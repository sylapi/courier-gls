<?php

namespace Sylapi\Courier\Gls;

/**
 * Class Connect.
 */
abstract class Connect
{
    const API_LIVE = 'https://ade.gls-poland.com/adeplus/pm1/ade_webapi2.php?wsdl';

    const API_SANDBOX = 'https://ade-test.gls-poland.com/adeplus/pm1/ade_webapi2.php?wsdl';

    /**
     * @var string
     */
    protected $api_uri;
    /**
     * @var
     */
    protected $client;
    /**
     * @var
     */
    protected $session;
    /**
     * @var
     */
    protected $login;
    /**
     * @var
     */
    protected $password;
    /**
     * @var
     */
    protected $parameters;
    /**
     * @var
     */
    protected $error;
    /**
     * @var
     */
    protected $response;
    /**
     * @var string
     */
    protected $code = '';

    /**
     * Connect constructor.
     */
    public function __construct()
    {
        $this->api_uri = self::API_LIVE;
    }

    /**
     * @param $login
     *
     * @return mixed
     */
    protected function setLogin($login)
    {
        return $this->login = $login;
    }

    /**
     * @param $password
     *
     * @return mixed
     */
    protected function setPassword($password)
    {
        return $this->password = $password;
    }

    /**
     * @return string
     */
    public function getApiUri()
    {
        return $this->api_uri;
    }

    /**
     * @return string
     */
    public function sandbox()
    {
        return $this->api_uri = self::API_SANDBOX;
    }

    /**
     * @return bool
     */
    public function isSuccess()
    {
        return (empty($this->error)) ? true : false;
    }

    /**
     * @return mixed
     */
    public function getError()
    {
        return $this->error;
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    protected function setError($value)
    {
        if (!empty($value)) {
            return $this->error[] = $value;
        }
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    protected function setCode($value)
    {
        return $this->code = $value;
    }

    /**
     * @return string
     */
    public function getCode()
    {
        return $this->code;
    }

    /**
     * @param $value
     *
     * @return mixed
     */
    protected function setResponse($value)
    {
        return $this->response = $value;
    }

    /**
     * @return mixed
     */
    public function getResponse()
    {
        return $this->response;
    }

    /**
     * @param $soap
     */
    public function setSoapClient($soap)
    {
        $this->client = $soap;
        $this->session = '12345';
    }

    /**
     * @param $session
     */
    public function setSession($session)
    {
        $this->session = $session;
    }

    /**
     * @return array
     */
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
