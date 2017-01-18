<?php

return [
    /**
     * true 进程服务
     * false = 会话模式
     */
    'daemonize'=>false,

    /**
     * 判断是否开启TCP
     * true = 开启
     * false = 关闭
     */
    'is_tcp'=>true,

    /**
     * 判断是否开启HTTP
     * true = 开启
     * false = 关闭
     */
    'is_http'=>false,

    /**
     * 判断是否开启web socket
     * true = 开启
     * false = 关闭
     */
    'is_socket'=>false,

    /**
     * TCP服务参数
     */
    'tcpParam'=>[
        'worker_num' => 2,
        'max_request'=>10000,
        'log_file'=>host().'data/console/swoole.logs',
        'task_tmpdir'=>host().'data/console/task/',
        'debug_mode'=> 1,
        'daemonize' => false,
        'task_worker_num' =>4,
        'dispatch_mode'=>3,

        //收发问题
        'open_eof_check'=>true,
        'open_eof_split' => true,
        //关闭Nagle合并算法
        'open_tcp_nodelay'     =>  true,
        'package_length_type' => 'N',
        'package_length_offset' => 0,
        'package_body_offset' => 0,

        //最大包长度
        'package_max_length'=>2097152,
        'buffer_output_size' => 3145728, //1024 * 1024 * 3,
        'pipe_buffer_size' => 33554432, // 1024 * 1024 * 32,
        'package_eof'=>"\r\n\r\n",
        'backlog'=>3000,
    ],

    /**
     * http服务参数
     */
    'httpParam'=>[],

    /**
     * web socket
     */
    'webSocketParam'=>[],


];