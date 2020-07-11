<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_MomoPayment
 * @link https://github.com/Goomento/MomoPayment
 */

namespace Goomento\MomoPayment\Ui;

use Goomento\MomoPayment\Controller\Payment\Start;
use Goomento\MomoPayment\Helper\Config;

/**
 * Class ConfigProvider
 * @package Goomento\MomoPayment\Ui
 */
class ConfigProvider implements \Magento\Checkout\Model\ConfigProviderInterface
{
    const MOMO_LOGO_SRC = 'https://developers.momo.vn/images/logo.png';

    /**
     * @return array|\array[][]
     */
    public function getConfig()
    {
        $config = [];
        $config['logo_src'] = self::MOMO_LOGO_SRC;
        $config['title'] = Config::staticConfigGet('title');
        $config['code'] = Config::CODE;
        $config['start_url'] = Start::getSlug(Config::CODE);

        return ['payment' => [Config::CODE => $config]];
    }
}
