<?php

namespace zhaiyujin\sandpay\SimpleType;

use zhaiyujin\sandpay\AbstractSimpleType;

/**
 * Class PayTool
 *
 *
 */
class AccessType extends AbstractSimpleType
{
    // 1-普通商户接入
    const _MERCHANT_ORDINARY = '1';

    // 2-平台商户接入
    const _MERCHANT_PLATFORM = '2';

    // 3-核心企业商户接入
    const _MERCHANT_CORE_ENTERPRISE = '3';
}
