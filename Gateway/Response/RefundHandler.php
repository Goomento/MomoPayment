<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_MomoPayment
 * @link https://github.com/Goomento/MomoPayment
 */

namespace Goomento\MomoPayment\Gateway\Response;

use Goomento\MomoPayment\Gateway\Request\AbstractDataBuilder;
use Magento\Payment\Gateway\Helper\SubjectReader;
use Magento\Sales\Model\Order\Payment;

/**
 * Class RefundHandler
 * @package Goomento\MomoPayment\Gateway\Response
 */
class RefundHandler extends AbstractResponseHandler
{

    public function handle(array $handlingSubject, array $response)
    {
        $paymentDO = SubjectReader::readPayment($handlingSubject);
        /** @var Payment $orderPayment */
        $orderPayment = $paymentDO->getPayment();
        $orderPayment->setTransactionId('refund-' . $response[AbstractDataBuilder::TRANSACTION_ID]);

        $orderPayment->setIsTransactionClosed(true);
        $orderPayment->setShouldCloseParentTransaction(!$orderPayment->getCreditmemo()->getInvoice()->canRefund());
    }
}
