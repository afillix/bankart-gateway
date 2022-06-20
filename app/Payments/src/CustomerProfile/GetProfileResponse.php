<?php

namespace App\Payments\src\CustomerProfile;

use App\Payments\src\Json\ResponseObject;

/**
 * Class GetProfileResponse
 *
 * @package App\Payments\src\CustomerProfile
 *
 * @property bool $profileExists
 * @property string $profileGuid
 * @property string $customerIdentification
 * @property string $preferredMethod
 * @property CustomerData $customer
 * @property PaymentInstrument[] $paymentInstruments
 */
class GetProfileResponse extends ResponseObject {

}
