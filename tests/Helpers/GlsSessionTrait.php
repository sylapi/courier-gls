<?php

namespace Sylapi\Courier\Gls\Tests\Helpers;

use Sylapi\Courier\Gls\Session;
use Sylapi\Courier\Gls\Parameters;



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
        $sessionMock = $this->createMock(Session::class);
        $sessionMock->method('client')
            ->willReturn($soapMock);
        $sessionMock->method('token')
            ->willReturn('522a034bc583c200ebb67f51f9e242cb371d9fbcc0ab0a099e6358e078a690a2');
        $sessionMock->method('parameters')
            ->willReturn(Parameters::create([
                'labelType' => 'one_label_on_a4_lt_pdf',
            ]));
        $sessionMock->method('parameters')
            ->willReturn(Parameters::create([
                'labelType' => 'one_label_on_a4_lt_pdf',
            ]));

        return $sessionMock;
    }
}
