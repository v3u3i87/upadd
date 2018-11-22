<?php

namespace Upadd\Bin\Session;

use Upadd\Bin\UpaddException;

class Load
{

    public static function setFileType($config)
    {
        if ($config['domain']) {
            ini_set('session.cookie_domain', $config['domain']);
        }
        if ($config['expire']) {
            ini_set('session.gc_maxlifetime', $config['expire']);
            ini_set('session.cookie_lifetime', $config['expire']);
        }
        if ($config['use_cookies']) {
            ini_set('session.use_cookies', $config['use_cookies'] ? 1 : 0);
        }
        if ($config['cache_limiter']) {
            session_cache_limiter($config['cache_limiter']);
        }
        if ($config['cache_expire']) {
            session_cache_expire($config['cache_expire']);
        }

        $seeion = new \Upadd\Bin\Session\SessionFile();
        session_set_save_handler(
            array($seeion, 'open'),
            array($seeion, 'close'),
            array($seeion, 'read'),
            array($seeion, 'write'),
            array($seeion, 'destroy'),
            array($seeion, 'gc')
        );
        if (session_start()) {
            return true;
        } else {
            return false;
        }
    }


}