<?php
namespace Sylapi\Courier\Gls\Message;

/**
 * Class adePickup_GetConsign
 * @package Sylapi\Courier\Gls\Message
 */
class adePickup_GetConsign
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
     * @param $consign_id
     * @return $this
     */
    public function prepareData($consign_id) {

        $this->data['consign_id'] = $consign_id;

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
                'id' => $this->data['consign_id'],
            );

            $result = $client->adePickup_GetConsign($params);
            if ($result) {
                $this->response['return'] = $result;
            }
            else {
                $this->response['error'] = $result->faultcode.' | '.$result->faultstring;
                $this->response['code'] = $result->faultactor.'';
            }
        }
        catch (\SoapFault $e) {
            $this->response['error'] = $e->faultactor.' | '.$e->faultstring;
            $this->response['code'] = $e->faultcode.'';
        }
    }

    /**
     * @return |null
     */
    public function getResponse() {
        if (!empty($this->response['return']->return->parcels->items->number) && $this->response['return']->return->parcels->items->number > 0) {
            return $this->response['return'];
        }
        return null;
    }

    /**
     * @return bool
     */
    public function isSuccess() {
        if (!empty($this->response['return']->return->parcels->items->number) && $this->response['return']->return->parcels->items->number > 0) {
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