<?php

namespace Sylapi\Courier\Gls\Message;

class adePreparingBox_Insert
{
    private $data;
    private $response;

    public function prepareData($parameters)
    {
        $this->data = [
            'rname1'     => $parameters['receiver']['name'],
            'rname2'     => '',
            'rname3'     => '',
            'rcountry'   => $parameters['receiver']['country'],
            'rzipcode'   => $parameters['receiver']['postcode'],
            'rcity'      => $parameters['receiver']['city'],
            'rstreet'    => $parameters['receiver']['street'],
            'rphone'     => $parameters['receiver']['name'],
            'rcontact'   => '',
            'references' => $parameters['options']['references'],
            'notes'      => $parameters['options']['note'],
            'quantity'   => 1,
            'weight'     => $parameters['options']['weight'],
            'date'       => date('Y-m-d', time()),
            'pfc'        => 1,
            'sendaddr'   => [
                'name1'   => $parameters['sender']['name'],
                'name2'   => '',
                'name3'   => '',
                'country' => $parameters['sender']['country'],
                'zipcode' => $parameters['sender']['postcode'],
                'city'    => $parameters['sender']['city'],
                'street'  => $parameters['sender']['street'],
            ],
            'srv_bool' => [
                'cod'        => ($parameters['options']['cod'] == true) ? true : false,
                'cod_amount' => ($parameters['options']['cod'] == true) ?: '',
                's10'        => false,
                's12'        => false,
                'sat'        => false,
            ],
            'srv_ade'   => '',
            'srv_daw'   => '',
            'srv_ident' => '',
            'srv_ppe'   => '',
            'srv_sds'   => '',
            'parcels'   => [[
                'reference' => $parameters['options']['references'],
                'weight'    => $parameters['options']['weight'],
            ]],
        ];

        return $this;
    }

    public function call($client, $session)
    {
        try {
            $params = [
                'session'           => $session,
                'consign_prep_data' => $this->data,
            ];

            $result = $client->adePreparingBox_Insert($params);
            if (isset($result->return->id)) {
                $this->response['return'] = $result->return->id;
            } elseif (!empty($result->faultcode)) {
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
