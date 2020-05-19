<?php
namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Gls\Message\adePreparingBox_Insert;
use Sylapi\Courier\Gls\Message\adePickup_GetParcelLabel;
use Sylapi\Courier\Gls\Message\adePickup_GetParcelLabels;
use Sylapi\Courier\Gls\Message\adePreparingBox_DeleteConsign;
use Sylapi\Courier\Gls\Message\adePickup_GetConsignIDs;
use Sylapi\Courier\Gls\Message\adePickup_GetConsign;
use Sylapi\Courier\Gls\Message\adePickup_Create;


class Gls extends Connect
{

    public function initialize($parameters) {

        $this->parameters = $parameters;

        if (!empty($parameters['accessData'])) {

            $this->setLogin($parameters['accessData']['login']);
            $this->setPassword($parameters['accessData']['password']);
        }
        else {
            $this->setError('Access Data is empty');
        }
    }

    public function login() {

        if (empty($this->client)) {

            $this->client = new \SoapClient($this->getApiUri(), array('trace' => 1, 'cache_wsdl' => WSDL_CACHE_NONE));
            $this->client->soap_defencoding = 'UTF-8';
            $this->client->decode_utf8 = true;

            $params = array(
                'user_name' => $this->login,
                'user_password' => $this->password
            );

            try {
                if ($this->client) {

                    $result = $this->client->adeLogin($params);
                    if ($result) {
                        $this->setSession($result->return->session);
                    }
                }
            } catch (\Exception $e) {
                $this->setError($e->faultcode);
            }
        }
        return false;
    }

    public function isSession() {
        if (!empty($this->session)) {
            return true;
        }
        return false;
    }

    public function ValidateData() {

        $this->login();

        if ($this->isSession()) {

            $adePreparingBoxInsert = new adePreparingBox_Insert();
            $adePreparingBoxInsert->prepareData($this->parameters)->call($this->client, $this->session);

            if ($adePreparingBoxInsert->isSuccess()) {

                $prepare_id = $adePreparingBoxInsert->getResponse();
                $this->setResponse($prepare_id);

                // Delete prepare box
                $this->preparing_delete($prepare_id);
            }
            else {
                $this->setError($adePreparingBoxInsert->getError());
                $this->setCode($adePreparingBoxInsert->getCode());
            }
        }
    }

    public function GetLabel() {

        $this->login();

        if ($this->isSession()) {

            $adePreparingBoxInsert = new adePickup_GetParcelLabel();
            $adePreparingBoxInsert->prepareData($this->parameters)->call($this->client, $this->session);

            $response = $adePreparingBoxInsert->getResponse();
            if (!empty($response)) {
                $response = base64_decode($response);
            }

            $this->setResponse($response);
            $this->setError($adePreparingBoxInsert->getError());
        }
    }

    public function GetLabels()
    {
        $this->login();

        if ($this->isSession()) {

            $adePickupGetParcelLabels = new adePickup_GetParcelLabels();
            $adePickupGetParcelLabels->prepareData($this->parameters)->call($this->client, $this->session);

            $this->setResponse($adePickupGetParcelLabels->getResponse());
            $this->setError($adePickupGetParcelLabels->getError());
        }
    }

    public function CreatePackage() {

        $this->login();

        if ($this->isSession()) {

            $adePreparingBoxInsert = new adePreparingBox_Insert();
            $adePreparingBoxInsert->prepareData($this->parameters)->call($this->client, $this->session);

            $this->setResponse($adePreparingBoxInsert->getResponse());
            $this->setError($adePreparingBoxInsert->getError());

            if ($adePreparingBoxInsert->isSuccess()) {

                $prepare_id = $adePreparingBoxInsert->getResponse();

                $adePickupCreate = new adePickup_Create();
                $adePickupCreate->prepareData($prepare_id)->call($this->client, $this->session);

                $this->setResponse($adePickupCreate->getResponse());
                $this->setError($adePickupCreate->getError());

                if ($adePickupCreate->isSuccess()) {

                    $confirm_id = $adePickupCreate->getResponse();

                    $adePickupGetConsignIDs = new adePickup_GetConsignIDs();
                    $adePickupGetConsignIDs->prepareData($confirm_id)->call($this->client, $this->session);

                    $this->setResponse($adePickupGetConsignIDs->getResponse());
                    $this->setError($adePickupGetConsignIDs->getError());

                    if ($adePickupGetConsignIDs->isSuccess()) {

                        $consign_id = $adePickupGetConsignIDs->getResponse();

                        $adePickupGetConsign = new adePickup_GetConsign();
                        $adePickupGetConsign->prepareData($consign_id)->call($this->client, $this->session);

                        if ($adePickupGetConsignIDs->isSuccess()) {

                            $response = $adePickupGetConsign->getResponse();
                            if ($response->return->parcels->items->number) {

                                $response = array(
                                    'tracking_id' => $response->return->parcels->items->number,
                                );
                            }
                        }

                        $this->setResponse($response);
                        $this->setError($adePickupGetConsign->getError());
                    }
                }
            }
        }
    }

    public function CheckPrice() {

        $response = (isset($this->parameters['options']['custom']['parcel_cost'])) ? $this->parameters['options']['custom']['parcel_cost'] : 0;
        $this->setResponse($response);
    }



    private function preparing_delete($prepare_id) {

        $adePreparingBoxDeleteConsign = new adePreparingBox_DeleteConsign();
        $adePreparingBoxDeleteConsign->prepareData($prepare_id)->call($this->client, $this->session);

        if (!$adePreparingBoxDeleteConsign->isSuccess()) {

            $this->setResponse($adePreparingBoxDeleteConsign->getResponse());
            $this->setError($adePreparingBoxDeleteConsign->getError());
        }
    }
}