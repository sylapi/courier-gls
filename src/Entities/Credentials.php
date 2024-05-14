<?php

declare(strict_types=1);

namespace Sylapi\Courier\Gls\Entities;

use Sylapi\Courier\Abstracts\Credentials as CredentialsAbstract;

class Credentials extends CredentialsAbstract
{
    public function setTrackingApiUrl(string $apiTrackingUrl): self
    {
        $this->set('apiTrackingUrl', $apiTrackingUrl);

        return $this;
    }

    public function getTrackingApiUrl(): string
    {
        return $this->get('apiTrackingUrl');
    }

    public function setTrackingLogin(string $trackingLogin): self
    {
        $this->set('trackingLogin', $trackingLogin);

        return $this;
    }

    public function getTrackingLogin(): string
    {
        return $this->get('trackingLogin');
    }

    public function setTrackingPassword(string $trackingPassword): self
    {
        $this->set('trackingPassword', $trackingPassword);

        return $this;
    }

    public function getTrackingPassword(): string
    {
        return $this->get('trackingPassword');
    }
}
