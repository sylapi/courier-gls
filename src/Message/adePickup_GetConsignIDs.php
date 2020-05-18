<?php

namespace Sylapi\Courier\Gls\Message;

class adePickup_GetConsignIDs
{
    private $data;
    private $response;

    public function prepareData($confirm_id)
    {
        $this->data['confirm_id'] = $confirm_id;

        return $this;
    }

    public function call($client, $session)
    {
        try {
            $params = [
                'session'  => $session,
                'id'       => $this->data['confirm_id'],
                'id_start' => 0,
            ];

            $result = $client->adePickup_GetConsignIDs($params);
            if (!empty($result->return->items)) {
                $this->response['return'] = $result->return->items;
            } else {
                $this->response['error'] = $result->faultcode.' | '.$result->faultstring;
                $this->response['code'] = $result->faultactor.'';
            }
        } catch (\SoapFault $e) {
            $this->response['error'] = $e->faultactor.' | '.$e->faultstring;
            $this->response['code'] = $e->faultcode.'';
        }
    }

    public function getResponse()
    {
        if (!empty($this->response['return']) && $this->response['return'] > 0) {
            return $this->response['return'];
        }

        return null;
    }

    public function isSuccess()
    {
        if (!empty($this->response['return'])) {
            return true;
        }

        return false;
    }

    public function getError()
    {
        return (!empty($this->response['error'])) ? $this->response['error'] : null;
    }

    public function getCode()
    {
        return (!empty($this->response['code'])) ? $this->response['code'] : 0;
    }
}
