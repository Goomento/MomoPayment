<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_MomoPayment
 * @link https://github.com/Goomento/MomoPayment
 */

namespace Goomento\MomoPayment\Gateway\Request;

use Goomento\MomoPayment\Helper\Config;

/**
 * Class CredentialDataBuilder
 * @package Goomento\MomoPayment\Gateway\Request
 */
class CredentialDataBuilder extends AbstractDataBuilder
{
    public function build(array $buildSubject)
    {
        return array_merge($buildSubject, [
            AbstractDataBuilder::PARTNER_CODE => Config::staticConfigGet('merchant_code'),
            AbstractDataBuilder::ACCESS_KEY => Config::staticConfigGet('merchant_access_key'),
            AbstractDataBuilder::REQUEST_ID => (string)time(),
        ]);
    }
}
