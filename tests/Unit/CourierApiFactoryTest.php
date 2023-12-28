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
use Sylapi\Courier\Gls\Entities\Credentials;
use PHPUnit\Framework\TestCase as PHPUnitTestCase;


class CourierApiFactoryTest extends PHPUnitTestCase
{
    public function testGlsSessionFactory()
    {
        $credentials = new Credentials();
        $credentials->setLogin('login');
        $credentials->setPassword('password');
        $credentials->setSandbox(true);
        $courierApiFactory = new SessionFactory();
        $glsSession = $courierApiFactory->session($credentials);
        
        $this->assertInstanceOf(Session::class, $glsSession);
    }

    public function testCourierFactoryCreate()
    {
        $credentials = [
            'login' => 'login',
            'password' => 'password',
            'sandbox' => true,
        ];

        $glsCourierApiFactory = new CourierApiFactory(new SessionFactory());
        $courier = $glsCourierApiFactory->create($credentials);

        $this->assertInstanceOf(Courier::class, $courier);
        $this->assertInstanceOf(Booking::class, $courier->makeBooking());
        $this->assertInstanceOf(Parcel::class, $courier->makeParcel());
        $this->assertInstanceOf(Receiver::class, $courier->makeReceiver());
        $this->assertInstanceOf(Sender::class, $courier->makeSender());
        $this->assertInstanceOf(Shipment::class, $courier->makeShipment());
    }
}
