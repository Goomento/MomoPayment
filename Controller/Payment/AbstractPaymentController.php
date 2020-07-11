<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_MomoPayment
 * @link https://github.com/Goomento/MomoPayment
 */

namespace Goomento\MomoPayment\Controller\Payment;


use Magento\Framework\App\Action\Context;
use Magento\Payment\Gateway\Command\CommandPoolInterface;
use Magento\Payment\Gateway\Data\PaymentDataObjectFactory;
use Magento\Sales\Api\Data\OrderInterfaceFactory;
use Psr\Log\LoggerInterface;

/**
 * Class AbstractPaymentController
 * @package Goomento\MomoPayment\Controller\Payment
 */
abstract class AbstractPaymentController extends \Goomento\Base\Controller\AbstractApiController
{
    /**
     * @var CommandPoolInterface
     */
    protected $commandPool;
    /**
     * @var PaymentDataObjectFactory
     */
    protected $paymentDataObjectFactory;
    /**
     * @var LoggerInterface
     */
    protected $logger;
    /**
     * @var OrderInterfaceFactory
     */
    protected $orderFactory;

    /**
     * AbstractPaymentController constructor.
     * @param Context $context
     * @param LoggerInterface $logger
     * @param PaymentDataObjectFactory $paymentDataObjectFactory
     * @param OrderInterfaceFactory $orderFactory
     * @param CommandPoolInterface $commandPool
     */
    public function __construct(
        Context $context,
        LoggerInterface $logger,
        PaymentDataObjectFactory $paymentDataObjectFactory,
        OrderInterfaceFactory $orderFactory,
        CommandPoolInterface $commandPool
    ) {
        parent::__construct($context);
        $this->paymentDataObjectFactory = $paymentDataObjectFactory;
        $this->commandPool = $commandPool;
        $this->orderFactory = $orderFactory;
        $this->logger = $logger;
    }
}
