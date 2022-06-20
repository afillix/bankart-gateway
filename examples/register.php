<?php


use Afillix\BankartGateway\Client;
use Afillix\BankartGateway\Data\Customer;
use Afillix\BankartGateway\Transaction\Register;
use Afillix\BankartGateway\Transaction\Result;

require_once('../initClientAutoload.php');


$client = new Client('username', 'password', 'apiKey', 'sharedSecret', 'language');

$customer = new Customer();
$customer
    ->setFirstName('John')
    ->setLastName('Smith')
    ->setEmail('john@smith.com')
    ->setIpAddress('123.123.123.123')
    ->setBillingAddress1('Street 1')
    ->setBillingCity('City')
    ->setBillingPostcode('1000')
    ->setBillingCountry('SI');

// define your transaction ID: e.g. 'myId-'.date('Y-m-d').'-'.uniqid()
$merchantTransactionId = 'RG-Test-'.date('Y-m-d').'-'.uniqid(); // must be unique

$register = new Register();
$register->setTransactionId($merchantTransactionId)
->setCallbackUrl('https://{HOST}/PHPPaymentGateway/examples/Callback.php')
    ->setSuccessUrl('http://{HOST}/PHPPaymentGateway/examples/PaymentOK.php')
    ->setErrorUrl('http://{HOST}/PHPPaymentGateway/examples/PaymentNOK.php')
    ->setCancelUrl('http://{HOST}/PHPPaymentGateway/examples/PaymentCancel.php')
    ->setDescription('Register transaction')
    ->setMerchantMetaData("Stranka:Janez;naslov=Kr neki")
    ->setCustomer($customer);

$result = $client->register($register);
$gatewayReferenceId = $result->getReferenceId(); //store it in your database

if ($result->getReturnType() == Result::RETURN_TYPE_ERROR) {
    //error handling Sample
    $error = $result->getFirstError();
    $outError = array();
    $outError ["message"] = $error->getMessage();
    $outError ["code"] = $error->getCode();
    $outError ["adapterCode"] = $error->getAdapterCode();
    $outError ["adapterMessage"] = $error->getAdapterMessage();
    header("Location: https://{HOST}/PHPPaymentGateway/examples/PaymentNOK.php?" . http_build_query($outError));
    die;
} elseif ($result->getReturnType() == Result::RETURN_TYPE_REDIRECT) {
    //redirect the user
    header('Location: '.$result->getRedirectUrl());
    die;
} elseif ($result->getReturnType() == Result::RETURN_TYPE_PENDING) {
    //payment is pending, wait for callback to complete

    //setCartToPending();

} elseif ($result->getReturnType() == Result::RETURN_TYPE_FINISHED) {
    //payment is finished, update your cart/payment transaction

    header("Location: https://{HOST}/PHPPaymentGateway/examples/PaymentOK.php?" . http_build_query($result->toArray()));
    die;
    //finishCart();
}
