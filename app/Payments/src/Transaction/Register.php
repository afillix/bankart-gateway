<?php

namespace App\Payments\src\Transaction;

use App\Payments\src\Transaction\Base\AbstractTransaction;
use App\Payments\src\Transaction\Base\AddToCustomerProfileInterface;
use App\Payments\src\Transaction\Base\AddToCustomerProfileTrait;
use App\Payments\src\Transaction\Base\OffsiteInterface;
use App\Payments\src\Transaction\Base\OffsiteTrait;
use App\Payments\src\Transaction\Base\ScheduleInterface;
use App\Payments\src\Transaction\Base\ScheduleTrait;

/**
 * Register: Register the customer's payment data for recurring charges.
 *
 * The registered customer payment data will be available for recurring transaction without user interaction.
 *
 * @package App\Payments\src\Transaction
 */
class Register extends AbstractTransaction implements OffsiteInterface, ScheduleInterface, AddToCustomerProfileInterface {
    use OffsiteTrait;
    use ScheduleTrait;
    use AddToCustomerProfileTrait;
}
