<?php

declare(strict_types=1);

namespace Sylapi\Courier\Gls;

class SessionFactory
{
    private $sessions = [];
    private $parameters;

    //These constants can be extracted into injected configuration
    const API_LIVE = 'https://ade.gls-poland.com/adeplus/pm1/ade_webapi2.php?wsdl';
    const API_SANDBOX = 'https://ade-test.gls-poland.com/adeplus/pm1/ade_webapi2.php?wsdl';

    public function session(Parameters $parameters): Session
    {
        $this->parameters = $parameters;
        $this->parameters->apiUrl = ($this->parameters->sandbox) ? self::API_SANDBOX : self::API_LIVE;

        $key = sha1($this->parameters->apiUrl.':'.$this->parameters->login.':'.$this->parameters->password);

        return (isset($this->sessions[$key])) ? $this->sessions[$key] : ($this->sessions[$key] = new Session($this->parameters));
    }
}
