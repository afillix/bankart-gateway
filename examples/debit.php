<?php
use Afillix\BankartGateway\Client;
use Afillix\BankartGateway\Data\Customer;
use Afillix\BankartGateway\Schedule\ScheduleData;
use Afillix\BankartGateway\Transaction\Debit;
use Afillix\BankartGateway\Transaction\Result;

include "/var/www/html/config.php";
include "/var/www/html/func.php";
dbcon();

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



$voucher_name = $_POST['voucher_name'];

$voucher_name = explode(" ",$voucher_name,2);

$voucher_fname = $voucher_name[0];
$voucher_lname = $voucher_name[1];


$_POST['place_ID'] = (int) decrypt_custom($_POST['place_ID']);
$_POST['voucher_ID'] = (int) decrypt_custom($_POST['voucher_ID']);
$_POST['user_ID'] = (int) decrypt_custom($_POST['user_ID']);
$_POST['amount'] = (int) decrypt_custom($_POST['amount']);

$_POST['TranType'] = 'debit';
$_POST['Language'] = $_POST['lang'];
$_POST['Amount'] = $_POST['amount'];
$_POST['Currency'] = "MKD";
$_POST['numInstalment'] = "00";
$_POST['IntType'] = "Redirect";
$_POST['initialStoreTrans'] = "No";
$_POST['subSeqentTrans'] = "No";
$_POST['gatewaySchadule'] = "on";
$_POST['scheduleUnit'] = "DAY";
$_POST['schedulePeriod'] = "";
$_POST['scheduleDelay'] = "";
$_POST['refTranId'] = "";
$_POST['first_name'] = $voucher_fname;
$_POST['last_name'] = $voucher_lname;
$_POST['email'] = $_POST['voucher_email'];

foreach($_POST as $k=>$v){

	$_POST[$k] = mysqli_real_escape_string($sql_link,$v);

}

$amount = $_POST['Amount'];
$place_ID = $_POST['place_ID'];
$voucher_ID =	$_POST['voucher_ID'];
$user_ID = $_POST['user_ID'];
$gift_user_name = $_POST['voucher_gift_name'];
$gift_user_email = $_POST['voucher_gift_email'];


if($amount < 1 OR $place_ID < 1 OR $voucher_ID < 1){

	header("Location: ".$_SERVER['HTTP_REFERER']);
	die();

}

/*

cur_user_name: cur_user_name,
cur_user_email: cur_user_email,
cur_user_gift: cur_user_gift,
cur_email_gift: cur_email_gift,

*/

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
$amount = '0';
if(isset($_POST["Amount"])){
    $amount = $_POST["Amount"];
}
//$client = new Client('username', 'password', '9313001IC931301', 'p4ZDjAHJubhs37ssyTIEAzyqMlhvOY');
$client = new Client('Api23ASPartnersip', 'tB4z/*a+Uj9_Fnhx=kwgPYq#WT(5a', '9313001IC931301', 'p4ZDjAHJubhs37ssyTIEAzyqMlhvOY', $_POST['Language']);

$customer = new Customer();
$customer
    ->setFirstName($_POST["first_name"])
    ->setLastName($_POST["last_name"])
    ->setEmail($_POST["email"])
    ->setIpAddress(getRealIpAddr())
    ->setBillingAddress1('Street 1')
    ->setBillingCity('Skopje')
    ->setBillingPostcode('1000')
    ->setBillingCountry('MK');
//add further customer details if necessary

// define your transaction ID: e.g. 'myId-'.date('Y-m-d').'-'.uniqid()
$merchantTransactionId = 'D-'.date('Y-m-d').'-'.uniqid(); // must be unique

srand();
$code = rand(1000000,90000000000);


$total_uses = mysqli_fetch_assoc(mysqli_query($sql_link,"SELECT total_uses FROM vouchers WHERE ID='$voucher_ID' AND place_id='$place_ID'"));

$total_uses = $total_uses['total_uses'];

$q = mysqli_query($sql_link,"INSERT INTO voucher_codes SET lang='$_POST[lang]',gift_user_name='$gift_user_name', gift_user_email='$gift_user_email',  user_name='$_POST[first_name] $_POST[last_name]', user_email='$_POST[email]', user_ID='$user_ID' ,status=0, place_ID='$place_ID', voucher_ID='$voucher_ID', code='$code', used='0', cur_uses='0', total_uses='$total_uses', amount='$amount' ");
$res_id = mysqli_insert_id($sql_link);

$res_id_no_enc = $res_id;

$res_id = encrypt_custom($res_id);

$currency = $_POST["Currency"];
$debit = new Debit();
$debit->setTransactionId($merchantTransactionId)
    ->setAmount($amount)
    ->setCurrency($currency)
    ->setCallbackUrl('https://common.mk/Payments/examples/callback.php')
    ->setSuccessUrl('https://common.mk/Payments/success-test/')
    ->setErrorUrl('https://common.mk/Payments/fail-test/')
    ->setCancelUrl('https://common.mk/Payments/fail-test/')
    ->setDescription('Reservation No:'.$res_id_no_enc)
    ->setMerchantMetaData("Transaction:Debit;Description:R".$res_id_no_enc)
    ->setCustomer($customer);


// Add Extra data
if(isset($_POST["numInstalment"])){
    $debit->addExtraData('userField1',$_POST["numInstalment"]);  //  If you have an agreement with your acquiring banks to offer Payments in installments,
                                      //userField1 is used and becomes mandatory. In such cases send 00 or 01 when no installments are selected.
                                      //In case of an invalid value, the payment will be declined.
}
//if token acquired via payment.js
if (isset($token)){
    $debit ->setTransactionToken($token);
}
switch ($initialStoreTrans){
    case "No":
        switch($subSeqentTrans){
            case "No":  //normal debit
                $debit->setWithRegister (false)
                    ->setTransactionIndicator('SINGLE');
                break;
            case "subSeqentCoF": //subsequent CoF - normal transaction with stored card
                $debit->setReferenceTransactionId($refTranId)
                    ->setTransactionIndicator('CARDONFILE');
                break;
            case "subSeqentRec":    //subsequent Recurring - Note: If jou send schedule on initialization
                //you don’t need to do that.
                $debit->setReferenceTransactionId($refTranId)
                    ->setTransactionIndicator('RECURRING');
                break;

            case "subSeqentMIT": //subsequent MIT
                $debit->setReferenceTransactionId($refTranId)
                    ->setTransactionIndicator('CARDONFILE-MERCHANT-INITIATED');
                break;
        }
        break;

    case "initialCoF": // debit & store card for future use
        $debit->setWithRegister (true);
        $debit->setTransactionIndicator('SINGLE');
        $debit->addExtraData('3ds:authenticationIndicator','04'); //04-add card
        break;

    case "initialRec":
        if(floatval($amount)== 0){
            echo("Initial recurring is not possible with amount:".$amount);
            die;
        }
        if(strlen($refTranId) > 0){ //Recurring establish with already stored card
            $debit->setReferenceTransactionId($refTranId);
        }
        $debit->setWithRegister (true)
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
            $debit -> setSchedule($myScheduleData);
        }

        break;

    case "initialMIT": //debit with MIT establishe
        if(floatval($amount)== 0){
            echo("Initial recurring is not possible with amount:".$amount);
            die;
        }
        if(strlen($refTranId) > 0){ //MIT establish with already stored card
            $debit->setReferenceTransactionId($refTranId);
        }
        $debit->setWithRegister (true)
            ->setTransactionIndicator('INITIAL')
            ->addExtraData('3ds:authenticationIndicator','02') //02-recurring+MIT
            ->addExtraData('3ds:recurringFrequency','1'); //1 -> MIT
        break;
}

$result = $client->debit($debit);

$gatewayReferenceId = $result->getReferenceId(); //store it in your database

$q = mysqli_query($sql_link,"UPDATE voucher_codes SET payment_id='$gatewayReferenceId' WHERE ID='$res_id_no_enc'");

setcookie("voucher_ref_id","$gatewayReferenceId",time()+ 1800,"/");

if ($result->getReturnType() == Result::RETURN_TYPE_ERROR) {

    //error handling Sample
    $error = $result->getFirstError();
    $outError = array();
    $outError ["message"] = $error->getMessage();
    $outError ["code"] = $error->getCode();
    $outError ["adapterCode"] = $error->getAdapterCode();
    $outError ["adapterMessage"] = $error->getAdapterMessage();
    header("Location: https://common.mk/Payments/fail/index.php?" . http_build_query($outError));
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
    header("Location: https://common.mk/Payments/success/index.php?" . http_build_query($result->toArray()));
    die;
    //finishCart();
}
