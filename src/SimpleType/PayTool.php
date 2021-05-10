<?php

namespace Asrx\Sandpay\SimpleType;

use Asrx\Sandpay\AbstractSimpleType;

/**
 * Class PayTool
 *
 *
 */
class PayTool extends AbstractSimpleType
{
    // 支付宝扫码
    const _ALIPAY_SCAN_QRCODE = '0401';
    // 微信扫码
    const _WEICHAT_SCAN_QRCODE = '0402';
    // 银联扫码
    const _UNIONPAY_SCAN_QRCODE = '0403';
    // QQ钱包扫码
    const _QQ_WALLET_SCAN_QRCODE = '0405';
    // 京东钱包扫码
    const _JD_WALLET_SCAN_QRCODE = '0406';
}
