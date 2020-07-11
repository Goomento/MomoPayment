<?php
/**
 * @author @haihv433
 * @copyright Copyright (c) 2020 Goomento (https://store.goomento.com)
 * @package Goomento_MomoPayment
 * @link https://github.com/Goomento/MomoPayment
 */

namespace Goomento\MomoPayment\Gateway\Response;

use Goomento\MomoPayment\Gateway\Helper\SubjectReader;
use Goomento\MomoPayment\Helper\Helper;
use Magento\Framework\Exception\LocalizedException;

/**
 * Class GetPayUrlHandler
 * @package Goomento\MomoPayment\Gateway\Response
 */
class GetPayUrlHandler extends AbstractResponseHandler
{
    /**
     * @var \Magento\Framework\App\Response\Http
     */
    protected $response;

    public function __construct(
        \Magento\Framework\App\Response\Http $response
    ) {
        $this->response = $response;
    }

    /**
     * @param array $handlingSubject
     * @param array $response
     * @throws LocalizedException
     */
    public function handle(array $handlingSubject, array $response)
    {
        $payUrl = SubjectReader::readPayUrl($response);
        if ($payUrl) {
            $this->response->setRedirect($payUrl);
            Helper::sendResponseAndExit($this->response);
        }

        throw new LocalizedException(__('Something went wrong when get Momo Payment Url'));
    }
}
