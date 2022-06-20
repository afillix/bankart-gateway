<?php

namespace Afillix\BankartGateway\Transaction;

use Afillix\BankartGateway\Transaction\Base\AbstractTransactionWithReference;
use Afillix\BankartGateway\Transaction\Base\AmountableInterface;
use Afillix\BankartGateway\Transaction\Base\AmountableTrait;
use Afillix\BankartGateway\Transaction\Base\ItemsInterface;
use Afillix\BankartGateway\Transaction\Base\ItemsTrait;

/**
 * Capture: Charge a previously preauthorized transaction.
 *
 * @package Afillix\BankartGateway\Transaction
 */
class Capture extends AbstractTransactionWithReference implements AmountableInterface, ItemsInterface {
    use AmountableTrait;
    use ItemsTrait;
}
