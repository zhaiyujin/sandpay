<?php
namespace zhaiyujin\sandpay\Facade;

use Illuminate\Support\Facades\Facade;
use zhaiyujin\sandpay\PreCreate\sandPayData;
use zhaiyujin\sandpay\PreCreate\SandPayRequest;

/**
 * Created by PhpStorm.
 * User: zhaiyujin
 * Date: 20-3-19
 * Time: 下午4:26
 */

class PayData extends Facade
{
    /**
     * 获取组件的注册名称。
     *
     * @return string
     */
    protected static function getFacadeAccessor()
    {
        return sandPayData::class;
    }
}