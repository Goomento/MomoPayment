<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_MomoPayment
 * @link https://github.com/Goomento/MomoPayment
 */

namespace Goomento\MomoPayment\Gateway\Validator;


use Goomento\MomoPayment\Gateway\Helper\SubjectReader;
use Goomento\MomoPayment\Gateway\Request\AbstractDataBuilder;
use Goomento\MomoPayment\Gateway\Response\AbstractResponseHandler;
use Goomento\MomoPayment\Helper\Helper;
use Goomento\MomoPayment\Helper\Logger;

/**
 * Class CompleteValidator
 * @package Goomento\MomoPayment\Gateway\Validator
 */
class CompleteValidator extends AbstractValidator
{
    /**
     * @param array $validationSubject
     * @return \Magento\Payment\Gateway\Validator\ResultInterface
     */
    public function validate(array $validationSubject)
    {
        $response         = SubjectReader::readResponse($validationSubject);
        $payment          = SubjectReader::readPayment($validationSubject);
        $order            = $payment->getOrder();
        $orderId          = $order->getOrderIncrementId();
        $amount           = Helper::staticConvertCurrency(SubjectReader::readAmount($validationSubject), AbstractDataBuilder::VND_CURRENCY, $order->getCurrencyCode());
        $errorMessages    = [];
        $validationResult = $this->validateTotalAmount($response, $amount)
                            && $this->validateErrorCode($response)
                            && $this->validateSignature($response)
                            && $this->validateTransactionId($response);

        if (!$validationResult) {
            $errorMessages = [__('Something went wrong when get pay url.')];
        }

        return $this->createResult($validationResult, $errorMessages);
    }

    /**
     * @return array
     */
    protected function getSignatureArray() : array
    {
        return [
            AbstractDataBuilder::PARTNER_CODE,
            AbstractDataBuilder::ACCESS_KEY,
            AbstractDataBuilder::REQUEST_ID,
            AbstractDataBuilder::AMOUNT,
            AbstractDataBuilder::ORDER_ID,
            AbstractDataBuilder::ORDER_INFO,
            AbstractResponseHandler::ORDER_TYPE,
            AbstractDataBuilder::TRANSACTION_ID,
            AbstractResponseHandler::RESPONSE_MESSAGE,
            AbstractResponseHandler::RESPONSE_LOCAL_MESSAGE,
            AbstractResponseHandler::RESPONSE_TIME,
            AbstractResponseHandler::ERROR_CODE,
            AbstractResponseHandler::PAY_TYPE,
            AbstractDataBuilder::EXTRA_DATA,
        ];
    }
}
