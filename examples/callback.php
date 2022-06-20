<?php
error_reporting(0);

use Afillix\BankartGateway\Client;
use Afillix\BankartGateway\Callback\Result;


include "/var/www/html/config.php";
include "/var/www/html/func.php";
dbcon();


require_once('../initClientAutoload.php');

$client = new Client('Api23ASPartnersip', 'tB4z/*a+Uj9_Fnhx=kwgPYq#WT(5a', '9313001IC931301', 'p4ZDjAHJubhs37ssyTIEAzyqMlhvOY');

//if you want to check signature uncoment if statement
/*
if ($client->validateCallbackWithGlobals() == false){
    //the signature is incorrect. It is your decision what to do

}
*/

$callbackResult = $client->readCallback(file_get_contents('php://input'));


$myTransactionId = $callbackResult->getTransactionId();
$gatewayTransactionId = $callbackResult->getReferenceId();

$check_transaction_id = mysqli_fetch_assoc(mysqli_query($sql_link,"SELECT * FROM voucher_codes WHERE payment_id='$gatewayTransactionId'"));


if ($callbackResult->getResult() == Result::RESULT_OK) {
    //payment ok
	$callbackResult->getResult();

		if($check_transaction_id[ID] > 0) $q = mysqli_query($sql_link,"UPDATE voucher_codes SET status=1 WHERE ID='$check_transaction_id[ID]'");

    //finishCart();

} elseif ($callbackResult->getResult() == Result::RESULT_ERROR) {
    //payment failed, handle errors
    $errors = $callbackResult->getErrors();

    if($check_transaction_id[ID] > 0) $q = mysqli_query($sql_link,"UPDATE voucher_codes SET status=2 WHERE ID='$check_transaction_id[ID]'");

 //   file_put_contents("/var/www/html/Payments/examples/test.txt", var_dump($callbackResult->getErrors()), FILE_APPEND | LOCK_EX);

}

echo "OK";
die;
