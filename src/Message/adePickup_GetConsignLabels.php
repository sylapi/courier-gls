<?php
namespace Sylapi\Courier\Gls\Message;

/**
 * Class adePickup_GetConsignLabels
 * @package Sylapi\Courier\Gls\Message
 */
class adePickup_GetConsignLabels
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

        $this->data = array(
            'tracking_id' => $parameters['tracking_id'],
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
                'number' => $this->data['tracking_id'],
                'mode' => $this->data['mode'],
            );

            $result = $client->adePickup_GetConsignLabels($params);
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
        if (!empty($this->response['return']) && $this->response['return'] > 0) {
            return $this->response['return'];
        }
        return null;
    }

    /**
     * @return bool
     */
    public function isSuccess() {
        if (!empty($this->response['return']) && $this->response['return'] > 0) {
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