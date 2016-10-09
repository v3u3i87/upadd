# UpAdd 架构设计

```
Application      默认应用目录（可以设置）
├─config         配置模块(各个开发环境,路由,数据库等各种配置信息)
│  ├─dev         开发环境
│  ├─local       本地环境
│  ├─pro         线上环境
│  ├─routing     路由
├─console        控制台
├─data           日志(api请求日志等)
├─docs           框架文档
├─extend         第三方集成代码
├─logic          中间逻辑层(业务处理)
├─server         数据库model
├─vendor         底层代码
开发模块
├─manage         后台开发模块
├─front          前端接口模块
```


