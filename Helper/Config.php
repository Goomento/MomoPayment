<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_MomoPayment
 * @link https://github.com/Goomento/MomoPayment
 */

namespace Goomento\MomoPayment\Helper;

/**
 * Class Config
 * @package Goomento\MomoPayment\Helper
 * @method static staticIsSandbox() : bool
 * @method static staticPaymentUrl() : string
 */
class Config extends \Goomento\Base\Helper\PaymentGatewayConfig
{
    const CODE = 'momo_payment';

    /**
     * @return bool
     */
    public function isSandbox()
    {
        return $this->configGet('environment')==='sandbox';
    }

    /**
     * @return string
     */
    public function paymentUrl()
    {
        $url = $this->isSandbox() ? $this->configGet('sandbox_endpoint') : $this->configGet('production_endpoint');
        $url = rtrim($url, '\\/') . '/gw_payment/transactionProcessor';
        return $url;
    }
}
