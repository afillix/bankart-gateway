<?php

namespace App\Payments\src\Transaction;

use App\Payments\src\Transaction\Base\AbstractTransactionWithReference;
use App\Payments\src\Transaction\Base\AmountableInterface;
use App\Payments\src\Transaction\Base\AmountableTrait;
use App\Payments\src\Transaction\Base\ItemsInterface;
use App\Payments\src\Transaction\Base\ItemsTrait;

/**
 * Capture: Charge a previously preauthorized transaction.
 *
 * @package App\Payments\src\Transaction
 */
class Capture extends AbstractTransactionWithReference implements AmountableInterface, ItemsInterface {
    use AmountableTrait;
    use ItemsTrait;
}
