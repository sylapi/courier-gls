<?php

namespace Sylapi\Courier\Gls\Message;

/**
 * Class adePreparingBox_Insert.
 */
class adePreparingBox_Insert
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
     * @param $parameters
     *
     * @return $this
     */
    public function prepareData($parameters)
    {
        $counties_map = ['uk' => 'gb'];
        $ppe = null;
        $srs = (isset($parameters['options']['srs'])) ? $parameters['options']['srs'] : false;

        if ($srs) {
            $ppe = [
                'sname1'   => $parameters['sender']['name'],
                'scountry' => strtr(strtolower($parameters['sender']['country']), $counties_map),
                'szipcode' => $parameters['sender']['postcode'],
                'scity'    => $parameters['sender']['city'],
                'sstreet'  => $parameters['sender']['street'],
                'sphone'   => $parameters['sender']['phone'],

                'rname1'   => $parameters['receiver']['name'],
                'rname2'   => '',
                'rname3'   => '',
                'rcountry' => strtr(strtolower($parameters['receiver']['country']), $counties_map),
                'rzipcode' => $parameters['receiver']['postcode'],
                'rcity'    => $parameters['receiver']['city'],
                'rstreet'  => $parameters['receiver']['street'],
                'rphone'   => $parameters['receiver']['phone'],

                'references' => $parameters['options']['references'],
                'weight'     => $parameters['options']['weight'],
            ];
        }

        $this->data = [
            'rname1'     => $parameters['receiver']['name'],
            'rname2'     => '',
            'rname3'     => '',
            'rcountry'   => strtr(strtolower($parameters['receiver']['country']), $counties_map),
            'rzipcode'   => $parameters['receiver']['postcode'],
            'rcity'      => $parameters['receiver']['city'],
            'rstreet'    => $parameters['receiver']['street'],
            'rphone'     => $parameters['receiver']['phone'],
            'rcontact'   => '',
            'references' => $parameters['options']['references'],
            'notes'      => $parameters['options']['note'],
            'quantity'   => 1,
            'weight'     => $parameters['options']['weight'],
            'date'       => $srs == true ? date(date('Y-m-d', strtotime('+21 days')), time()) : date('Y-m-d'),
            'pfc'        => 1,
            'sendaddr'   => [
                'name1'   => $parameters['sender']['name'],
                'name2'   => '',
                'name3'   => '',
                'country' => strtr(strtolower($parameters['sender']['country']), $counties_map),
                'zipcode' => $parameters['sender']['postcode'],
                'city'    => $parameters['sender']['city'],
                'street'  => $parameters['sender']['street'],
            ],
            'srv_bool' => [
                'cod'        => ($parameters['options']['cod'] == true) ? true : false,
                'cod_amount' => ($parameters['options']['cod'] == true) ? $parameters['options']['amount'] : '',
                's10'        => (isset($parameters['options']['hour10'])) ? $parameters['options']['hour10'] : false,
                's12'        => (isset($parameters['options']['hour12'])) ? $parameters['options']['hour12'] : false,
                'sat'        => (isset($parameters['options']['saturday'])) ? $parameters['options']['saturday'] : false,
                'srs'        => $srs,
            ],
            'srv_ade'   => '',
            'srv_daw'   => '',
            'srv_ident' => '',
            'srv_ppe'   => $ppe,
            'srv_sds'   => '',
            'parcels'   => [[
                'reference' => $parameters['options']['references'],
                'weight'    => $parameters['options']['weight'],
            ]],
        ];

        return $this;
    }

    /**
     * @param $client
     * @param $session
     */
    public function call($client, $session)
    {
        try {
            if ($this->data['rcountry'] != 'pl') {
                $params = [
                    'session' => $session,
                    'lang'    => 'gb',
                ];
                $client->adeLang_Change($params);
            }

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

    /**
     * @return |null
     */
    public function getResponse()
    {
        if (!empty($this->response['return']) && $this->response['return'] > 0) {
            return $this->response['return'];
        }

        return null;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        if (!empty($this->response['return']) && $this->response['return'] > 0) {
            return true;
        }

        return false;
    }

    /**
     * @return |null
     */
    public function getError()
    {
        return (!empty($this->response['error'])) ? $this->response['error'] : null;
    }

    /**
     * @return int
     */
    public function getCode()
    {
        return (!empty($this->response['code'])) ? $this->response['code'] : 0;
    }
}
