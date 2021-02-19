<?php

use Sylapi\Courier\CourierFactory;

$courier = CourierFactory::create('Gls', [
    'login'     => 'mylogin',
    'password'  => 'mypassword',
    'sandbox'   => true,
    'labelType' => 'one_label_on_a4_rt_pdf',
]);

/**
 * PostShipment.
 */
$booking = $courier->makeBooking();
$booking->setShipmentId('123456');

try {
    $response = $courier->postShipment($booking);
    if ($response->hasErrors()) {
        var_dump($response->getFirstError()->getMessage());
    } else {
        var_dump($response->referenceId); // Utworzony wewnetrzny idetyfikator zamowienia
        var_dump($response->shipmentId); // Zewnetrzny idetyfikator zamowienia
        var_dump($response->trackingId); // Zewnetrzny idetyfikator sledzenia przesylki
    }
} catch (\Exception $e) {
    var_dump($e->getMessage());
}
