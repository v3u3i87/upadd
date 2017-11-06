<?php
namespace Upadd\Bin\View;

use Config;

class Prompt
{


    /**
     * @return string
     */
    public static function error_html($msg = 'Whoops, looks like something went wrong.')
    {
        return '<!DOCTYPE html><html><head><meta http-equiv="Content-Type" content="text/html; charset=UTF-8"/><meta name="robots" content="noindex,nofollow" /><style>html{color:#000;background:#FFF;}body,div,dl,dt,dd,ul,ol,li,h1,h2,h3,h4,h5,h6,pre,code,form,fieldset,legend,input,textarea,p,blockquote,th,td{margin:0;padding:0;}table{border-collapse:collapse;border-spacing:0;}fieldset,img{border:0;}address,caption,cite,code,dfn,em,strong,th,var{font-style:normal;font-weight:normal;}li{list-style:none;}caption,th{text-align:left;}h1,h2,h3,h4,h5,h6{font-size:100%;font-weight:normal;}q:before,q:after{content:\'\';}abbr,acronym{border:0;font-variant:normal;}sup{vertical-align:text-top;}sub{vertical-align:text-bottom;}input,textarea,select{font-family:inherit;font-size:inherit;font-weight:inherit;}input,textarea,select{*font-size:100%;}legend{color:#000;}html{background:#eee;padding:10px}img{border:0;}#sf-resetcontent{width:970px;margin:0 auto;}.sf-reset{font:11px Verdana,Arial,sans-serif;color:#333}.sf-reset .clear{clear:both;height:0;font-size:0;line-height:0;}.sf-reset .clear_fix:after{display:block;height:0;clear:both;visibility:hidden;}.sf-reset .clear_fix{display:inline-block;}.sf-reset * html .clear_fix{height:1%;}.sf-reset .clear_fix{display:block;}.sf-reset,.sf-reset .block{margin:auto}.sf-reset abbr{border-bottom:1px dotted #000;cursor:help;}.sf-reset p{font-size:14px;line-height:20px;color:#868686;padding-bottom:20px}.sf-reset strong{font-weight:bold;}.sf-reset a{color:#6c6159;}.sf-reset a img{border:none;}.sf-reset a:hover{text-decoration:underline;}.sf-reset em{font-style:italic;}.sf-reset h1,.sf-reset h2{font:20px Georgia,"Times New Roman",Times,serif}.sf-reset h2 span{background-color:#fff;color:#333;padding:6px;float:left;margin-right:10px;}.sf-reset .traces li{font-size:12px;padding:2px 4px;list-style-type:decimal;margin-left:20px;}.sf-reset .block{background-color:#FFFFFF;padding:10px 28px;margin-bottom:20px;-webkit-border-bottom-right-radius:16px;-webkit-border-bottom-left-radius:16px;-moz-border-radius-bottomright:16px;-moz-border-radius-bottomleft:16px;border-bottom-right-radius:16px;border-bottom-left-radius:16px;border-bottom:1px solid #ccc;border-right:1px solid #ccc;border-left:1px solid #ccc;}.sf-reset .block_exception{background-color:#ddd;color:#333;padding:20px;-webkit-border-top-left-radius:16px;-webkit-border-top-right-radius:16px;-moz-border-radius-topleft:16px;-moz-border-radius-topright:16px;border-top-left-radius:16px;border-top-right-radius:16px;border-top:1px solid #ccc;border-right:1px solid #ccc;border-left:1px solid #ccc;overflow:hidden;word-wrap:break-word;}.sf-reset li a{background:none;color:#868686;text-decoration:none;}.sf-reset li a:hover{background:none;color:#313131;text-decoration:underline;}.sf-reset ol{padding:10px 0;}.sf-reset h1{background-color:#FFFFFF;padding:15px 28px;margin-bottom:20px;-webkit-border-radius:10px;-moz-border-radius:10px;border-radius:10px;border:1px solid #ccc;}</style></head><body><div id="sf-resetcontent" class="sf-reset"><h1>'.$msg.'</h1></div></body></html>';
    }

    /**
     * @param string $title
     * @return string
     */
    public static function error_jump_html($title = 'Sorry, the site now can not be accessed.')
    {
        $url = Config::get('error@http_url_error');
        $h = <<<HTML
<!DOCTYPE html>
<html lang="zh-CN">
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
        return $h;
    }

    /**
     * @param string $title
     * @return \json
     */
    public static function error_json($title = 'Sorry, the site now can not be accessed.')
    {
        return json($title);
    }


}