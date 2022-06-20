<?php

namespace App\Payments\src\CustomerProfile;

use App\Payments\src\Json\ResponseObject;

/**
 * Class UpdateProfileResponse
 *
 * @package App\Payments\src\CustomerProfile
 *
 * @property string $profileGuid
 * @property string $customerIdentification
 * @property CustomerData $customer
 * @property array $changedFields
 */
class UpdateProfileResponse extends ResponseObject {

}
