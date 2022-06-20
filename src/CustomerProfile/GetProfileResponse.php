<?php

namespace Afillix\BankartGateway\CustomerProfile;

use Afillix\BankartGateway\Json\ResponseObject;

/**
 * Class GetProfileResponse
 *
 * @package Afillix\BankartGateway\CustomerProfile
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
