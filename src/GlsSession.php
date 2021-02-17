<?php
declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Gls\GlsParameters;
use Sylapi\Courier\Exceptions\InvalidArgumentException;
use Sylapi\Courier\Exceptions\ResponseException;
use SoapClient;
use SoapFault;

class GlsSession
{
	private $parameters;
	private $token;

	public function __construct(GlsParameters $parameters)
	{
		$this->parameters = $parameters;
		$this->initParameters();
		$this->client = null;
		$this->token = null;
	}
	
	public function parameters(): GlsParameters
	{
		return $this->parameters;
	}

	private function initParameters(): void
	{
		
	}

	public function token() : string
	{
		if(!$this->token)
		{
			$this->initializeSession();
		}

		return $this->token;
	}	

	public function client(): SoapClient
	{
		if(!$this->client)
		{
			$this->initializeSession();
		}

		return $this->client;
	}

	private function initializeSession() : void
	{
        $this->client = new \SoapClient($this->parameters->apiUrl, array('trace' => 1, 'cache_wsdl' => WSDL_CACHE_NONE));
        $this->client->soap_defencoding = 'UTF-8';
        $this->client->decode_utf8 = true;

        $params = array(
            'user_name' => $this->parameters->login,
            'user_password' => $this->parameters->password
		);

		$result = null;

		try {
			$result = $this->client->adeLogin($params);
		} catch (SoapFault $fault) {
			throw new InvalidArgumentException('GlsSession - Invalid credentials: ' . $fault->getMessage() .' Code: '. $fault->faultcode);
		}
	
		if (!is_object($result)) {
			throw new InvalidArgumentException('GlsSession - Invalid credentials');
		}

		$this->token = $result->return->session;
	}

	public function logout(): bool
	{
		if(!$this->token) {
			return false;
		}
		
		try {
			$this->client->adeLogout(['session' => $this->token]);
		} catch (SoapFault $e) {
			throw new ResponseException('GlsSession - Invalid credentials: ' . $e->getMessage() .' Code: '. $e->faultcode);
		}

		return true;
	}

}
