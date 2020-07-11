<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_MomoPayment
 * @link https://github.com/Goomento/MomoPayment
 */

namespace Goomento\MomoPayment\Gateway\Request;

use Magento\Payment\Gateway\Request\BuilderInterface;

/**
 * Class AbstractDataBuilder
 * @package Goomento\MomoPayment\Gateway\Request
 */
abstract class AbstractDataBuilder implements BuilderInterface
{
    const PAY_URL_PATH = 'gw_payment/transactionProcessor';

    const REQUEST_TYPE = 'requestType';

    const ORDER_INFO = 'orderInfo';

    const ORDER_ID = 'orderId';

    const AMOUNT = 'amount';

    const AMOUNT_MARK = '_amount';

    const EXTRA_DATA = 'extraData';

    const REQUEST_CAPTURE = 'captureMoMoWallet';

    const REQUEST_REFUND = 'refundMoMoWallet';

    const PARTNER_CODE = 'partnerCode';

    const ACCESS_KEY = 'accessKey';

    const RETURN_URL = 'returnUrl';

    const NOTIFY_URL = 'notifyUrl';

    const REQUEST_ID = 'requestId';

    const TRANSACTION_ID = 'transId';

    const SIGNATURE = 'signature';

    const VND_CURRENCY = 'VND';

    /**
     * @var string|null
     */
    protected $requestType;

    /**
     * AbstractDataBuilder constructor.
     * @param string|null $requestType
     */
    public function __construct(
        string $requestType = null
    ) {
        $this->requestType = $requestType;
    }
}
