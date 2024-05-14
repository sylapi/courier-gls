<?php

declare(strict_types=1);

namespace Sylapi\Courier\Gls;

use Sylapi\Courier\Courier;
use Sylapi\Courier\Gls\Entities\Credentials;

class CourierApiFactory
{
    private $glsSessionFactory;

    public function __construct(SessionFactory $glsSessionFactory)
    {
        $this->glsSessionFactory = $glsSessionFactory;
    }

    public function create(array $credentials): Courier
    {
        $credentials = Credentials::from($credentials);

        $session = $this->glsSessionFactory
                    ->session($credentials);

        return new Courier(
            new CourierCreateShipment($session),
            new CourierPostShipment($session),
            new CourierGetLabels($session),
            new CourierGetStatuses($session),
            new CourierMakeShipment(),
            new CourierMakeParcel(),
            new CourierMakeReceiver(),
            new CourierMakeSender(),
            new CourierMakeService(),
            new CourierMakeOptions(),
            new CourierMakeBooking(),
            new CourierMakeLabelType(),
        );
    }
}
