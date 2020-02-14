<?php

namespace Sylapi\Courier\Gls\Message;

class adePreparingBox_DeleteConsign
{
    private $data;
    private $response;

    public function prepareData($prepare_id)
    {
        $this->data['prepare_id'] = $prepare_id;
        return $this;
    }

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

    public function getResponse() {
        if (!empty($this->response->return->id) && $this->response->return->id > 0) {
            return $this->response->return->id;
        }
        return null;
    }

    public function isSuccess() {
        if (!empty($this->response->return->id) && $this->response->return->id > 0) {
            return true;
        }
        return false;
    }

    public function getError() {
        return (!empty($this->response['error'])) ? $this->response['error'] : null;
    }

    public function getCode() {
        return (!empty($this->response['code'])) ? $this->response['code'] : 0;
    }
}