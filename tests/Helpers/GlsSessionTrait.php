<?php

namespace Sylapi\Courier\Gls\Tests\Helpers;

use Sylapi\Courier\Gls\GlsParameters;
use Sylapi\Courier\Gls\GlsSession;

trait GlsSessionTrait
{
    private function getSoapMock()
    {
        return $this->getMockBuilder('SoapClient')
                    ->disableOriginalConstructor()
                    ->getMock();
    }

    private function getSessionMock($soapMock)
    {
        $sessionMock = $this->createMock(GlsSession::class);
        $sessionMock->method('client')
            ->willReturn($soapMock);
        $sessionMock->method('token')
            ->willReturn('522a034bc583c200ebb67f51f9e242cb371d9fbcc0ab0a099e6358e078a690a2');
        $sessionMock->method('parameters')
            ->willReturn(GlsParameters::create([
                'labelType' => 'one_label_on_a4_lt_pdf',
            ]));
        $sessionMock->method('parameters')
            ->willReturn(GlsParameters::create([
                'labelType' => 'one_label_on_a4_lt_pdf',
            ]));

        return $sessionMock;
    }
}
