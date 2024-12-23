# Introduction

A PHP API wrapper for [Notch Pay](https://notchpay.co/).

## Installation

You can install the package via composer:

```bash
composer require notchpay/notchpay-php
```

## Usage

Do a redirect to the authorization URL received from calling the payments/initialize endpoint. This URL is valid for one time use, so ensure that you generate a new URL per payment.

When the payment is successful, we will call your callback URL (as setup in your dashboard or while initializing the transaction) and return the reference sent in the first step as a query parameter.

If you use a test public key, we will call your test callback url, otherwise, we'll call your live callback url.

### 0. Prerequisites

Confirm that your server can conclude a TLSv1.2 connection to Notch Pay's servers. Most up-to-date software have this capability. Contact your service provider for guidance if you have any SSL errors.
_Don't disable SSL peer verification!_

### 1. Prepare your parameters

`email`, `amount` and `currency` are the most common compulsory parameters. Do send a unique email per customer.
The amount accept numeric value value.
The currency accept currency ISO 3166.
For instance, to accept `For US Dollar`, please send `USD` as the currency.


### 2. Initialize a onetime payments

Initialize a payment by calling our API.

```php
use NotchPay\NotchPay;
use NotchPay\Payment;

NotchPay::setApiKey('sk_1234abcd');

try {
    $tranx = Payment::initialize([
        'amount'=>$amount,       // according to currency format
        'email'=>$email,         // unique to customers
        'currency'=>$currency,         // currency iso code
        'callback'=>$callback,         // optional callback url
        'reference'=>$reference, // unique to transactions
    ]);
} catch(\NotchPay\Exceptions\ApiException $e){
    print_r($e->errors);
    die($e->getMessage());
}
// redirect to page so User can pay
header('Location: ' . $tranx->authorization_url);

```

When the user enters their payment details, NotchPay will validate and charge the card. It will do all the below:

Send a payment.complete event to your Webhook URL set at: https://business.notchpay.co/settings/developer

If receipts are not turned off, an HTML receipt will be sent to the customer's email.


### 3. Verify Transaction

After we redirect to your callback, please verify the transaction before giving value.

```php
    $reference = isset($_GET['reference']) ? $_GET['reference'] : '';
    if(!$reference){
      die('No reference supplied');
    }

    // initiate the Library's NotchPay Object
    NotchPay::setApiKey('sk_1234abcd');

    try {
    $tranx = Payment::verify($reference);

    if ($tranx->transaction->status === 'complete') {
      // transaction was successful...
      // please check other things like whether you already gave value for this ref
      // if the email matches the customer who owns the product etc
      // Give value
    }
} catch(\NotchPay\Exceptions\ApiException $e){
    print_r($e->errors);
    die($e->getMessage());
}

```

## Transfers

```php
use NotchPay\DTO\Transfers\RecipientDTO;
use NotchPay\DTO\Transfers\TransfersDTO;
use NotchPay\NotchPay;

NotchPay::setApiKey('pk_test.jMwMAHmIVTXn8X6v1G8972297813974');
NotchPay::setApiPrivateKey('sk.jMwMAHmIVTXn8X6v1G897587');

try {
    // Create an instance with a Object
    $recipientData = new RecipientDTO(
        name: "Benjamin Maggio",
        channel: "cm.mobile",
        number: "+2376xxxxxx", // Number to receive found (this is fake number)
        phone: "+2376xxxxxx", // Recipient phone number (this is fake number)
        email: "hellop@notchpay.ohi",
        country: "CM",
        description: "Test description",
        reference: "3RAV4gZLesBAXTrwiuUDLnJGSAS4RVEbF5"
    );

       //Or you can Create an instance with a an Array directly
//    $recipientData = RecipientDTO::fromArray([
//            "name" => "Benjamin Maggio",
//            "channel" => "cm.mobile",
//            "number" => "+237656019255",
//            "phone" => "+237655632823",
//            "email" => "hello@notchpay.cio",
//            "country" => "MX",
//            "description" => "Test description",
//            "reference" => "3RAV4gZLesBAXTrwiuUDLnJGSAS4RVEbG5"
//        ]);

    $recipient = NotchPay::recipients()->create($recipientData);

// sample of recipient response
{
   "code": 201,
   "status": "Created",
   "message": "Beneficiary created successfully",
   "recipient": {
       "id": "rcp.xxxxxxxxx",
       "phone": "+2376xxxxxx",
       "name": "Benjamin Maggio",
       "email": "test@gmail.com",
       "sandbox": false,
       "country": "CM",
       "payment_method": {
           "channel": "cm.orange",
           "id": "pm.xxxxxxxxxxx",
           "email": null,
           "country": "CM",
           "name": null,
           "type": "Mobile Money",
           "account_number": null,
           "number": "2376xxxxxx",
           "phone": "+2376xxxxxx",
           "issuer": "Orange Money",
           "recipient": "rcp.xxxxxxxxx",
           "issuer_code": null,
           "created_at": "2024-12-23T09:39:45.000000Z"
       }
   }
}

// the list of your recipient
$recipientList = NotchPay::recipients()->list();

{
   "code": 200,
   "status": "OK",
   "message": "Beneficiaries  retrieved",
   "totals": 1,
   "last_page": 1,
   "current_page": 1,
   "selected": 1,
   "items": [
            {
                "id": "rcp.xxxxxxxx",
                "phone": "+2376xxxxxx",
                "name": "Benjamin Maggio",
                "email": null,
                "sandbox": false,
                "country": "CM",
                "payment_method": {
                    "channel": "cm.orange",
                    "id": "pm.xxxxxxxx",
                    "email": null,
                    "country": null,
                    "name": null,
                    "type": "Mobile Money",
                    "account_number": null,
                    "number": "2376xxxxxx",
                    "phone": null,
                    "issuer": "Orange Money",
                    "recipient": "rcp.xxxxxxxx",
                    "issuer_code": null,
                    "created_at": "2024-12-23T09:39:46.000000Z"
            }
        }
   ]
}



 // Initialize a transfer
    $transferDTO = new TransfersDTO(
       amount: "15",
       currency: "XAF",
       description: "Test description",
       recipientId: $recipient->recipient->id,
       channel: "cm.mobile",
       beneficiary: [
            'name' => $recipient->recipient->name,
            'number' => $recipient->recipient->phone
        ]
    );
    
    $transfer = NotchPay::transfers()->initialize($transferDTO);

// Sample of reponse of a transfers initialize
{
   "status": "Accepted",
   "message": "Transfer initialized",
   "code": 201,
   "transfer": {
       "amount": 15,
       "amount_total": 16,
       "sandbox": false,
       "fee": 1,
       "converted_amount": 15,
       "beneficiary": "rcp.7jnXcrcX2KFTN9fi",
       "description": "Test description", 
       "reference": "po.xxxxxxxxxxxx", // here is your transfer referece. you can use it to verify a transfer
       "trxref": null,
       "statement": null,
       "status": "sent",
       "currency": "XAF",
       "geo": "127.0.0.1",
       "created_at": "2024-12-23T09:39:46.000000Z"
   }
}

// verify a Transfer
$verified = NotchPay::transfers()->verify("po.xxxxxxxxxxxx");

{
   "code": 200,
   "status": "OK",
   "message": "Transfer retrieved",
   "transfer": {
       "amount": 15,
       "amount_total": 16,
       "sandbox": false,
       "fee": 1,
       "converted_amount": 15,
       "beneficiary": "rcp.xxxxxxxx",
       "description": "Test description",
       "reference": "po.xxxxxxxxxxxxx",
       "trxref": null,
       "statement": null,
       "status": "sent",
       "currency": "XAF",
       "geo": "127.0.0.1",
       "created_at": "2024-12-23T09:39:46.000000Z"
   }
}

// List transfers
$transfers = NotchPay::transfers()->list();

{
   "code": 200,
   "status": "OK",
   "message": "Transfers retrieved",
   "totals": 1,
   "last_page": 1,
   "current_page": 1,
   "selected": 1,
   "items": [
       {
           "amount": 15,
           "amount_total": 16,
           "sandbox": false,
           "fee": 1,
           "converted_amount": 15,
           "beneficiary": "rcp.xxxxxxxx",
           "description": "Test description",
           "reference": "po.xxxxxxxxxxxx",
           "trxref": null,
           "statement": null,
           "status": "sent",
           "currency": "XAF",
           "geo": "127.0.0.1",
           "created_at": "2024-12-23T09:39:46.000000Z"
       }
   ]
}
    
} catch(\NotchPay\Exceptions\ApiException $e){
    print_r($e->getMessage());
    die();
} catch (\NotchPay\Exceptions\InvalidArgumentException $e) {
    print_r($e->getMessage());
    die();
}

## Changelog

Please see [CHANGELOG](CHANGELOG.md) for more information on what has changed recently.

## Contributing

Please see [CONTRIBUTING](CONTRIBUTING.md) for details.

## Security

If you discover any security related issues, please email hello@notchpay.co instead of using the issue tracker.


## License

The MIT License (MIT). Please see [License File](LICENSE.md) for more information.
