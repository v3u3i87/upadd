
1.请访问github获取upadd代码
```
https://github.com/v3u3i87/upadd
```

2.使用git克隆一份代码到本地
```
git clone https://github.com/v3u3i87/upadd.git 

```
3.配置运行需要的文件

###start.php 
	
	该文件是配置程序启动的必要文件，其中配置形式是以一个数组包开始的，具体参数以下说明

environment 字段为多环境配置信息

**参考:**

```php
	'environment'=>[
		/**
		 本地环境,其中的hostNmaeA或是hostNmaeB为主机名称,多个名称就是多个用户或是电脑		
		*/
		'local'=>['hostNmaeA','hostNmaeB'],
		//测试环境,同上规则-读取配置/config/develop/
		'develop'=>['dev_host_name_a','dev_host_name_b'],
		//该项环境为直接读取/config/目录下的配置文件
		'productionHostNmae'
	],

```

目前没有开启,作为系统预留字段
```
//字段为自定义命令空间系列
is_autoload 

//命名空间辐射关系
autoload 

//全局别名设置
is_alias 

//全局别名设置路径设置
alias 

//排除配置文件以外的定义文件
exclude_config 
```

cli_action_autoload 使用命令行时,需要设置控制器命名空间路径

以下为入口文件常量：APP_ROUTES 设置为false时启用
```
//定义控制器获取参数名如x.php?u=index
set_action =u　
//定义控制器内的方法获取参数名如x.php?u=index&p=main
set_function =p 
```

###routing.php

	路由配置文件,里面设置具体的路由格式

使用该配置方式,需要在入口文件定义常量APP_ROUTES为true

目前仅支持一个入口文件,启用该功能

###extend.php

	暂时没有使用

###filters.php

	配合路由使用的过滤器

###database.php

	数据库配置文件

```php
return [

	'db'=>[
		//数据库类库,默认使用pdo
		'type'=>'pdo_mysql',
		//数据库链接地址
        'host'=>'127.0.0.1',
        //用户名
        'user'=>'root',
        //密码
        'pass'=>'123456',
        //数据库名
        'name'=>'upadd',
        //判断
        'port'=>3306,
        //编码
        'charset'=>'UTF8',
        //统一前置
        'prefix'=>'up_',
	],

];
```
