<?php
namespace zhaiyujin\sandpay\Contracts;



use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;
use function header;

/**
 * Abstract class for Request classes
 * @package Asrx\Sandpay
 */
abstract class AbstractRequest{
    /**
     * URL to production environment
     */
    const PRODUCTION_URL = null;

    /**
     * URL to testing environment
     */
    const TESTING_URL = null;

    private $curl = null;

    private $response;

    /**
     * AbstractRequest constructor.
     * @throws \ErrorException
     */
    public function __construct()
    {
        //  $this->curl = new Curl();
    }

    /**
     * @return null
     */
    public final function getResponse()
    {
        return $this->response;
    }



    /**
     * @param string $uri
     * @param array $params
     * @return $this
     */
    public final function httpPost(string $uri,array $params)
    {
        $this->response = $this->http_post_json($uri,$params);
        return $this;
    }

    /**
     * [建立请求，以表单HTML形式构造（默认）]
     * @param  [array]  $para_temp [请求参数数组]
     * @param  [string]  $method    [接口名]
     * @param  boolean $verTag    [是否需要版本字段]
     * @param  boolean $sortTag   [是否需要自动排序]
     * @return [type]             [description]
     */
    public function buildRequestForm($url,$para) {

        //待请求参数数组
        header("Content-type: text/html; charset=utf-8");
        $sHtml = "<form id='submit' name='submit' action='". $url ."' method='POST'>";
        while (list ($key, $val) = each ($para)) {
            // if(get_magic_quotes_gpc()){$val = stripslashes($val);}
            $sHtml.= "<input type='hidden' name='".$key."' value='".$val."'/>";
        }

        //submit按钮控件请不要含有name属性
        $sHtml .= "<input type='submit'  value='' style='display:none;'></form>";

        $sHtml .= "<script>document.forms['submit'].submit();</script>";

        return $sHtml;
    }
    /**
     * 除去数组中的空值和签名参数
     * @param $para 签名参数组
     * return 去掉空值与签名参数后的新签名参数组
     */
    public function paraFilter($para) {
        $para_filter = array();
        while (list ($key, $val) = each ($para)) {
         $para_filter[$key] = $para[$key];
        }
        return $para_filter;
    }
    public   function http_post_json($url, $param,$option="post")
    {
        if (empty($url) || empty($param)) {
            return false;
        }

        try {

            $ch = curl_init();//初始化curl
            curl_setopt($ch, CURLOPT_URL, $url);
            curl_setopt($ch, CURLOPT_POST, 1);
            curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($param));
            curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded'));
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/64.0.3282.186 Safari/537.36');
            //正式环境时解开注释
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);

            $data = curl_exec($ch);//运行curl
            curl_close($ch);
            Log::info("提现返回报文...");
            Log::info($data);
            if (!$data) {
                throw new \Exception('请求出错');
            }


            $this->response=$data;
            return $this->response;
        } catch (\Exception $e) {
            throw $e;
        }
    }


}
