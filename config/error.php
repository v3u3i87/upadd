<?php
return [

    /*
    * 生产环境
    */
    'debug'=>true,

    /**
     * 是否开启URL错误跳转
     * true = 开启
     * false = 关闭
     */
    'is_http_url_error'=>false,


    /**
     *  URL错误跳转
     */
    'http_url_error'=>'/',

    /**
     * 错误页面模板路径
     */
    'http_error_view_path'=>'/error/info.html',


];