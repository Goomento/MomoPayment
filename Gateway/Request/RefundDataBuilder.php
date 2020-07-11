<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_MomoPayment
 * @link https://github.com/Goomento/MomoPayment
 */

namespace Goomento\MomoPayment\Gateway\Request;


use Goomento\MomoPayment\Gateway\Helper\SubjectReader;
use Goomento\MomoPayment\Helper\Helper;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;

/**
 * Class RefundDataBuilder
 * @package Goomento\MomoPayment\Gateway\Request
 */
class RefundDataBuilder extends AbstractDataBuilder
{

    public function build(array $buildSubject)
    {
        /** @var PaymentDataObjectInterface $payment */
        $paymentDO = SubjectReader::readPayment($buildSubject);
        $payment = $paymentDO->getPayment();
        $order = $payment->getOrder();

        return [
            self::AMOUNT_MARK => (string) Helper::staticConvertCurrency(SubjectReader::readAmount($buildSubject), $order->getOrderCurrencyCode(), self::VND_CURRENCY),
            self::ORDER_ID => "refund-" . $order->getIncrementId(),
            self::TRANSACTION_ID => $payment->getParentTransactionId(),
            self::REQUEST_TYPE => $this->requestType,
        ];
    }
}
