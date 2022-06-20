<?php

namespace Afillix\BankartGateway\Transaction;

use Afillix\BankartGateway\Transaction\Base\AbstractTransactionWithReference;
use Afillix\BankartGateway\Transaction\Base\AmountableInterface;
use Afillix\BankartGateway\Transaction\Base\AmountableTrait;
use Afillix\BankartGateway\Transaction\Base\ItemsInterface;
use Afillix\BankartGateway\Transaction\Base\ItemsTrait;

/**
 * Payout: Payout a certain amount of money to the customer. (Debits the merchant's account, Credits the customer's account)
 *
 * @package Afillix\BankartGateway\Transaction
 */
class Payout extends AbstractTransactionWithReference implements AmountableInterface, ItemsInterface {
    use ItemsTrait;
    use AmountableTrait;

    /**
     * @var string
     */
    protected $description;

    /**
     * @var string
     */
    protected $callbackUrl;

    /**
     * @return string
     */
    public function getDescription() {
        return $this->description;
    }

    /**
     * @param string $description
     */
    public function setDescription($description) {
        $this->description = $description;
    }

    /**
     * @return string
     */
    public function getCallbackUrl() {
        return $this->callbackUrl;
    }

    /**
     * @param string $callbackUrl
     */
    public function setCallbackUrl($callbackUrl) {
        $this->callbackUrl = $callbackUrl;
    }

}
