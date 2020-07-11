<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_MomoPayment
 * @link https://github.com/Goomento/MomoPayment
 */

namespace Goomento\MomoPayment\Gateway\Validator;

use Goomento\MomoPayment\Gateway\Request\AbstractDataBuilder;
use Goomento\MomoPayment\Gateway\Response\AbstractResponseHandler;
use Goomento\MomoPayment\Helper\Logger;
use Magento\Payment\Gateway\Validator\ResultInterfaceFactory;

/**
 * Class AbstractValidator
 * @package Goomento\MomoPayment\Gateway\Validator
 */
abstract class AbstractValidator extends \Magento\Payment\Gateway\Validator\AbstractValidator
{
    /**
     * AbstractValidator constructor.
     * @param ResultInterfaceFactory $resultFactory
     */
    public function __construct(ResultInterfaceFactory $resultFactory)
    {
        parent::__construct($resultFactory);
    }

    /**
     * @param array $response
     * @return boolean
     */
    protected function validateErrorCode(array $response)
    {
        if (isset($response[AbstractResponseHandler::ERROR_CODE])
            && ((string)$response[AbstractResponseHandler::ERROR_CODE] === '0')) {
            return true;
        }
        Logger::staticCritical($response[AbstractResponseHandler::RESPONSE_MESSAGE]);
        return false;
    }

    /**
     * @param array $response
     * @return boolean
     */
    protected function validateTransactionId(array $response)
    {
        if (isset($response[AbstractDataBuilder::TRANSACTION_ID])
            && $response[AbstractDataBuilder::TRANSACTION_ID]) {
            return true;
        }
        Logger::staticCritical(__('Invalid transaction ID'));
        return false;
    }

    /**
     * @param array $response
     * @param $orderId
     * @return bool
     */
    protected function validateOrderId(array $response, $orderId)
    {
        if (isset($response[AbstractDataBuilder::ORDER_ID])
            && (string) $response[AbstractDataBuilder::ORDER_ID] === (string) $orderId) {
            return true;
        }
        Logger::staticCritical(__('Invalid order ID'));
        return false;
    }

    /**
     * Validate Signature
     *
     * @param array $response
     * @return boolean
     */
    protected function validateSignature(array $response)
    {
        $newParams = [];
        foreach ($this->getSignatureArray() as $param) {
            if (isset($response[$param])) {
                $newParams[$param] = $response[$param];
            }
        }

        $signature = \Goomento\MomoPayment\Helper\Helper::hash($newParams);
        if (!empty($response[AbstractDataBuilder::SIGNATURE])
            && $response[AbstractDataBuilder::SIGNATURE] === $signature) {
            return  true;
        }

        Logger::staticCritical(__('Invalid signature'));

        return false;
    }

    /**
     * Validate total amount.
     *
     * @param array               $response
     * @param array|number|string $amount
     * @return boolean
     */
    protected function validateTotalAmount(array $response, $amount)
    {
        if (isset($response[AbstractDataBuilder::AMOUNT])
            && (string)($response[AbstractDataBuilder::AMOUNT]) === (string)$amount) {
            return true;
        }
        Logger::staticCritical(__('Invalid Total Amount'));
        return false;
    }

    /**
     * @inheritDoc
     */
    abstract protected function getSignatureArray() : array;
}
