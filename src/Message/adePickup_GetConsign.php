<?php
namespace Sylapi\Courier\Gls\Message;

class adePickup_GetConsign
{
    private $data;
    private $response;

    public function prepareData($consign_id) {

        $this->data['consign_id'] = $consign_id;

        return $this;
    }

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

    public function getResponse() {
        if (!empty($this->response['return']->return->parcels->items->number) && $this->response['return']->return->parcels->items->number > 0) {
            return $this->response['return'];
        }
        return null;
    }

    public function isSuccess() {
        if (!empty($this->response['return']->return->parcels->items->number) && $this->response['return']->return->parcels->items->number > 0) {
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