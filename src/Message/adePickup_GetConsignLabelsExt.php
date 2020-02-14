<?php
namespace Sylapi\Courier\Gls\Message;

class adePickup_GetConsignLabelsExt
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
                'mode' => 'one_label_on_a4_lt_pdf',
            );

            $result = $client->adePickup_GetConsignLabelsExt($params);
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