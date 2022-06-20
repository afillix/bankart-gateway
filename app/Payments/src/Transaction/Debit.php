<?php

namespace App\Payments\src\Transaction;

use App\Payments\src\Transaction\Base\AbstractTransactionWithReference;
use App\Payments\src\Transaction\Base\AddToCustomerProfileInterface;
use App\Payments\src\Transaction\Base\AddToCustomerProfileTrait;
use App\Payments\src\Transaction\Base\AmountableInterface;
use App\Payments\src\Transaction\Base\AmountableTrait;
use App\Payments\src\Transaction\Base\ItemsInterface;
use App\Payments\src\Transaction\Base\ItemsTrait;
use App\Payments\src\Transaction\Base\OffsiteInterface;
use App\Payments\src\Transaction\Base\OffsiteTrait;
use App\Payments\src\Transaction\Base\ScheduleInterface;
use App\Payments\src\Transaction\Base\ScheduleTrait;

/**
 * Debit: Charge the customer for a certain amount of money. This could be once, but also recurring.
 *
 * @package App\Payments\src\Transaction
 */
class Debit extends AbstractTransactionWithReference implements AmountableInterface, OffsiteInterface, ItemsInterface, ScheduleInterface, AddToCustomerProfileInterface {
    use OffsiteTrait;
    use AmountableTrait;
    use ItemsTrait;
    use ScheduleTrait;
    use AddToCustomerProfileTrait;

    const TRANSACTION_INDICATOR_SINGLE = 'SINGLE';
    const TRANSACTION_INDICATOR_INITIAL = 'INITIAL';
    const TRANSACTION_INDICATOR_RECURRING = 'RECURRING';
    const TRANSACTION_INDICATOR_CARDONFILE = 'CARDONFILE';
    const TRANSACTION_INDICATOR_CARDONFILE_MERCHANT = 'CARDONFILE_MERCHANT';

    /**
     * @var bool
     */
    protected $withRegister = false;

    /**
     * @var string
     */
    protected $transactionIndicator;

    /**
     * @return boolean
     */
    public function isWithRegister() {
        return $this->withRegister;
    }

    /**
     * set true if you want to register a user vault together with the debit
     *
     * @param boolean $withRegister
     *
     * @return $this
     */
    public function setWithRegister($withRegister) {
        $this->withRegister = $withRegister;
        return $this;
    }

    /**
     * @return string
     */
    public function getTransactionIndicator() {
        return $this->transactionIndicator;
    }

    /**
     * @param string $transactionIndicator
     */
    public function setTransactionIndicator($transactionIndicator) {
        $this->transactionIndicator = $transactionIndicator;
        return $this;
    }

}
