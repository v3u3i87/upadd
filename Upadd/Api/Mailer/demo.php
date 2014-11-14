<?php

	    require_once UPADD_HOST.'/upadd/api/Mailer/class.phpmailer.php';
		$mail = new PHPMailer();
		$mail->CharSet = 'utf-8';//设置编码
		$address ="7569949@qq.com";
		$mail->IsSMTP(); // 使用SMTP方式发送
		$mail->Host = "smtp.exmail.qq.com"; // 您的企业邮局域名
		$mail->SMTPAuth = true; // 启用SMTP验证功能
		$mail->Username = "info@upadd.cn"; // 邮局用户名(请填写完整的email地址)
		$mail->Password = "qq0801"; // 邮局密码
		$mail->Port=25;//端口
		$mail->From = "info@upadd.cn"; //邮件发送者email地址
		$mail->FromName = "Richard.Z";
		$mail->AddAddress($address, "code");//收件人地址，可以替换成任何想要接收邮件的email信箱,格式是AddAddress("收件人email","收件人姓名")
		//$mail->AddReplyTo("", "");
		//$mail->AddAttachment("/var/tmp/file.tar.gz"); // 添加附件
		//$mail->IsHTML(true); // set email format to HTML //是否使用HTML格式
		$mail->Subject = "AAsdfsf@#$%%测试啊啊···"; //邮件标题
		$body = '<h2>测试</h2><P>你好</p>';
		$mail->Body = $body; // "Hello,这是测试邮件"; //邮件内容
		//$mail->AltBody = "This is the body in plain text for non-HTML mail clients"; //附加信息，可以省略
		
		if(!$mail->Send())
		{
			echo "邮件发送失败. <p>";
			echo "错误原因: " . $mail->ErrorInfo;
			exit;
		}else{
			echo "邮件发送成功!!";	
		}


?>