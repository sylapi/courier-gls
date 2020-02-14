<?php
namespace Sylapi\Courier\Gls\Message;

class adePickup_Create
{
    private $data;
    private $response;

    public function prepareData($consigns_id) {

        $this->data['consigns_ids'] = (is_array($consigns_id)) ? $consigns_id : array($consigns_id);

        return $this;
    }

    public function call($client, $session) {

        try {

            $params = array(
                'session' => $session,
                'consigns_ids' => $this->data['consigns_ids'],
                'desc' => '',
            );

            $result = $client->adePickup_Create($params);
            if ($result) {
                $this->response['return'] = $result->return->id;
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
        if (!empty($this->response['return']) && $this->response['return'] > 0) {
            return $this->response['return'];
        }
        return null;
    }

    public function isSuccess() {
        if (!empty($this->response['return']) && $this->response['return'] > 0) {
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