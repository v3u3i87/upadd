<?php

namespace Upadd\Swoole\Lib;

/**
 * About:Richard.z
 * Email:v3u3i87@gmail.com
 * Blog:https://www.zmq.cc
 * Date: 2017/2/4
 * Time: 下午7:13
 * Name:
 */
use Upadd\Bin\UpaddException;

class Help
{

    public static function parseAddress($address)
    {
        $data = [];
        $type = null;
        if (false === ($data = parse_url($address))) {
            throw new UpaddException($address);
        }
        switch (strtolower($data['scheme'])) {
            case 'tcp':
            case 'unix':
                $type = SWOOLE_SOCK_TCP;
                break;
            case 'udp':
                $type = SWOOLE_SOCK_UDP;
                break;
            case 'http':
            case 'ws':
            default:
                $type = null;
        }
        $data['sock'] = $type;
        return $data;
    }

    /**
     * 获取IP
     * @return string
     */
    public static function get_local_ip()
    {
        $serverIps = swoole_get_local_ip();
        $patternArray = [
            '10\.',
            '172\.1[6-9]\.',
            '172\.2[0-9]\.',
            '172\.31\.',
            '192\.168\.'
        ];

        foreach ($serverIps as $serverIp) {
            if (preg_match('#^' . implode('|', $patternArray) . '#', $serverIp)) {
                return $serverIp;
            }
        }

        return 'unknown';
    }

}