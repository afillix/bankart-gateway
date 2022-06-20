<?php

namespace App\Payments\src\CustomerProfile\PaymentData;

/**
 * Class WalletData
 *
 * @package App\Payments\src\CustomerProfile\PaymentData
 *
 * @property string $walletReferenceId
 * @property string $walletOwner
 * @property string $walletType
 */
class WalletData extends PaymentData {

    const TYPE_PAYPAL = 'paypal';

}
