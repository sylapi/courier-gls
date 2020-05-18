<?php

namespace Sylapi\Courier\Gls\Message;

class adePickup_GetConsignLabels
{
    private $data;
    private $response;

    public function prepareData($parameters)
    {
        $this->data = [
            'tracking_id' => $parameters['tracking_id'],
            'mode'        => $parameters['format'],
        ];

        return $this;
    }

    public function call($client, $session)
    {
        try {
            $params = [
                'session' => $session,
                'number'  => $this->data['tracking_id'],
                'mode'    => $this->data['mode'],
            ];

            $result = $client->adePickup_GetConsignLabels($params);
            if ($result) {
                $this->response['return'] = $result;
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
        if (!empty($this->response['return']) && $this->response['return'] > 0) {
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
