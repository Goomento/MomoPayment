<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_MomoPayment
 * @link https://github.com/Goomento/MomoPayment
 */

namespace Goomento\MomoPayment\Gateway\Http;

use Goomento\MomoPayment\Gateway\Request\AbstractDataBuilder;
use Goomento\MomoPayment\Helper\Config;
use Magento\Framework\HTTP\ZendClient;
use Magento\Framework\Serialize\Serializer\Json;
use Magento\Payment\Gateway\Http\TransferBuilder;
use Magento\Payment\Gateway\Http\TransferFactoryInterface;

/**
 * Class TransferFactory
 * @package Goomento\MomoPayment\Gateway\Http
 */
class TransferFactory extends \Magento\Framework\HTTP\ZendClientFactory implements TransferFactoryInterface
{
    /**
     * @var TransferBuilder
     */
    protected $transferBuilder;
    /**
     * @var Json
     */
    protected $serializer;

    /**
     * TransferFactory constructor.
     * @param \Magento\Framework\ObjectManagerInterface $objectManager
     * @param TransferBuilder $transferBuilder
     * @param Json $serializer
     * @param string $instanceName
     */
    public function __construct(
        \Magento\Framework\ObjectManagerInterface $objectManager,
        TransferBuilder $transferBuilder,
        Json $serializer,
        $instanceName = '\\Magento\\Framework\\HTTP\\ZendClient'
    ) {
        parent::__construct($objectManager, $instanceName);
        $this->transferBuilder = $transferBuilder;
        $this->serializer = $serializer;
    }

    /**
     * @param array $request
     * @return \Magento\Payment\Gateway\Http\Transfer|\Magento\Payment\Gateway\Http\TransferInterface
     */
    public function create(array $request = [])
    {
        $this->correctRequest($request);
        $this->setSignatureParams($request);
        $body = $this->serializer->serialize($request);

        return $this->transferBuilder
            ->setMethod(ZendClient::POST)
            ->setBody($body)
            ->setHeaders([
                'Content-Type: application/json',
                'Content-Length: ' . strlen($body)
            ])
            ->setUri(Config::staticPaymentUrl())
            ->build();
    }

    /**
     * @param array $request
     */
    public function setSignatureParams(array &$request)
    {
        $params = [];
        foreach ($this->getSignatureData() as $field) {
            isset($request[$field])&&$params[$field] = $request[$field];
        }
        $request[AbstractDataBuilder::SIGNATURE] = \Goomento\MomoPayment\Helper\Helper::hash($params);
    }

    public function correctRequest(array &$request)
    {
        /**
         * Remove data default data
         */
        if (isset($request['payment'])) {
            unset($request['payment']);
        }

        if (isset($request[AbstractDataBuilder::AMOUNT_MARK])) {
            $request[AbstractDataBuilder::AMOUNT] = $request[AbstractDataBuilder::AMOUNT_MARK];
            unset($request[AbstractDataBuilder::AMOUNT_MARK]);
        }
    }

    /**
     * @param array $params
     * @return array
     */
    public function getSignatureData()
    {
        return [
            AbstractDataBuilder::PARTNER_CODE,
            AbstractDataBuilder::ACCESS_KEY,
            AbstractDataBuilder::REQUEST_ID,
            AbstractDataBuilder::AMOUNT,
            AbstractDataBuilder::ORDER_ID,
            AbstractDataBuilder::TRANSACTION_ID, // For refund
            AbstractDataBuilder::ORDER_INFO,
            AbstractDataBuilder::RETURN_URL,
            AbstractDataBuilder::NOTIFY_URL,
            AbstractDataBuilder::EXTRA_DATA
        ];
    }
}
