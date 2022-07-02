<?php

namespace Modules\MercadoPago\Services;

use Modules\Payment\Models\PaymentConfiguration;

class MercadoPagoService
{

    public function getPublicKey()
    {
        return PaymentConfiguration::getPublicKeyMp();
    }

}