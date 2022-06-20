<?php

use Afillix\BankartGateway\Client;
use Afillix\BankartGateway\Data\Customer;
use Afillix\BankartGateway\Transaction\Preauthorize;
use Afillix\BankartGateway\Transaction\Result;

require_once('../initClientAutoload.php');
//get customers IP
function getRealIpAddr()
{
    if (!empty($_SERVER['HTTP_CLIENT_IP']))   //check ip from share internet
    {
      $ip=$_SERVER['HTTP_CLIENT_IP'];
    }
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))   //to check ip is pass from proxy
    {
      $ip=$_SERVER['HTTP_X_FORWARDED_FOR'];
    }
    else
    {
      $ip=$_SERVER['REMOTE_ADDR'];
    }
    return $ip;
}


//PajmentJS indicator
$token = null;
if(isset($_POST["transaction_token"])){
    $token = $_POST["transaction_token"];
}
// Use gateway schadule or merchant send a sub sequent transaction in period.
//true-use gateway schadule
//false-merchant create own schadule
$gatewaySchadule = 'off';
if(isset($_POST["gatewaySchadule"])){
    $gatewaySchadule = $_POST["gatewaySchadule"]=== 'on'? 'on': 'off';
}
//Initial transaction with stored card(prewious or now)
$initialStoreTrans = 'No';
if(isset($_POST["initialStoreTrans"])){
    $initialStoreTrans = $_POST["initialStoreTrans"];
}
//Sub-sequent transaction with stored card
$subSeqentTrans = 'No';
if(isset($_POST["subSeqentTrans"])){
    $subSeqentTrans = $_POST["subSeqentTrans"];
}
// Schadule in case of Recurring transaction
$scheduleUnit = 'DAY';
if(isset($_POST["scheduleUnit"])){
    $scheduleUnit = $_POST["scheduleUnit"];
}
$schedulePeriod = '1';
if(isset($_POST["schedulePeriod"])){
    $schedulePeriod = $_POST["schedulePeriod"];
}
$scheduleDelay = '';
if(isset($_POST["scheduleDelay"])){
    $scheduleDelay = $_POST["scheduleDelay"];
}
$refTranId='';
if(isset($_POST["refTranId"])){
    $refTranId = $_POST["refTranId"];
}

$client = new Client('username', 'password', 'apiKey', 'sharedSecret', 'language');


$customer = new Customer();
$customer
    ->setFirstName($_POST["first_name"])
    ->setLastName($_POST["last_name"])
    ->setEmail($_POST["email"])
    ->setIpAddress(getRealIpAddr())
    ->setBillingAddress1('Street 1')
    ->setBillingCity('City')
    ->setBillingPostcode('1000')
    ->setBillingCountry('SI');

//add further customer details if necessary

// define your transaction ID: e.g. 'myId-'.date('Y-m-d').'-'.uniqid()
$merchantTransactionId = 'P-Test-'.date('Y-m-d').'-'.uniqid(); // must be unique

$amount = $_POST["Amount"];
$currency = $_POST["Currency"];
$preauth = new Preauthorize();
$preauth->setTransactionId($merchantTransactionId)
    ->setAmount($amount)
    ->setCurrency($currency)
    ->setDescription('Transaction Description')
    ->setCallbackUrl('https://{HOST}/PHPPaymentGateway/examples/Callback.php')
    ->setSuccessUrl('http://{HOST}/PHPPaymentGateway/examples/PaymentOK.php')
    ->setErrorUrl('http://{HOST}/PHPPaymentGateway/examples/PaymentNOK.php')
    ->setCancelUrl('http://{HOST}/PHPPaymentGateway/examples/PaymentCancel.php')
    ->setDescription('One pair of shoes')
    ->setMerchantMetaData("Transaction:Preauthorize;Description:test")
    ->setCustomer($customer);
// Add Extra data
if(isset($_POST["numInstalment"])){
    $preauth->addExtraData('userField1',$_POST["numInstalment"]);  //  If you have an agreement with your acquiring banks to offer Payments in installments,
                                      //userField1 is used and becomes mandatory. In such cases send 00 or 01 when no installments are selected.
                                      //In case of an invalid value, the payment will be declined.
}

//if token acquired via payment.js
if (isset($token)) {
    $preauth->setTransactionToken($token);
}

switch ($initialStoreTrans){
    case "No":
        switch($subSeqentTrans){
            case "No":  //normal Preauthorize
                $preauth->setWithRegister (false)
                    ->setTransactionIndicator('SINGLE');
                break;
            case "subSeqentCoF": //subsequent CoF - normal transaction with stored card
                $preauth->setReferenceTransactionId($refTranId)
                    ->setTransactionIndicator('CARDONFILE');
                break;

            case "subSeqentRec":    //subsequent Recurring - Note: If jou send schedule on initialization
                //you don’t need to do that.
                echo("Sub-sequent Recurring with 'Preauthorize' is not possible! Instead Preauthorize us Sub-sequent 'Debit'");
                die;
                break;

            case "subSeqentMIT": //subsequent MIT
                echo("Sub-sequent MIT with 'Preauthorize' is not possible! Instead Preauthorize us Sub-sequent 'Debit'");
                die;
                break;
        }
        break;

    case "initialCoF": // Preauthorize & store card for future use
        $preauth->setWithRegister (true);
        $preauth->setTransactionIndicator('SINGLE');
        $preauth->addExtraData('3ds:authenticationIndicator','04'); //04-add card
        break;

    case "initialRec":
        if(strlen($refTranId) > 0){ //Recurring establish with already stored card
            $preauth->setReferenceTransactionId($refTranId);
        }
        $preauth->setWithRegister (true)
            ->setTransactionIndicator('INITIAL')
            ->addExtraData('3ds:authenticationIndicator','02') //02-recurring+MIT
            ->addExtraData('3ds:recurringFrequency','2'); //!1->Recurring; no connections with $schedulePeriod
        //if gateway do a sub-sequent transactions
        if($gatewaySchadule == 'on'){
            //create schedular
            $myScheduleData = new ScheduleData();
            $myScheduleData -> setPeriodUnit($scheduleUnit) // The units are 'DAY','WEEK','MONTH','YEAR'
                -> setPeriodLength($schedulePeriod)
                -> setAmount($amount)
                -> setCurrency($currency);

            //Delay for first sub sequent transaction
            if (strlen($scheduleDelay) > 0){ //if dellay for first sub-sequent transaction is set
                $date =new DateTime("now", new DateTimeZone('Europe/Ljubljana'));
                $date ->modify($_POST["scheduleDelay"]);
                $myScheduleData -> setStartDateTime($date);
            }

            //add Schedular to debit transaction
            $preauth -> setSchedule($myScheduleData);
        }

        break;

    case "initialMIT": //Preauthorize with MIT establishe
        if(strlen($refTranId) > 0){ //MIT establish with already stored card
            $preauth->setReferenceTransactionId($refTranId);
        }
        $preauth->setWithRegister (true)
            ->setTransactionIndicator('INITIAL')
            ->addExtraData('3ds:authenticationIndicator','02') //02-recurring+MIT
            ->addExtraData('3ds:recurringFrequency','1'); //1 -> MIT
        break;

}


$result = $client->preauthorize($preauth);

$gatewayReferenceId = $result->getReferenceId(); //store it in your database

if ($result->getReturnType() == Result::RETURN_TYPE_ERROR) {
    //error handling Sample
    $error = $result->getFirstError();
    $outError = array();
    $outError ["message"] = $error->getMessage();
    $outError ["code"] = $error->getCode();
    $outError ["adapterCode"] = $error->getAdapterCode();
    $outError ["adapterMessage"] = $error->getAdapterMessage();
    header("Location: http://{HOST}/PHPPaymentGateway/examples/PaymentNOK.php?" . http_build_query($outError));
    die;
} elseif ($result->getReturnType() == Result::RETURN_TYPE_REDIRECT) {
    //redirect the user
    header('Location: '.$result->getRedirectUrl());
    die;
} elseif ($result->getReturnType() == Result::RETURN_TYPE_PENDING) {
    //payment is pending, wait for callback to complete
    // not for credit card transactions
    //setCartToPending();

} elseif ($result->getReturnType() == Result::RETURN_TYPE_FINISHED) {
    //payment is finished, update your cart/payment transaction

    header("Location: http://{HOST}/PHPPaymentGateway/examples/PaymentOK.php?" . http_build_query($result->toArray()));
    die;
    //finishCart();
}
