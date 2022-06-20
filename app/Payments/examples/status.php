<?php

use App\Payments\src\Client;
use App\Payments\src\StatusApi\StatusRequestData;

require_once('../initClientAutoload.php');

$client = new Client('username', 'password', 'apiKey', 'sharedSecret');


$statusRequestData = new StatusRequestData();

$transactionUuid = $_POST["refTranId"]; // the gatewayReferenceId you get by Result->getReferenceId();

// use either the UUID or your merchantTransactionId but not both
$statusRequestData->setTransactionUuid($transactionUuid);

//or
//$merchantTransactionId = 'your_transaction_id';
//$statusRequestData->setMerchantTransactionId($merchantTransactionId);

  $statusResult = $client->sendStatusRequest($statusRequestData);

  var_dump($statusResult);

die();
