<?php
namespace Upadd\Bin\Client;

use Data;

class HttpAsync
{

    private $is_monitor = false;

    private $monitorData = [];

    private $error = '';

    public function monitor()
    {
        $this->is_monitor = true;
    }

    /**
     * 设置url
     * @param $host
     * @return array
     */
    private function setUrl($host)
    {
        $req = parse_url($host);
        $_host = $req['host'];
        $path = isset($req['path']) ? $req['path'] : null;
        $query = isset( $req['query']) ?  $req['query'] : null;
        $url = '';
        if($path && $query)
        {
            $url = $path.'?'.$query;
        }elseif($path && empty($query))
        {
            $url = $path;
        }else{
            $url = '/';
        }
        return [$_host,$url];
    }

    /**
     * 异步
     * @param $host
     * @param $url
     * @param int $port
     * @return bool
     */
    public function get($host=null,$port=80)
    {
        list($_host,$url) = $this->setUrl($host);
        $fp = fsockopen($_host, $port, $errno, $errstr, 3);
        if($fp)
        {
            $head = "GET {$url} HTTP/1.0 \r\n";
            $head .= "Host: {$_host}\r\n";
            $head .= "\r\n";
            fwrite($fp, $head);
            /**
             * 判断是否监听
             */
            if($this->is_monitor)
            {
                //监听数据
                while (!feof($fp))
                {
                    $this->monitorData[] = fgets($fp, 128);
                }
            }
            /**
             * 关闭异步
             */
            fclose($fp);
            return true;
        }else{
            $this->error = $errstr . ($errno);
            return false;
        }
    }


    /**
     * 异步post
     * @param null $host
     * @param array $data
     * @param int $port
     * @return bool
     */
    public function post($host=null,$data=[],$port=80)
    {
        list($_host,$url) = $this->setUrl($host);
        $post = http_build_query($data);
        $length = strlen($post);
        $fp = fsockopen( $_host , $port, $errno, $errstr, 30);
        if ($fp)
        {
            $out = "POST $url HTTP/1.0\r\n";
            $out .= "Host: $_host\r\n";
            $out .= "Content-type: application/x-www-form-urlencoded\r\n";
            $out .= "Connection: Close\r\n";
            $out .= "Content-Length: $length\r\n";
            $out .= "\r\n";
            $out .= $post."\r\n";
            fwrite($fp, $out);
            /**
             * 判断是否监听
             */
            if($this->is_monitor)
            {
                //监听数据
                while (!feof($fp))
                {
                    $this->monitorData[] = fgets($fp, 128);
                }
            }
            fclose($fp);
            return true;
        }else{
            $this->error = $errstr . ($errno);
            return false;
        }
    }

    /**
     * 返回数据
     * @return array
     */
    public function data()
    {
        return $this->monitorData;
    }

    /**
     * 返回错误
     * @return array
     */
    public function error()
    {
        return $this->error;
    }




}