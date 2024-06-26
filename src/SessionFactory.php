<?php

declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Gls\Entities\Credentials;

class SessionFactory
{
    private $sessions = [];

    //These constants can be extracted into injected configuration
    const API_LIVE = 'https://ade.gls-poland.com/adeplus/pm1/ade_webapi2.php?wsdl';
    const API_SANDBOX = 'https://ade-test.gls-poland.com/adeplus/pm1/ade_webapi2.php?wsdl';
    const API_TRACKING_LIVE = 'http://www.gls-group.eu/276-I-PORTAL-WEBSERVICE/services/Tracking/wsdl/Tracking.wsdl';

    public function session(Credentials $credentials): Session
    {
        $apiUrl = $credentials->isSandbox() ? self::API_SANDBOX : self::API_LIVE;

        $credentials->setApiUrl($apiUrl);
        $credentials->setTrackingApiUrl(self::API_TRACKING_LIVE);

        $key = sha1( $apiUrl.':'.$credentials->getLogin().':'.$credentials->getPassword());

        return (isset($this->sessions[$key])) ? $this->sessions[$key] : ($this->sessions[$key] = new Session($credentials));
    }
}
