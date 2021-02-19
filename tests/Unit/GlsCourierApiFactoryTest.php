<?php

namespace Sylapi\Courier\Gls\Tests\Unit;

use PHPUnit\Framework\TestCase as PHPUnitTestCase;
use Sylapi\Courier\Courier;
use Sylapi\Courier\Gls\GlsBooking;
use Sylapi\Courier\Gls\GlsCourierApiFactory;
use Sylapi\Courier\Gls\GlsParameters;
use Sylapi\Courier\Gls\GlsParcel;
use Sylapi\Courier\Gls\GlsReceiver;
use Sylapi\Courier\Gls\GlsSender;
use Sylapi\Courier\Gls\GlsSession;
use Sylapi\Courier\Gls\GlsSessionFactory;
use Sylapi\Courier\Gls\GlsShipment;

class GlsCourierApiFactoryTest extends PHPUnitTestCase
{
    private $parameters = [
        'login'           => 'login',
        'password'        => 'password',
        'sandbox'         => true,
        'labelType'       => 'one_label_on_a4_rt_pdf',
    ];

    public function testGlsSessionFactory()
    {
        $glsSessionFactory = new GlsSessionFactory();
        $glsSession = $glsSessionFactory->session(
            GlsParameters::create($this->parameters)
        );
        $this->assertInstanceOf(GlsSession::class, $glsSession);
    }

    public function testCourierFactoryCreate()
    {
        $glsCourierApiFactory = new GlsCourierApiFactory(new GlsSessionFactory());
        $courier = $glsCourierApiFactory->create($this->parameters);

        $this->assertInstanceOf(Courier::class, $courier);
        $this->assertInstanceOf(GlsBooking::class, $courier->makeBooking());
        $this->assertInstanceOf(GlsParcel::class, $courier->makeParcel());
        $this->assertInstanceOf(GlsReceiver::class, $courier->makeReceiver());
        $this->assertInstanceOf(GlsSender::class, $courier->makeSender());
        $this->assertInstanceOf(GlsShipment::class, $courier->makeShipment());
    }
}
