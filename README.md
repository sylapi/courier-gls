# Courier-gls

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
            ->setReceiver($receiver);
            ->setParcel($parcel);
            ->setContent('Zawartość przesyłki')

    try{
        /**
        * @return array | Indetyfikatory dotyczace przesylki
        */
        $response = $courier->createShipment($shipment);
        echo $response['shipmentId']; // Zewnetrzny idetyfikator zamowienia
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```

## PostShipment

```php
    /**
     * Init Courier
     */
    $booking = $courier->makeBooking();
    $booking->setShipmentId('1111');
    try{
        /**
        * @return array | Indetyfikatory dotyczace przesylki
        */
        $response = $courier->postShipment($booking);
        echo $response['referenceId']; // Utworzony wewnetrzny idetyfikator zamowienia
        echo $response['shipmentId']; // Zewnetrzny idetyfikator zamowienia
        echo $response['trackingId']; // Zewnetrzny idetyfikator sledzenia przesylki
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```

## GetStatus

```php
    /**
     * Nie jest dostępne
     */
```


## GetLabel

```php
    /**
     * Init Courier
     */
    try{
        /**
        * @return string | Plik z etykietami (zakodowany MIME base64)
        */
        $label = $courier->getLabel('1111');
    } catch (\Exception $e) {
        echo $e->getMessage();
    }
```