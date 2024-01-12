<?php

declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use SoapFault;
use SoapClient;
use Sylapi\Courier\Gls\Entities\Credentials;
use Sylapi\Courier\Exceptions\InvalidArgumentException;

class Session
{
    private $credentials;
    private $token;
    private $client;

    public function __construct(Credentials $credentials)
    {
        $this->credentials = $credentials;
        $this->token = null;
        $this->client = null;
    }

    public function credentials(): Credentials
    {
        return $this->credentials;
    }

    public function token(): string
    {
        if (!$this->token) {
            $this->initializeSession();
        }

        return $this->token;
    }

    public function client(): SoapClient
    {
        if (!$this->client) {
            $this->client = $this->initializeSession();
        }

        return $this->client;
    }

    public function clientTracking(): SoapClient
    {
        return new \SoapClient($this->credentials->getTrackingApiUrl(), ['trace' => 1, 'cache_wsdl' => WSDL_CACHE_NONE]);
    }

    private function initializeSession(): SoapClient
    {
        $this->client = new \SoapClient($this->credentials->getApiUrl(), ['trace' => 1, 'cache_wsdl' => WSDL_CACHE_NONE]);
        $this->client->soap_defencoding = 'UTF-8';
        $this->client->decode_utf8 = true;

        $params = [
            'user_name'     => $this->credentials->getLogin(),
            'user_password' => $this->credentials->getPassword(),
        ];

        $result = null;

        try {
            $result = $this->client->adeLogin($params);
        } catch (SoapFault $fault) {
            throw new InvalidArgumentException('GlsSession - Invalid credentials: '.$fault->getMessage().' Code: '.$fault->faultcode);
        }

        if (!is_object($result)) {
            throw new InvalidArgumentException('GlsSession - Invalid credentials');
        }

        $this->token = $result->return->session;

        return $this->client;
    }
}
