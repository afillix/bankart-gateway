<?

use App\Payments\src\Client;
use App\Payments\src\Callback\Result;

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

//	echo "ERROR!";
//}


?>
