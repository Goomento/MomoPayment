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
use Magento\Payment\Gateway\Validator\ResultInterface;

/**
 * Class RefundValidator
 * @package Goomento\MomoPayment\Gateway\Validator
 */
class RefundValidator extends AbstractValidator
{
    /**
     * @param array $validationSubject
     * @return ResultInterface
     */
    public function validate(array $validationSubject)
    {
        $response         = SubjectReader::readResponse($validationSubject);
        $payment          = SubjectReader::readPayment($validationSubject);
        $order          = $payment->getOrder();
        $amount           = Helper::staticConvertCurrency(SubjectReader::readAmount($validationSubject), AbstractDataBuilder::VND_CURRENCY, $order->getCurrencyCode());
        $errorMessages    = [];
        $validationResult = $this->validateTotalAmount($response, $amount)
            && $this->validateErrorCode($response)
            && $this->validateSignature($response)
            && $this->validateTransactionId($response);

        if (!$validationResult) {
            $errorMessages [] = $response[AbstractResponseHandler::RESPONSE_MESSAGE];
            $errorMessages = [__('Something went wrong when get pay url.')];
        }

        return $this->createResult($validationResult, $errorMessages);
    }

    /**
     * @return array
     */
    protected function getSignatureArray(): array
    {
        return [
            AbstractDataBuilder::PARTNER_CODE,
            AbstractDataBuilder::ACCESS_KEY,
            AbstractDataBuilder::REQUEST_ID,
            AbstractDataBuilder::ORDER_ID,
            AbstractResponseHandler::ERROR_CODE,
            AbstractDataBuilder::TRANSACTION_ID,
            AbstractResponseHandler::RESPONSE_MESSAGE,
            AbstractResponseHandler::RESPONSE_LOCAL_MESSAGE,
            AbstractDataBuilder::REQUEST_TYPE,
        ];
    }
}
