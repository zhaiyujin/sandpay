<?php

namespace zhaiyujin\sandpay\PreCreate;

use zhaiyujin\sandpay\Contracts\AbstactCompare;
use zhaiyujin\sandpay\Contracts\AbstractCompare;

/**
 * Class PreCreateBody
 *
 *
 *
 * @property string $PayTool
 * @property string $OrderCode
 * @property string $LimitPay
 * @property string $TotalAmount
 * @property string $Subject
 * @property string $Body
 * @property string $TxnTimeOut
 * @property string $NotifyUrl
 * @property string $FrontUrl
 * @property string $StoreId
 * @property string $TerminalId
 * @property string $OperatorId
 * @property string $ClearCycle
 * @property string $RiskRateInfo
 * @property string $BizExtendParams
 * @property string $MerchExtendParams
 * @property string $Extend
 *
 */
class Body extends AbstractCompare
{
    protected $name = 'body';


    public function fill( $data)
    {
        if(!is_array($data)) return false;
        foreach ($data as $name => $value) {
            $this->$name = $value;
        }
        return $this;
    }

    /**
     * 支付工具
     * @param $payTool
     * @return $this
     */
    public function setPayTool($payTool)
    {
        $this->values['payTool'] = $payTool;
        return $this;
    }

    public function setPayModeList($payModelList)
    {
        $this->values['payModeList'] = $payModelList;
        return $this;
    }
    /**
     * 商户订单号
     * @param $orderCode
     * @return $this
     */
    public function setOrderCode($orderCode)
    {
        $this->values['orderCode'] = $orderCode;
        return $this;
    }

    /**
     * 限定支付方式 (支付工具为微信扫码有效；1-限定不能使用信用卡)
     * @param $limitPay
     * @return $this
     */
    public function setLimitPay($limitPay)
    {
        $this->values['limitPay'] = $limitPay;
        return $this;
    }

    /**
     * 订单金额
     * @param $totalAmount
     * @return $this
     */
    public function setTotalAmount($totalAmount)
    {
        $this->values['totalAmount'] = sprintf("%012d", $totalAmount * 100);
        return $this;
    }

    /**
     * 订单标题
     * @param $subject
     * @return $this
     */
    public function setSubject($subject)
    {
        $this->values['subject'] = $subject;
        return $this;
    }

    /**
     * 订单描述
     * @param $body
     * @return $this
     */
    public function setBody($body)
    {
        $this->values['body'] = $body;
        return $this;
    }

    /**
     * 订单超时时间
     * @param $txnTimeOut
     * @return $this
     */
    public function setTxnTimeOut($txnTimeOut)
    {
        $this->values['txnTimeOut'] = $txnTimeOut;
        return $this;
    }

    /**
     * 异步通知地址
     * @param $notifyUrl
     * @return $this
     */
    public function setNotifyUrl($notifyUrl)
    {
        $this->values['notifyUrl'] = $notifyUrl;
        return $this;
    }

    /**
     * 前台通知地址
     * @param $frontUrl
     * @return $this
     */
    public function setFrontUrl($frontUrl)
    {
        $this->values['frontUrl'] = $frontUrl;
        return $this;
    }

    /**
     * 商户门店编号
     * @param $storeId
     * @return $this
     */
    public function setStoreId($storeId)
    {
        $this->values['storeId'] = $storeId;
        return $this;
    }

    /**
     * 商户终端编号
     * @param $terminalId
     * @return $this
     */
    public function setTerminalId($terminalId)
    {
        $this->values['terminalId'] = $terminalId;
        return $this;
    }

    /**
     * 操作员编号
     * @param $operatorId
     * @return $this
     */
    public function setOperatorId($operatorId)
    {
        $this->values['operatorId'] = $operatorId;
        return $this;
    }

    /**
     * 清算模式
     * @param $clearCycle
     * @return $this
     */
    public function setClearCycle($clearCycle)
    {
        $this->values['clearCycle'] = $clearCycle;
        return $this;
    }


    public function setAccountingMode($accountingMode)
    {
        $this->values['accountingMode'] = $accountingMode;
        return $this;
    }
    /**
     * 风控信息域
     * @param $riskRateInfo
     * @return $this
     */
    public function setRiskRateInfo($riskRateInfo)
    {
        $this->values['riskRateInfo'] = $riskRateInfo;
        return $this;
    }

    /**
     * 业务扩展参数
     * @param $bizExtendParams
     * @return $this
     */
    public function setBizExtendParams($bizExtendParams)
    {
        $this->values['bizExtendParams'] = $bizExtendParams;
        return $this;
    }

    /**
     * 商户扩展参数
     * @param $merchExtendParams
     * @return $this
     */
    public function setMerchExtendParams($merchExtendParams)
    {
        $this->values['merchExtendParams'] = $merchExtendParams;
        return $this;
    }

    /**
     * 扩展域
     * @param $extend
     * @return $this
     */
    public function setExtend($extend)
    {
        $this->values['extend'] = $extend;
        return $this;
    }


    public function setAccsplitInfo($accsplitInfo)
    {
        $this->values['accsplitInfo'] = $accsplitInfo;
        return $this;
    }

    public function setPayMode($paymode)
    {
        $this->values['payMode']=$paymode;
        return $this;
    }
    public function setPayExtra($payExtra)
    {
        $this->values['payExtra']=$payExtra;
        return $this;
    }

    public function setClientIp($clientIp)
    {
        $this->values['clientIp']=$clientIp;
        return $this;
    }

    public function setUserId($userId)
    {
        $this->values['userId']=$userId;
        return $this;
    }

    public function setPayerName($payerName)
    {
        $this->values['payerName']=$payerName;
        return $this;
    }

    public function setCurrenCyCode($currencyCode)
    {
        $this->values['currencyCode']=$currencyCode;
        return $this;
    }
    public function setOrderTime($orderTime)
    {
        $this->values['orderTime']=$orderTime;
        return $this;
    }
}
