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

/**
 * Class GetPayUrlValidator
 * @package Goomento\MomoPayment\Gateway\Validator
 */
class GetPayUrlValidator extends AbstractValidator
{

    public function validate(array $validationSubject)
    {
        $response         = SubjectReader::readResponse($validationSubject);
        $payment          = SubjectReader::readPayment($validationSubject);
        $orderId          = $payment->getOrder()->getOrderIncrementId();
        $errorMessages    = [];

        $validationResult = $this->validateErrorCode($response)
            && $this->validateOrderId($response, $orderId)
            && $this->validateSignature($response);

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
            AbstractDataBuilder::REQUEST_ID,
            AbstractDataBuilder::ORDER_ID,
            AbstractResponseHandler::RESPONSE_MESSAGE,
            AbstractResponseHandler::RESPONSE_LOCAL_MESSAGE,
            AbstractResponseHandler::PAY_URL,
            AbstractResponseHandler::ERROR_CODE,
            AbstractDataBuilder::REQUEST_TYPE,
        ];
    }
}
