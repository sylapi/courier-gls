<?php

use Sylapi\Courier\CourierFactory;

$courier = CourierFactory::create('Gls', [
    'login'     => 'mylogin',
    'password'  => 'mypassword',
    'sandbox'   => true,
    'labelType' => 'one_label_on_a4_rt_pdf',
]);

/**
 * GetLabel.
 */
try {
    $response = $courier->getLabel('123456');
    if ($response->hasErrors()) {
        var_dump($response->getFirstError()->getMessage());
    } else {
        var_dump((string) $response);
    }
} catch (\Exception $e) {
    var_dump($e->getMessage());
}
