<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_MomoPayment
 * @link https://github.com/Goomento/MomoPayment
 */

namespace Goomento\MomoPayment\Gateway\Request;

use Goomento\MomoPayment\Gateway\Helper\SubjectReader;
use Magento\Framework\Exception\LocalizedException;
use Magento\Payment\Gateway\Data\OrderAdapterInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectInterface;
use Goomento\MomoPayment\Helper\Helper;

/**
 * Class GetPayUrlDataBuilder
 * @package Goomento\MomoPayment\Gateway\Request
 */
class GetPayUrlDataBuilder extends AbstractDataBuilder
{
    /**
     * @param array $buildSubject
     * @return array
     */
    protected function buildOrderData(array $buildSubject) : array
    {
        /** @var PaymentDataObjectInterface $payment */
        $payment = SubjectReader::readPayment($buildSubject);
        /** @var OrderAdapterInterface $order */
        $order = $payment->getOrder();

        return [
            self::ORDER_ID => (string)$order->getOrderIncrementId(),
            self::ORDER_INFO => $this->getOrderInfo($order),
            self::AMOUNT_MARK => (string) Helper::staticConvertCurrency(SubjectReader::readAmount($buildSubject), $order->getCurrencyCode(), self::VND_CURRENCY),
            self::EXTRA_DATA => "customer=" . ($order->getCustomerId() ?? 'guest'),
        ];
    }

    /**
     * @return string
     */
    protected function getOrderInfo(OrderAdapterInterface $order)
    {
        try {
            $shippingAddress = $order->getShippingAddress();
            return implode(" ", [
                $shippingAddress->getFirstname(),
                $shippingAddress->getLastname(),
                $shippingAddress->getEmail(),
            ]);
        } catch (\Exception $e) {
            return __('Customer');
        }
    }


    /**
     * @param array $buildSubject
     * @return array
     * @throws LocalizedException
     */
    public function build(array $buildSubject)
    {
        return array_merge([
            self::REQUEST_TYPE => $this->requestType,
            self::AMOUNT => SubjectReader::readAmount($buildSubject),
        ], $this->buildOrderData($buildSubject));
    }
}
