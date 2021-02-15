<?php

namespace Sylapi\Courier\Gls\Message;

/**
 * Class adePickup_GetConsignIDs.
 */
class adePickup_GetConsignIDs
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
     * @param $confirm_id
     *
     * @return $this
     */
    public function prepareData($confirm_id)
    {
        $this->data['confirm_id'] = $confirm_id;

        return $this;
    }

    /**
     * @param $client
     * @param $session
     */
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
        if (!empty($this->response['return'])) {
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
