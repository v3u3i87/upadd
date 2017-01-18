<?php

return [

    /**
     * true 进程服务
     * false = 会话模式
     */
    'daemonize'=>false,

    /**
     * 1 = 仅支持 tcp 模式
     * 2 = 支持 http
     * 3 = 支持 http 和 tcp,但必须开启http 才可以开启tcp
     */
    'is_mode'=>3,

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
    'is_http'=>true,

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
    'httpParam'=>[
        'dispatch_mode' => 3,

        'package_max_length' => 2097152, // 1024 * 1024 * 2,
        'buffer_output_size' => 3145728, //1024 * 1024 * 3,
        'pipe_buffer_size' => 33554432, //1024 * 1024 * 32,
        'open_tcp_nodelay' => 1,

        'heartbeat_check_interval' => 5,
        'heartbeat_idle_time' => 10,
        'open_cpu_affinity' => 1,

        'reactor_num' => 32,//建议设置为CPU核数 x 2
        'worker_num' => 40,
        'task_worker_num' => 20,//生产环境请加大，建议1000

        'max_request' => 0, //必须设置为0，否则会导致并发任务超时,don't change this number
        'task_max_request' => 4000,

        'backlog' => 3000,
        //swoole http 系统日志，任何代码内echo都会在这里输出
        'log_file'=>host().'data/console/http_swoole.logs',
        //task 投递内容过长时，会临时保存在这里，请将tmp设置使用内存
        'task_tmpdir'=>host().'data/console/task/',
        'pid_path' => host().'data/console/tmp/',
        'response_header' => array('Content_Type' => 'application/json; charset=utf-8'),
    ],

    /**
     * web socket
     */
    'webSocketParam'=>[],


];