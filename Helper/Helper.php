<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_MomoPayment
 * @link https://github.com/Goomento/MomoPayment
 */

namespace Goomento\MomoPayment\Helper;

use Magento\Directory\Helper\Data as DirectoryHelper;
use Magento\Directory\Model\CurrencyFactory;
use Magento\Framework\App\Helper\Context;
use Magento\Framework\Exception\LocalizedException;
use Magento\Store\Model\StoreManagerInterface;

/**
 * Class Helper
 * @package Goomento\MomoPayment\Helper
 * @method staticConvertCurrency($amount, $from, $to = null)
 */
class Helper extends \Goomento\Base\Helper\AbstractHelper
{
    /**
     * @var DirectoryHelper
     */
    protected $directoryHelper;
    /**
     * @var StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var CurrencyFactory
     */
    protected $currencyFactory;

    public function __construct(
        Context $context,
        DirectoryHelper $directoryHelper,
        StoreManagerInterface $storeManager,
        CurrencyFactory $currencyFactory
    ) {
        $this->directoryHelper = $directoryHelper;
        $this->storeManager = $storeManager;
        $this->currencyFactory = $currencyFactory;
        parent::__construct($context);
    }

    /**
     * @param array $params
     * @return string
     */
    public static function hash(array $params)
    {
        return hash_hmac('sha256', urldecode(http_build_query($params)), Config::staticConfigGet('merchant_private_key'));
    }

    /**
     * @param $amount
     * @param $from
     * @param null $to
     * @return false|float
     * @throws LocalizedException
     */
    public function convertCurrency($amount, $from, $to = null)
    {
        try {
            if ($from !== $to) {
                if ($to === $this->getBaseCurrencyCode()) {
                    return round($this->convertToBaseCurrency($amount, $from));
                }

                $amount = $this->directoryHelper->currencyConvert($amount, $from, $to);
            }
            return round($amount);
        } catch (\Exception $e) {
            Logger::staticError($e->getMessage());
            throw new LocalizedException(__("Please, setup your currency ..."));
        }
    }

    /**
     * @param $amount
     * @param $from
     * @return float|int
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    protected function convertToBaseCurrency($amount, $from)
    {
        $rate = $this->currencyFactory->create()->load($this->getBaseCurrencySymbol())->getAnyRate($from);
        return $amount*$rate;
    }

    /**
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getBaseCurrencySymbol()
    {
        return $this->storeManager->getStore()->getBaseCurrency()->getCode();
    }

    /**
     * Get store base currency code
     *
     * @return string
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getBaseCurrencyCode()
    {
        return $this->storeManager->getStore()->getBaseCurrencyCode();
    }

    public static function sendResponseAndExit(\Magento\Framework\App\ResponseInterface $response = null, $code = 0)
    {
        if ($response) {
            $response->sendResponse();
        }

        exit($code);
    }
}
