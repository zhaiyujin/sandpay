<?php

namespace zhaiyujin\sandpay;


use function is_string;
use zhaiyujin\sandpay\Contracts\AbstractRequest;

/**
 * Class Sand
 *
 * @package \Asrx\Sandpay
 */
class Sand extends AbstractRequest
{
    private $certPath;
    private $pfxPath;
    private $password;
    protected $config;
    private $data;
    private $signContent;

    /**
     * @param null $certPath
     */
    public function setCertPath($certPath)
    {
        $this->certPath = $certPath;
    }

    /**
     * @param null $pfxPath
     */
    public function setPfxPath($pfxPath)
    {
        $this->pfxPath = $pfxPath;
    }

    /**
     * @param mixed $password
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * Sand constructor.
     * @param null $certPath
     * @param null $pfxPath
     * @param null $password
     * @throws \ErrorException
     */
    public function __construct()
    {
        parent::__construct();
        $this->config=config("sandpay");
        $this->certPath = $this->config['sandpay_pub_key_path'];
        $this->pfxPath = $this->config['sandpay_pri_key_path'];;
        $this->password = $this->config['sandpay_cert_pwd'];;
    }

    /**
     * 获取公钥
     * @return mixed
     * @throws \Exception
     */
    private function loadX509Cert()
    {
        try {
            $file = file_get_contents($this->certPath);
            if (!$file){
                throw new \Exception('`SandPay cert` File Is Not Found.');
            }

            $cert = chunk_split(base64_encode($file));
            $cert = "-----BEGIN CERTIFICATE-----\n{$cert}-----END CERTIFICATE-----\n";

            $res = openssl_pkey_get_public($cert);
            $content = openssl_pkey_get_details($res);
            openssl_free_key($res);

            if (!$content){
                throw new \Exception('`SandPay cert` Is Error');
            }
            return $content['key'];
        }catch (\Exception $e){
            throw $e;
        }
    }


    /**
     * 获取公钥
     * @param $path
     * @return mixed
     * @throws Exception
     */
    function dfloadX509Cert()
    {
        try {
            $file = file_get_contents($this->certPath);
            if (!$file) {
                throw new \Exception('loadx509Cert::file_get_contents ERROR');
            }

            $cert = chunk_split(base64_encode($file), 64, "\n");
            $cert = "-----BEGIN CERTIFICATE-----\n" . $cert . "-----END CERTIFICATE-----\n";

            $res = openssl_pkey_get_public($cert);
            $detail = openssl_pkey_get_details($res);
            openssl_free_key($res);

            if (!$detail) {
                throw new \Exception('loadX509Cert::openssl_pkey_get_details ERROR');
            }

            return $detail['key'];
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 获取私钥
     * @param $path
     * @param $pwd
     * @return mixed
     * @throws Exception
     */
  public  function dfloadPk12Cert()
    {
        try {
            $file = file_get_contents($this->pfxPath);
            if (!$file) {
                throw new \Exception('loadPk12Cert::file
					_get_contents');
            }

            if (!openssl_pkcs12_read($file, $cert, $this->password)) {
                throw new \Exception('loadPk12Cert::openssl_pkcs12_read ERROR');
            }
            return $cert['pkey'];
        } catch (\Exception $e) {
            throw $e;
        }
    }
    /**
     * 获取私钥
     * @return mixed
     * @throws \Exception
     */
    private function loadPk12Cert()
    {
        try {
            $file = file_get_contents($this->pfxPath);
            if (!$file) {
                throw new \Exception('`SandPay pfx` File Is Not Found.');
            }

            if (!openssl_pkcs12_read($file, $content, $this->password)) {
                throw new \Exception('`SandPay pfx` Is Error');
            }
            return $content['pkey'];
        } catch (\Exception $e) {
            throw $e;
        }
    }


    /**
     * 私钥签名
     * @param $plainText
     * @return $this
     * @throws \Exception
     */
    public final function sign($plainText)
    {
        $this->data = $plainText;
        $path = $this->loadPk12Cert();

        $plainText = json_encode($plainText);
        try {
            $resource = openssl_pkey_get_private($path);
            $result = openssl_sign($plainText, $sign, $resource);
            openssl_free_key($resource);

            if (!$result) {
                throw new \Exception('Sign Error: ' . $plainText);
            }

            $this->signContent = base64_encode($sign);
        } catch (\Exception $e) {
            throw $e;
        }
        return $this;
    }



    /**
     * 私钥签名
     * @param $plainText
     * @param $path
     * @return string
     * @throws Exception
     */
    function df_sign($plainText)
    {
        $this->data = $plainText;
        $path = $this->dfloadPk12Cert();
        $plainText = json_encode($plainText);
        try {
            $resource = openssl_pkey_get_private($path);
            $result = openssl_sign($plainText, $sign, $resource);
            openssl_free_key($resource);

            if (!$result) {
                throw new \Exception('签名出错' . $plainText);
            }

            return base64_encode($sign);
        } catch (\Exception $e) {
            throw $e;
        }
    }

    /**
     * 验证签名
     * @param $plainText
     * @param $signContent
     * @return bool
     * @throws \Exception
     */
    public final function verify($plainText, $signContent)
    {
        $path = $this->loadX509Cert();

        $resource = openssl_pkey_get_public($path);
        $result = openssl_verify($plainText, base64_decode($signContent), $resource);
        openssl_free_key($resource);

        return $result ? true : false;
//        if (!$result){
//            throw new \Exception('Signature verification failed.');
//        }
//        return true;
    }

    public final function parseResult($result)
    {
        $arr = [];
        $response = urldecode($result);

        $arrStr = explode('&',$response);
        foreach ($arrStr as $item) {
            $p = strpos($item,'=');
            $key = substr($item,0,$p);
            $value = substr($item,$p+1);
            $arr[$key] = $value;
        }
       
        return $arr;
    }

    public final function getRequestParams()
    {
        if ($this->signContent){
            return [
                'charset' => 'utf-8',
                'shignType' => '01',
                'data' => json_encode($this->data),
                'sign' => $this->signContent
            ];
        }
        throw new \Exception('Need to Signed.');
    }

    /**
     * 生成AESKey
     * @param $size
     * @return string
     */
    public function aes_generate($size)
    {
        $str = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
        $arr = array();
        for ($i = 0; $i < $size; $i++) {
            $arr[] = $str[mt_rand(0, 61)];
        }

        return implode('', $arr);
    }




    /**
     * 公钥验签
     * @param $plainText
     * @param $sign
     * @param $path
     * @return int
     * @throws Exception
     */
   public function verify2($plainText, $sign)
    {
        $path=$this->loadX509Cert();
        $resource = openssl_pkey_get_public($path);
        $result = openssl_verify($plainText, base64_decode($sign), $resource);
        openssl_free_key($resource);

        if (!$result) {
            throw new \Exception('签名验证未通过,plainText:' . $plainText . '。sign:' . $sign, '02002');
        }

        return $result;
    }

    public function verify_dec($result)
    {
        $decryptAESKey=$this->RSADecryptByPri($result['encryptKey'],$this->loadPk12Cert());
        $decryptPlainText=$this->AESDecrypt($result['encryptData'],$decryptAESKey);
        return $decryptPlainText;
    }
    /**
     * 公钥加密AESKey
     * @param $plainText
     * @param $puk
     * @return string
     * @throws Exception
     */
  public  function RSAEncryptByPub($plainText)
    {
        $puk= $this->dfloadX509Cert();
        if (!openssl_public_encrypt($plainText, $cipherText, $puk, OPENSSL_PKCS1_PADDING)) {
            throw new \Exception('AESKey 加密错误');
        }

        return base64_encode($cipherText);
    }

    /**
     * 私钥解密AESKey
     * @param $cipherText
     * @param $prk
     * @return string
     * @throws Exception
     */
   public function RSADecryptByPri($cipherText, $prk)
    {
        if (!openssl_private_decrypt(base64_decode($cipherText), $plainText, $prk, OPENSSL_PKCS1_PADDING)) {
            throw new \Exception('AESKey 解密错误');
        }

        return (string)$plainText;
    }
    /**
     * AES加密
     * @param $plainText
     * @param $key
     * @return string
     * @throws \Exception
     */
   public function AESEncrypt($plainText, $key)
    {
        $plainText = json_encode($plainText);
        $result = openssl_encrypt($plainText, 'AES-128-ECB', $key, 1);

        if (!$result) {
            throw new \Exception('报文加密错误');
        }

        return base64_encode($result);
    }

    /**
     * AES解密
     * @param $cipherText
     * @param $key
     * @return string
     * @throws \Exception
     */
   public  function AESDecrypt($cipherText, $key)
    {
        $result = openssl_decrypt(base64_decode($cipherText), 'AES-128-ECB', $key, 1);

        if (!$result) {
            throw new \Exception('报文解密错误', 2003);
        }

        return $result;
    }

}
