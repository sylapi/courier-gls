# Courier-GLS

![StyleCI](https://github.styleci.io/repos/240472865/shield?style=flat&branch=new&style=flat) ![PHPStan](https://img.shields.io/badge/PHPStan-level%205-brightgreen.svg?style=flat) [![Build](https://github.com/sylapi/courier-gls/actions/workflows/build.yaml/badge.svg?branch=new&event=push)](https://github.com/sylapi/courier-gls/actions/workflows/build.yaml) [![codecov.io](https://codecov.io/github/sylapi/courier-gls/coverage.svg?branch=new)](https://codecov.io/github/sylapi/courier-gls/branch/new/)

## Init

```php
    /**
    * @return Sylapi\Courier\Courier
    */
    $courier = CourierFactory::create('Gls',[
        'login' => 'mylogin',
        'password' => 'mypassword',
        'sandbox' => true,
        'labelType' => 'one_label_on_a4_rt_pdf'
        'services' => [
            'srs' => true, // true/false
            // 's10' => true, // true/false
            's12' => true, // true/false
            'sat' => true, // true/false
            'cod' => true, // cod
            'cod_amount' => 100.00
        ]
    ]);
```

## CreateShipment

```php
    $sender = $courier->makeSender();
    $sender->setFullName('Nazwa Firmy/Nadawca')
        ->setStreet('Ulica')
        ->setHouseNumber('2a')
        ->setApartmentNumber('1')
        ->setCity('Miasto')
        ->setZipCode('66100')
        ->setCountry('Poland')
        ->setCountryCode('pl')
        ->setContactPerson('Jan Kowalski')
        ->setEmail('my@email.com')
        ->setPhone('48500600700')


    $receiver = $courier->makeReceiver();

    $receiver->setFirstName('Jan')
        ->setSurname('Nowak')
        ->setStreet('Ulica')
        ->setHouseNumber('2a')
        ->setApartmentNumber('1')
        ->setCity('Miasto')
        ->setZipCode('66100')
        ->setCountry('Poland')
        ->setCountryCode('pl')
        ->setContactPerson('Jan Kowalski')
        ->setEmail('my@email.com')
        ->setPhone('48500600700')

    $parcel = $courier->makeParcel();
    $parcel->setWeight(1.5);

    $shipment = $courier->makeShipment();
    $shipment->setSender($sender)
        ->setReceiver($receiver)
        ->setParcel($parcel)
        ->setContent('Zawartość przesyłki');


    try {
        $response = $courier->createShipment($shipment);
        if ($response->hasErrors()) {
            var_dump($response->getFirstError()->getMessage());
        } else {
            var_dump($response->shipmentId); // Zewnetrzny idetyfikator zamowienia
        }
    } catch (\Exception $e) {
        var_dump($e->getMessage());
    }
```

## PostShipment

```php
    /**
     * Init Courier
     */

    $booking = $courier->makeBooking();
    $booking->setShipmentId('123456');

    try {
        $response = $courier->postShipment($booking);
        if ($response->hasErrors()) {
            var_dump($response->getFirstError()->getMessage());
        } else {
            var_dump($response->shipmentId); // Zewnetrzny idetyfikator zamowienia
        }
    } catch (\Exception $e) {
        var_dump($e->getMessage());
    }
```

## GetLabel

```php
    /**
     * Init Courier
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
```
