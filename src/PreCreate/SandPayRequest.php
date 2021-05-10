<?php

namespace zhaiyujin\sandpay\PreCreate;
use Illuminate\Support\Facades\Log;
use function env;
use function is_array;
use function is_string;
use zhaiyujin\sandpay\Sand;


/**
 * Class Request
 *
 * @package \Asrx\Sandpay\PreCreate
 */
class SandPayRequest extends Sand
{


    /**
     *
     * @param SandPayData $data
     * @return array
     * @throws \Exception
     */
    public function handle(SandPayData $data,$suffix="pay")
    {


        if(!$data) throw new \Exception("参数不能为空",1);
        $data=$data->toArray();

        $post = $this->sign($data)->getRequestParams();

     //   $this->httpPost($this->config['sandpay_api_host']."/".$suffix,$post);
     return   $this->buildRequestForm($this->config['sandpay_api_host']."/".$suffix,$post);
      //  $result = $this->parseResult($this->getResponse());

/*
        if (!$this->verify($result['data'],$result['sign'])){
            throw new \Exception('Signature verification failed.');
        }
return $result;
*/
      //  return ["url"=>$this->config['sandpay_api_host']."/".$suffix,"post"=>$post];


    }

    public function openApi($data)
    {

        $AESKey=$this->aes_generate(16);
        $encryptKey = $this->RSAEncryptByPub($AESKey);
        $encryptData = $this->AESEncrypt($data['pt'], $AESKey);
        $sign = $this->df_sign($data['pt']);
        $post = array(
            'transCode' => $data['transCode'],
            'accessType' => '0',
            'merId' => $this->config['sandpay_collection'],
            'encryptKey' => $encryptKey,
            'encryptData' => $encryptData,
            'sign' => $sign
        );
        Log::info("提现报文..");
        Log::info($post);
        $this->httpPost($this->config['sandpay_api_df_host']."/".$data['url'],$post);
        $result = $this->parseResult($this->getResponse());

        try {
            $decryptPlainText = $this->verify_dec($result);
            if (!$this->verify2($decryptPlainText, $result['sign'])) {
                throw new \Exception('Signature verification failed.');
            }
        } catch (\Exception $e) {
            throw new \Exception($e->getMessage());
        }
        Log::info("提现报文结果..");
        Log::info($decryptPlainText);
     return $decryptPlainText;

    }


}
