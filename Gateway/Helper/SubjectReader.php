<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_MomoPayment
 * @link https://github.com/Goomento/MomoPayment
 */

namespace Goomento\MomoPayment\Gateway\Helper;


use Goomento\MomoPayment\Gateway\Response\AbstractResponseHandler;
use Goomento\MomoPayment\Gateway\Request\AbstractDataBuilder;

/**
 * Class SubjectReader
 * @package Goomento\MomoPayment\Gateway\Helper
 */
class SubjectReader extends \Magento\Payment\Gateway\Helper\SubjectReader
{
    /**
     * Read Pay Url from transaction data
     *
     * @param array $transactionData
     * @return string
     */
    public static function readPayUrl(array $transactionData)
    {
        if (empty($transactionData[AbstractResponseHandler::PAY_URL])) {
            throw new \InvalidArgumentException('Pay Url should be provided');
        }

        return $transactionData[AbstractResponseHandler::PAY_URL];
    }

    /**
     * @param array $transactionData
     * @return mixed
     */
    public static function readOrderId(array $transactionData)
    {
        if (empty($transactionData[AbstractDataBuilder::ORDER_ID])) {
            throw new \InvalidArgumentException('Order Id not provided.');
        }

        return $transactionData[AbstractDataBuilder::ORDER_ID];
    }
}
