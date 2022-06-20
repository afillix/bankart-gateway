<?php

namespace Afillix\BankartGateway\CustomerProfile\PaymentData;

/**
 * Class WalletData
 *
 * @package Afillix\BankartGateway\CustomerProfile\PaymentData
 *
 * @property string $walletReferenceId
 * @property string $walletOwner
 * @property string $walletType
 */
class WalletData extends PaymentData {

    const TYPE_PAYPAL = 'paypal';

}
