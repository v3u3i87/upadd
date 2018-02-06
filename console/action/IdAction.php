<?php

namespace console\action;

use Config;

class IdAction extends \Upadd\Frame\Action
{


    public function test()
    {
//获取域名或主机地址
        echo $_SERVER['HTTP_HOST'] . "<br />";

//获取网页地址
        echo $_SERVER['PHP_SELF'] . "<br />";

//获取网址参数
        echo $_SERVER["QUERY_STRING"] . "<br />";

//获取用户代理
        echo $_SERVER['HTTP_REFERER'] . "<br />";

//获取完整的url
        echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['REQUEST_URI'];
        echo 'http://' . $_SERVER['HTTP_HOST'] . $_SERVER['PHP_SELF'] . '?' . $_SERVER['QUERY_STRING'];

//包含端口号的完整url
        echo 'http://' . $_SERVER['SERVER_NAME'] . ':' . $_SERVER["SERVER_PORT"] . $_SERVER["REQUEST_URI"];

//只取路径
        $url = 'http://' . $_SERVER['SERVER_NAME'] . $_SERVER["REQUEST_URI"];
        echo dirname($url);
    }

    /**
     * 获取局域网所有用户的电脑IP和主机名、及mac地址 调试
     */
    public function mac()
    {
        $bIp = gethostbyname($_ENV['COMPUTERNAME']); //获取本机的局域网IP
        echo "本机IP：", $bIp, "\n";
        //gethostbyaddr 函数可以根据局域网IP获取主机名
        echo "本机主机名：", gethostbyaddr($bIp), "\n\n\n";

        //默认网关IP
        list($ipd1, $ipd2, $ipd3) = explode('.', $bIp);
        $mask = $ipd1 . "." . $ipd2 . "." . $ipd3;
        exec('arp -a', $aIp); //获取局域网中的其他IP
        foreach ($aIp as $ipv) {
            //一下显示的IP是否是当前局域网中的 而不是其他的类型 可以在cmd下试一下命令
            if (strpos($ipv, '接口') !== false) {
                $bool = false;
                preg_match('/(?:(?:25[0-5]|2[0-4]\d|((1\d{2})|([1-9]?\d)))\.){3}(?:25[0-5]|2[0-4]\d|((1\d{2})|([1-9]?\d)))/', $ipv, $arr);
                if (strcmp($arr[0], $bIp) == 0) {
                    $bool = true;
                }
            }

            if ($bool) {
                $str = preg_replace('/\s+/', '|', $ipv);
                $sArr = explode('|', $str);
                if ($sArr[1] == 'Internet' || empty($sArr[1])) {
                    continue;
                }
                //去除默认网关
                if (strcmp($mask . ".1", $sArr[1]) == 0) {
                    continue;
                }
                //去除同网关下255的IP
                if (strcmp($mask . ".255", $sArr[1]) == 0) {
                    continue;
                }
                //去除组播IP
                list($cIp) = explode('.', $sArr[1]);
                if ($cIp >= 224 && $cIp <= 239) {
                    continue;
                }
                echo "IP地址：|", $sArr[1], "|\n";
                echo "MAC地址：", $sArr[2], "\n";
                echo "主机名：", gethostbyaddr($sArr[1]), "\n";
                echo "\n\n";
            }
        }
    }

    public function info()
    {
        $hostname = gethostbyaddr($_SERVER['remote_addr']);    //获取主机名
        echo $hostname;            //输出结果
//
        $hosts = gethostbynamel('localhost');       //获取ip地址列表
        print_r($hosts);           //输出数组
//
        $protocol = 'tcp';        //定义协议名称
        $get_prot = getprotobyname($protocol);   //返回协议号
        if ($get_prot == -1)       //如果找不到
        {
            echo 'invalid protocol';      //输出错误信息
        } else {
            echo 'protocol #' . $get_prot;     //输出相应的协议号
        }
//
        $protocol_num = '6';       //定义协议号
        $get_prot = getprotobynumber($protocol_num);  //返回协议名称
        if ($get_prot == -1)       //如果找不到
        {
            echo 'invalid protocol';      //输出错误信息
        } else {
            echo 'protocol #' . $get_prot;     //输出相应的协议名称
        }
    }
}


class Ping
{

    public function demo()
    {
        $ping = new self();
        $host = '127.0.0.1';
        $port = 80;
        $num = 10;
        echo 'Pinging ' . $host . ' [' . gethostbyname($host) . '] with Port:' . $port . ' of data:' . "\r\n";
        ob_flush();
        flush();
        for ($i = 0; $i < $num; $i++) {
            echo $ping->send($host, $port);
            ob_flush();
            flush();
            sleep(1);
        }
    }


    private function microtime_float()
    {
        list($usec, $sec) = explode(" ", microtime());
        return ((float)$usec + (float)$sec);
    }

    public function getsoft($host, $port)
    {
        $fp = @fsockopen($host, $port, $errno, $errstr, 3);
        if (!$fp) return 'unknown';
        $get = "GET / HTTP/1.1\r\nHost:" . $host . "\r\nConnection: Close\r\n\r\n";
        @fputs($fp, $get);
        $data = '';
        while ($fp && !feof($fp))
            $data .= fread($fp, 1024);
        @fclose($fp);
        $array = explode("\n", $data);
        $k = 2;
        for ($i = 0; $i < 20; $i++) {
            if (stristr($array[$i], 'Server')) {
                $k = $i;
                break;
            }
        }
        if (!stristr($array[$k], 'Server')) return 'unknown';
        else return str_replace('Server', '服务器软件', $array[$k]);
    }

    public function send($host, $port)
    {
        $time_start = $this->microtime_float();
        $ip = gethostbyname($host);
        $fp = @fsockopen($host, $port, $errno, $errstr, 1);
        if (!$fp) return 'Request timed out.' . "\r\n";
        $get = "GET / HTTP/1.1\r\nHost:" . $host . "\r\nConnection: Close\r\n\r\n";
        @fputs($fp, $get);
        @fclose($fp);
        $time_end = $this->microtime_float();
        $time = $time_end - $time_start;
        $time = ceil($time * 1000);
        return 'Reply from ' . $ip . ': time=' . $time . 'ms';
    }
}

class Health
{
    public static $status;

    public function __construct()
    {
    }

    public function check($ip, $port)
    {
        $sock = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
        socket_set_nonblock($sock);
        socket_connect($sock, $ip, $port);
        socket_set_block($sock);
        self::$status = socket_select($r = array($sock), $w = array($sock), $f = array($sock), 5);
        return (self::$status);
    }

    public function checklist($lst)
    {
    }

    public function status()
    {
        switch (self::$status) {
            case 2:
                echo "Closed\n";
                break;
            case 1:
                echo "Openning\n";
                break;
            case 0:
                echo "Timeout\n";
                break;
        }
    }
}

//$ip = '192.168.3.1';
//$port = 80;
//$health = new Health();
//$health->check($ip, $port);
//$health->status();
