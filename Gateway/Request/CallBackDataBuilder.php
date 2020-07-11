<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_MomoPayment
 * @link https://github.com/Goomento/MomoPayment
 */

namespace Goomento\MomoPayment\Gateway\Request;

use Goomento\MomoPayment\Helper\Config;
use Magento\Framework\UrlInterface;

/**
 * Class CallBackDataBuilder
 * @package Goomento\MomoPayment\Gateway\Request
 */
class CallBackDataBuilder extends AbstractDataBuilder
{
    /**
     * @var UrlInterface
     */
    protected $url;

    public function __construct(
        UrlInterface $url,
        string $requestType = null
    ) {
        parent::__construct($requestType);
        $this->url = $url;
    }

    /**
     * @param array $buildSubject
     * @return array
     */
    public function build(array $buildSubject)
    {
        return array_merge($buildSubject, [
            AbstractDataBuilder::NOTIFY_URL => $this->url->getUrl(Config::CODE . '/payment/ipn'),
            AbstractDataBuilder::RETURN_URL => $this->url->getUrl(Config::CODE . '/payment/returnAction'),
        ]);
    }
}
