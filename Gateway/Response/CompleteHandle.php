<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_MomoPayment
 * @link https://github.com/Goomento/MomoPayment
 */

namespace Goomento\MomoPayment\Gateway\Response;

use Goomento\MomoPayment\Gateway\Helper\SubjectReader;
use Goomento\MomoPayment\Gateway\Request\AbstractDataBuilder;
use Goomento\MomoPayment\Helper\Config;
use Magento\Payment\Gateway\Helper\ContextHelper;
use Magento\Payment\Model\MethodInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Model\Order;

/**
 * Class CompleteHandle
 * @package Goomento\MomoPayment\Gateway\Response
 */
class CompleteHandle extends AbstractResponseHandler
{
    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;

    public function __construct(
        OrderRepositoryInterface $orderRepository
    ) {
        $this->orderRepository = $orderRepository;
    }

    /**
     * @param array $handlingSubject
     * @param array $response
     */
    public function handle(array $handlingSubject, array $response)
    {
        $response  = SubjectReader::readResponse($handlingSubject);
        $paymentDO = SubjectReader::readPayment($handlingSubject);
        $payment = $paymentDO->getPayment();
        $order   = $payment->getOrder();
        ContextHelper::assertOrderPayment($payment);

        $payment->setTransactionId($response[AbstractDataBuilder::TRANSACTION_ID]);
        $payment->setIsTransactionClosed(false);
        $payment->setShouldCloseParentTransaction(true);

        if ($order->getState() === Order::STATE_PENDING_PAYMENT) {
            switch (Config::staticConfigGet('payment_action')) {
                case MethodInterface::ACTION_AUTHORIZE_CAPTURE:
                    $payment->capture();
                    break;
            }
        }

        $this->orderRepository->save($order);
    }
}
