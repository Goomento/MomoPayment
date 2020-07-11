<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_MomoPayment
 * @link https://github.com/Goomento/MomoPayment
 */

namespace Goomento\MomoPayment\Controller\Payment;

use Magento\Checkout\Model\Session;
use Magento\Framework\App\Action\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\Session\SessionManager;
use Magento\Payment\Gateway\Command\CommandPoolInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectFactory;
use Magento\Quote\Api\CartManagementInterface;
use Magento\Quote\Api\CartRepositoryInterface;
use Magento\Sales\Api\OrderRepositoryInterface;
use Magento\Sales\Api\PaymentFailuresInterface;
use Psr\Log\LoggerInterface;
use Magento\Sales\Api\Data\OrderInterfaceFactory;

/**
 * Class Start
 * @package Goomento\MomoPayment\Controller\Payment
 */
class Start extends AbstractPaymentController
{
    /**
     * @var CartManagementInterface
     */
    protected $cartManagement;
    /**
     * @var SessionManager
     */
    protected $sessionManager;
    /**
     * @var CartRepositoryInterface
     */
    protected $quoteRepository;
    /**
     * @var Session
     */
    protected $checkoutSession;
    /**
     * @var PaymentDataObjectFactory
     */
    protected $paymentDataObjectFactory;
    /**
     * @var OrderRepositoryInterface
     */
    protected $orderRepository;
    /**
     * @var CommandPoolInterface
     */
    protected $commandPool;
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var PaymentFailuresInterface|null
     */
    protected $paymentFailures;

    public function __construct(
        Context $context,
        LoggerInterface $logger,
        PaymentDataObjectFactory $paymentDataObjectFactory,
        OrderInterfaceFactory $orderFactory,
        CommandPoolInterface $commandPool,
        Session $checkoutSession,
        PaymentFailuresInterface $paymentFailures
    ) {
        parent::__construct($context, $logger, $paymentDataObjectFactory, $orderFactory, $commandPool);
        $this->checkoutSession = $checkoutSession;
        $this->paymentFailures = $paymentFailures;
    }


    public function execute()
    {
        try {
            $orderId = $this->checkoutSession->getLastOrderId();
            if ($orderId) {
                /** @var \Magento\Sales\Model\Order $order */
                $order   = $this->orderFactory->create()->load($orderId);
                $payment = $order->getPayment();
                $paymentDataObject = $this->paymentDataObjectFactory->create($payment);

                $this->commandPool->get('getPayUrl')->execute(
                    [
                        'payment' => $paymentDataObject,
                        'amount' => $order->getTotalDue(),
                    ]
                );
            }
        } catch (LocalizedException $e) {
            $this->paymentFailures->handle((int)$this->checkoutSession->getLastQuoteId(), $e->getMessage());
            $this->logger->critical($e);
            $this->messageManager->addErrorMessage($e->getMessage());
        } catch (\Exception $e) {
            $this->paymentFailures->handle((int)$this->checkoutSession->getLastQuoteId(), $e->getMessage());
            $this->logger->critical($e);

            $this->messageManager->addErrorMessage(__('Sorry, but something went wrong.'));
        }

        return $this->_redirect('checkout/cart');
    }
}
