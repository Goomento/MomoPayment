<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_MomoPayment
 * @link https://github.com/Goomento/MomoPayment
 */

namespace Goomento\MomoPayment\Model\Config\Source;

/**
 * Class Environment
 * @package Goomento\MomoPayment\Model\Config\Source
 */
class Environment implements \Magento\Framework\Data\OptionSourceInterface
{

    public function toOptionArray()
    {
        return [
            [
                'value' => 'sandbox',
                'label' => __('Sandbox')
            ],
            [
                'value' => 'production',
                'label' => __('Production')
            ],
        ];
    }
}
