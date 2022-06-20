<?php

namespace Afillix\BankartGateway\Transaction;

use Afillix\BankartGateway\Transaction\Base\AbstractTransaction;
use Afillix\BankartGateway\Transaction\Base\AddToCustomerProfileInterface;
use Afillix\BankartGateway\Transaction\Base\AddToCustomerProfileTrait;
use Afillix\BankartGateway\Transaction\Base\OffsiteInterface;
use Afillix\BankartGateway\Transaction\Base\OffsiteTrait;
use Afillix\BankartGateway\Transaction\Base\ScheduleInterface;
use Afillix\BankartGateway\Transaction\Base\ScheduleTrait;

/**
 * Register: Register the customer's payment data for recurring charges.
 *
 * The registered customer payment data will be available for recurring transaction without user interaction.
 *
 * @package Afillix\BankartGateway\Transaction
 */
class Register extends AbstractTransaction implements OffsiteInterface, ScheduleInterface, AddToCustomerProfileInterface {
    use OffsiteTrait;
    use ScheduleTrait;
    use AddToCustomerProfileTrait;
}
