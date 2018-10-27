<?php
namespace Upadd\Bin\Password;

/**
 * Class passwd
 * @package Upadd\Bin\Password
 */
class passwd{

    /**
     * 设置密码
     * @param $passwd 输入密码
     * @param bool $is_md5 默认md5
     * @return bool|string
     */
    public static function set($passwd, $is_md5 = false)
    {
        if ($is_md5) {
            $passwd = md5($passwd);
        }
        $newPasswd = password_hash($passwd, PASSWORD_DEFAULT);
        if ($newPasswd) {
            return $newPasswd;
        }
        return false;
    }

    /**
     * 验证密码
     * @param $passwd 密码
     * @param $checkPasswd 验证密码
     * @param bool $is_md5
     * @return bool
     */
    public static function check($passwd, $checkPasswd, $is_md5 = false) : bool
    {
        if ($is_md5) {
            $passwd = md5($passwd);
        }
        if (password_verify($passwd, $checkPasswd)) {
            return true;
        }
        return false;
    }

}