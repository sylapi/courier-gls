<?php

namespace Sylapi\Courier\Gls\Message;

/**
 * Class adePreparingBox_DeleteConsign
 * @package Sylapi\Courier\Gls\Message
 */
class adePreparingBox_DeleteConsign
{
    /**
     * @var
     */
    private $data;
    /**
     * @var
     */
    private $response;

    /**
     * @param $prepare_id
     * @return $this
     */
    public function prepareData($prepare_id)
    {
        $this->data['prepare_id'] = $prepare_id;
        return $this;
    }

    /**
     * @param $client
     * @param $session
     */
    public function call($client, $session) {

        try {

            $params = array(
                'session' => $session,
                'id' => $this->data['prepare_id'],
            );

            $this->response = $client->adePreparingBox_DeleteConsign($params);
        }
        catch (\SoapFault $e) {
            $this->response['error'] = $e->faultactor;
        }
    }

    /**
     * @return |null
     */
    public function getResponse() {
        if (!empty($this->response->return->id) && $this->response->return->id > 0) {
            return $this->response->return->id;
        }
        return null;
    }

    /**
     * @return bool
     */
    public function isSuccess() {
        if (!empty($this->response->return->id) && $this->response->return->id > 0) {
            return true;
        }
        return false;
    }

    /**
     * @return |null
     */
    public function getError() {
        return (!empty($this->response['error'])) ? $this->response['error'] : null;
    }

    /**
     * @return int
     */
    public function getCode() {
        return (!empty($this->response['code'])) ? $this->response['code'] : 0;
    }
}