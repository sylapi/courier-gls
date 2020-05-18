<?php

namespace Sylapi\Courier\Gls;

abstract class Connect
{
    const API_LIVE = 'https://ade.gls-poland.com/adeplus/pm1/ade_webapi2.php?wsdl';
    const API_SANDBOX = 'https://ade-test.gls-poland.com/adeplus/pm1/ade_webapi2.php?wsdl';

    protected $api_uri;
    protected $client;
    protected $session;
    protected $login;
    protected $password;
    protected $parameters;
    protected $error;
    protected $response;
    protected $code = '';

    public function __construct()
    {
        $this->api_uri = self::API_LIVE;
    }

    protected function setLogin($login)
    {
        return $this->login = $login;
    }

    protected function setPassword($password)
    {
        return $this->password = $password;
    }

    public function getApiUri()
    {
        return $this->api_uri;
    }

    public function sandbox()
    {
        return $this->api_uri = self::API_SANDBOX;
    }

    public function isSuccess()
    {
        return (empty($this->error)) ? true : false;
    }

    public function getError()
    {
        return $this->error;
    }

    protected function setError($value)
    {
        if (!empty($value)) {
            return $this->error[] = $value;
        }
    }

    protected function setCode($value)
    {
        return $this->code = $value;
    }

    public function getCode()
    {
        return $this->code;
    }

    protected function setResponse($value)
    {
        return $this->response = $value;
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function setSoapClient($soap)
    {
        $this->client = $soap;
        $this->session = '12345';
    }

    public function setSession($session)
    {
        $this->session = $session;
    }

    public function debug()
    {
        return [
            'success'  => $this->isSuccess(),
            'code'     => $this->getCode(),
            'error'    => $this->getError(),
            'response' => $this->getResponse(),
        ];
    }
}
