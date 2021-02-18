<?php

declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use SoapClient;
use SoapFault;
use Sylapi\Courier\Exceptions\InvalidArgumentException;

class GlsSession
{
    private $parameters;
    private $token;
    private $client;

    public function __construct(GlsParameters $parameters)
    {
        $this->parameters = $parameters;
        $this->initParameters();
        $this->token = null;
        $this->client = null;
    }

    public function parameters(): GlsParameters
    {
        return $this->parameters;
    }

    private function initParameters(): void
    {
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
            $this->initializeSession();
        }

        return $this->client;
    }

    private function initializeSession(): void
    {
        $this->client = new \SoapClient($this->parameters->apiUrl, ['trace' => 1, 'cache_wsdl' => WSDL_CACHE_NONE]);
        $this->client->soap_defencoding = 'UTF-8';
        $this->client->decode_utf8 = true;

        $params = [
            'user_name'     => $this->parameters->login,
            'user_password' => $this->parameters->password,
        ];

        $result = null;

        try {
            /* @phpstan-ignore-next-line */
            $result = $this->client->adeLogin($params);
        } catch (SoapFault $fault) {
            throw new InvalidArgumentException('GlsSession - Invalid credentials: '.$fault->getMessage().' Code: '.$fault->faultcode);
        }

        if (!is_object($result)) {
            throw new InvalidArgumentException('GlsSession - Invalid credentials');
        }

        $this->token = $result->return->session;
    }
}
