<?php

namespace Afillix\BankartGateway\CustomerProfile;

use Afillix\BankartGateway\Json\ResponseObject;

/**
 * Class UpdateProfileResponse
 *
 * @package Afillix\BankartGateway\CustomerProfile
 *
 * @property string $profileGuid
 * @property string $customerIdentification
 * @property CustomerData $customer
 * @property array $changedFields
 */
class UpdateProfileResponse extends ResponseObject {

}
