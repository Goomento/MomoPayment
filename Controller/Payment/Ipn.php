<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_MomoPayment
 * @link https://github.com/Goomento/MomoPayment
 */

namespace Goomento\MomoPayment\Controller\Payment;

use Goomento\MomoPayment\Gateway\Helper\SubjectReader;
use Magento\Framework\App\Action\Context;
use Magento\Payment\Gateway\Command\CommandPoolInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectFactory;
use Magento\Payment\Model\MethodInterface;
use Magento\Sales\Api\Data\OrderInterfaceFactory;
use Magento\Sales\Model\Order;
use Psr\Log\LoggerInterface;

/**
 * Class Ipn
 * @package Goomento\MomoPayment\Controller\Payment
 */
class Ipn extends AbstractPaymentController
{
    /**
     * @var MethodInterface
     */
    protected $method;

    /**
     * Ipn constructor.
     * @param Context $context
     * @param LoggerInterface $logger
     * @param MethodInterface $method
     * @param PaymentDataObjectFactory $paymentDataObjectFactory
     * @param OrderInterfaceFactory $orderFactory
     * @param CommandPoolInterface $commandPool
     */
    public function __construct(
        Context $context,
        LoggerInterface $logger,
        MethodInterface $method,
        PaymentDataObjectFactory $paymentDataObjectFactory,
        OrderInterfaceFactory $orderFactory,
        CommandPoolInterface $commandPool
    ) {
        parent::__construct($context, $logger, $paymentDataObjectFactory, $orderFactory, $commandPool);
        $this->method = $method;
    }

    /**
     * @return \Magento\Framework\App\ResponseInterface|\Magento\Framework\Controller\Result\Json|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if (!$this->isPost()) {
            return $this->responseError();
        }
        $data       = [
            'errors' => true,
            'messages' => __('Something went wrong white execute.')
        ];

        try {
            $response         = $this->getRequest()->getParams();
            $orderIncrementId = SubjectReader::readOrderId($response);
            /** @var Order $order */
            $order            = $this->orderFactory->create()->loadByIncrementId($orderIncrementId);
            $payment          = $order->getPayment();

            if ($payment->getMethod() === $this->method->getCode()) {
                $paymentDataObject = $this->paymentDataObjectFactory->create($payment);
                $this->commandPool->get('complete')->execute(
                    [
                        'payment' => $paymentDataObject,
                        'response' => $response,
                        'amount' => $order->getTotalDue()
                    ]
                );
            }
            $data = [
                'errors' => false,
                'messages' => __('Success')
            ];
        } catch (\Exception $e) {
            $this->logger->critical($e->getMessage());
            $this->responseError();
        }

        $this->responseOk($data);
    }
}
