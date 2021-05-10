<?php

namespace zhaiyujin\sandpay\PreCreate;
use function config;
use zhaiyujin\sandpay\Contracts\AbstractCompare;


/**
 * Class Head
 *
 *
 *
 * @property string $Version
 * @property string $Method
 * @property string $ProductId
 * @property string $AccessType
 * @property string $Mid
 * @property string $PlMid
 * @property string $ChannelType
 * @property string $ReqTime
 */
class Head extends AbstractCompare
{
    protected $name = 'head';

    /**
     * Head constructor.
     */
    public function __construct()
    {
        $this->Version = '1.0';
        $this->ReqTime = date('YmdHis',time());
        $this->Mid=config("sandpay.sandpay_collection");
    }
    public function fill( $data)
    {
        if(!is_array($data)) return false;
        foreach ($data as $name => $value) {
            $this->$name = $value;
        }
        return $this;
    }
    /**
     * 版本号
     * @param $version
     * @return $this
     */
    public function setVersion($version)
    {
        $this->values['version'] = $version;
        return $this;
    }

    /**
     * 接口method
     * @param $method
     * @return $this
     */
    public function setMethod($method)
    {
        $this->values['method'] = $method;
        return $this;
    }

    /**
     * 产品编码
     * @param $productId
     * @return $this
     */
    public function setProductId($productId)
    {
        $this->values['productId'] = $productId;
        return $this;
    }

    /**
     * 接入类型
     * 1-普通商户接入 | 2-平台商户接入 | 3-核心企业商户接入
     * @param $accessType
     * @return $this
     */
    public function setAccessType($accessType)
    {
        $this->values['accessType'] = $accessType;
        return $this;
    }

    /**
     * 商户ID
     * 收款方商户号
     * @param $mid
     * @return $this
     */
    public function setMid($mid)
    {
        $this->values['mid'] = $mid;
        return $this;
    }

    /**
     * 平台ID
     * 接入类型为2时必填，在担保支付模式下填写核心商户号
     * @param $plMid
     * @return $this
     */
    public function setPlMid($plMid)
    {
        $this->values['plMid'] = $plMid;
        return $this;
    }

    /**
     * 渠道类型
     * 07-互联网 | 08-移动端
     * @param $channelType
     * @return $this
     */
    public function setChannelType($channelType)
    {
        $this->values['channelType'] = $channelType;
        return $this;
    }

    /**
     * 请求时间
     * 格式：yyyyMMddhhmmss
     * @param $reqTime
     * @return $this
     */
    public function setReqTime($reqTime)
    {
        $this->values['reqTime'] = $reqTime;
        return $this;
    }
}
