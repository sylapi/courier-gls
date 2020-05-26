<?php
namespace Sylapi\Courier\Gls\Message;

/**
 * Class adePickup_GetParcelLabels
 * @package Sylapi\Courier\Gls\Message
 */
class adePickup_GetParcelLabels
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
     * @return $this
     */
    public function prepareData($parameters) {

        $format = array(
            'A4' => 'four_labels_on_a4_pdf',
            'A6' => 'roll_160x100_pdf',
            'DPL' => 'roll_160x100_datamax',
            'ZPL' => 'roll_160x100_zebra',
            'EPL' => 'roll_160x100_zebra_epl',
        );

        $parameters['format'] = strtr($parameters['format'], $format);

        if (!empty($parameters['options']['custom']['label_format'])) {
            $parameters['format'] = $parameters['options']['custom']['label_format'];
        }
        
        $this->data = array(
            'trackings' => $parameters['trackings'],
            'mode' => $parameters['format'],
        );

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
                'numbers' => [
                    'items' => $this->data['trackings'],
                ],
                'mode' => $this->data['mode'],
            );

            $result = $client->adePickup_GetParcelsLabels($params);
            if (!empty($result->return->labels)) {
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
     * @return bool|string|null
     */
    public function getResponse() {
        if (!empty($this->response['return']->return->labels)) {
            return base64_decode($this->response['return']->return->labels);
        }
        return null;
    }

    /**
     * @return bool
     */
    public function isSuccess() {
        if (!empty($this->response['return']->return->labels)) {
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