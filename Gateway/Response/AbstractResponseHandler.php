<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_MomoPayment
 * @link https://github.com/Goomento/MomoPayment
 */

namespace Goomento\MomoPayment\Gateway\Response;


use Magento\Payment\Gateway\Response\HandlerInterface;

/**
 * Class AbstractResponseHandler
 * @package Goomento\MomoPayment\Gateway\Response
 */
abstract class AbstractResponseHandler implements HandlerInterface
{
    const PAY_URL = 'payUrl';
    public const PAY_TYPE = 'payType';
    public const RESPONSE_MESSAGE = 'message';
    public const RESPONSE_TIME = 'responseTime';
    public const ORDER_TYPE = 'orderType';
    public const RESPONSE_LOCAL_MESSAGE = 'localMessage';
    public const ERROR_CODE = 'errorCode';
}
