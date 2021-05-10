```php
<?php
/**
 * Created by PhpStorm.
 * User: zhaiyujin
 * Date: 20-3-19
 * Time: 下午5:25
 */

namespace App\Logic;


use App\Models\User;
use function dump;
use function generateOrderId;
use Illuminate\Http\Request;
use function json_decode;
use function json_encode;
use Yansongda\Supports\Logger;
use zhaiyujin\sandpay\Facade\PayData;
use zhaiyujin\sandpay\Facade\SandPay;

class SandPayLogic
{


    //移动发起支付

    /**
     * [mid]商户号
     * [orderCode]商户订单号
     * [totalAmount]订单金额
     * [subject]订单标题
     * [body]订单描述
     * [txnTimeOut]订单超时时间
     * [payMode]支付模式
     * [bankCode]银行编码
     * [payType]支付类型:
     * [clientIp]客户端IP
     * [notifyUrl]异步通知地址
     * [frontUrl]前台通知地址
     * [extend]扩展域
     */
    public static function pay ($data,$type)
    {

        $head=[
            'method' => 'sandpay.trade.orderCreate',
            'productId' => $data['productId'],
            'accessType' => '1',
            'channelType' => '08',
        ];
        $body=[
            'orderCode' => $data['orderCode'],
            'totalAmount' => $data['totalAmount'],
            'subject' => $data['subject'],
            'body' => $data['body'],
            "userId"=>$data['userId'],
            'notifyUrl' => $data['notifyUrl'],
            'frontUrl' => $data['frontUrl'],
            "clearCycle"=>"0",
            "accountingMode"=>"02",
            "orderTime"=>time(),
            "currencyCode"=>156
        ];

        $data= PayData::fillData($head,$body);
       return SandPay::Handle($data,$type);

    }

    public static function sand_daifu($data)
    {


        $info=array(
            'transCode' => 'RTPM', // 实时代付
            'url' => '/agentpay',
            'pt' => array(
                'orderCode' =>  $data['orderCode'],
                'version' => '01',
                'productId' => '00000004',
                'tranTime' => $data['tran_time'],
                'tranAmt' => sprintf("%012d", $data['tranAmt'] * 100),
                'currencyCode' => '156',
                'accAttr' => '0',
                'accNo' => $data['accNo'],
                'accType' => 4,
                'accName' => $data['accName'],
                'remark' => '用户提现',
                'payMode' => '1',
                'channelType' => '07'
            ));
        try {


            $result = SandPay::openApi($info);
            return json_decode($result,true);

        } catch (\Exception $e) {
            return ["respCode"=>"1111","respDesc"=>$e->getMessage()];
        }



        
        
        
    }


    public static function order_query($orderCode,$tran_time)
    {

        $info=array(
            'transCode' => 'ODQU', // 订单查询
            'url' => '/queryOrder',
            'pt' => array(
                'orderCode' => $orderCode,
                'version' => '01',
                'productId' => '00000004',
                'tranTime' => $tran_time
            )
        );

            $result = SandPay::openApi($info);
            return json_decode($result,true);


    }



}
```
