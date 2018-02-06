<?php
namespace Upadd\Bin\Client;

/**
 * About:Richard.z
 * Email:v3u3i87@gmail.com
 * Blog:https://www.zmq.cc
 * Date: 2018/1/12
 * Time: 下午3:57
 * Name:
 */
use Log;
use Upadd\Bin\Client;

class HttpCurl extends Client
{

    /**
     * CRUL方法
     * @param array $_param
     * @return array|bool
     */
    private function send()
    {
        try {
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $this->url);
            if ($this->header) {
                curl_setopt($ch, CURLOPT_HTTPHEADER, $this->header);
            }
            curl_setopt($ch, CURLOPT_CUSTOMREQUEST, $this->methods);
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, false);
            curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 6.1) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.63 Safari/537.36');
            curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 60);
            curl_setopt($ch, CURLOPT_AUTOREFERER, 1);
            //数据
            curl_setopt($ch, CURLOPT_POSTFIELDS, $this->data);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            $body = curl_exec($ch);
            curl_close($ch);
            $this->isLog($body);
            $this->isResponseType($body);
        } catch (Exception $exception) {
            echo $exception->getMessage();
        }
    }


    /**
     * @return mixed
     */
    public function sync()
    {
        $this->send();
    }

    /**
     * @return mixed
     */
    public function async()
    {

    }

    /**
     * @return mixed
     */
    public function close()
    {

    }

}