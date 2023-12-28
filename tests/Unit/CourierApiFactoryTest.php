<?php

namespace Sylapi\Courier\Gls\Tests\Unit;

use Sylapi\Courier\Courier;
use Sylapi\Courier\Gls\Session;
use Sylapi\Courier\Gls\Parameters;
use Sylapi\Courier\Gls\SessionFactory;
use Sylapi\Courier\Gls\Entities\Parcel;
use Sylapi\Courier\Gls\Entities\Sender;
use Sylapi\Courier\Gls\Entities\Booking;
use Sylapi\Courier\Gls\CourierApiFactory;
use Sylapi\Courier\Gls\Entities\Receiver;
use Sylapi\Courier\Gls\Entities\Shipment;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;


class CourierApiFactoryTest extends PHPUnitTestCase
{
    private $parameters = [
        'login'           => 'login',
        'password'        => 'password',
        'sandbox'         => true,
        'labelType'       => 'one_label_on_a4_rt_pdf',
    ];

    public function testGlsSessionFactory()
    {
        $credentials = [
            'login' => 'login',
            'password' => 'password',
            'sandbox' => true,
        ];

        $courierApiFactory = new CourierApiFactory(new SessionFactory());
        $glsSession = $courierApiFactory->create($credentials);

        $this->assertInstanceOf(Session::class, $glsSession);
    }

    public function testCourierFactoryCreate()
    {
        $glsCourierApiFactory = new CourierApiFactory(new SessionFactory());
        $courier = $glsCourierApiFactory->create($this->parameters);

        $this->assertInstanceOf(Courier::class, $courier);
        $this->assertInstanceOf(Booking::class, $courier->makeBooking());
        $this->assertInstanceOf(Parcel::class, $courier->makeParcel());
        $this->assertInstanceOf(Receiver::class, $courier->makeReceiver());
        $this->assertInstanceOf(Sender::class, $courier->makeSender());
        $this->assertInstanceOf(Shipment::class, $courier->makeShipment());
    }
}
