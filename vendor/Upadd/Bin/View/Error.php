<?php
namespace Upadd\Bin\View;

use Config;

class Error{


    public static function html($title='Sorry, the site now can not be accessed.')
    {
        $url = Config::get('sys@http_url_error');
        $h = <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="UTF-8">
	 <meta http-equiv="refresh" content="2;url=$url" />
	<title>$title</title>
	<style type="text/css">body{margin:0;padding:0;font-family:"微软雅黑",Arial,"Trebuchet MS",Verdana,Georgia,Baskerville,Palatino,Times;font-size:16px}div{margin-left:auto;margin-right:auto}a{text-decoration:none;color:#1064A0}a:hover{color:#0078D2}img{border:none}h1,h2,h3,h4{margin:0;font-weight:normal;font-family:"微软雅黑",Arial,"Trebuchet MS",Helvetica,Verdana}h1{font-size:44px;color:#0188DE;padding:20px 0 10px 0}h2{color:#0188DE;font-size:16px;padding:10px 0 40px 0}#page{width:910px;padding:20px 20px 40px 20px;margin-top:80px;border-style:dashed;border-color:#e4e4e4;line-height:30px;background:url(sorry.png) no-repeat right}.button{width:180px;height:28px;margin-left:0;margin-top:10px;background:#009CFF;border-bottom:4px solid #0188DE;text-align:center}.button a{width:180px;height:28px;display:block;font-size:14px;color:#fff}.button a:hover{background:#5BBFFF}</style>
</head>
<body>

<div id="page">
	<h1>抱歉，找不到此页面~</h1>
	<h2>Sorry, the site now can not be accessed. </h2>
	<font color="#666666">你请求访问的页面，暂时找不到，已返回首页,谢谢！</font>
</div>
</body>
</html>
HTML;
        echo $h;
    }


    public static function json($title = 'Sorry, the site now can not be accessed.')
    {
        echo json($title);
    }



}