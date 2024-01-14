﻿# moviewebsite-htmlclass-homework



* 给前端JavaScript调用的接口位于private文件夹下
    *


### 部署方法

1. 在github上下载,尽量不要放在中文目录
2. 打开wamp，确保数据库开启。
3. 运行register.php && login.php
4. 第一次运行php会自动生成数据库



## 测试
* 在根目录下，有
    1. AutoRegister.php
    2. AutoAddMovie.php
    3. AutoComment.php
    *  应当按顺序。
* 运行三个脚本，可以创建数据集

* 示例异步获取信息的示例
    * register.php中。
        * register.php会发送html页面给用户。html来自html/register.html
        * register.php接收表单内容
        * private/peekName.php接收JavaScript发送的表单请求。并返回一个json。
        * 在register.php的F12处(网络/控制台)可以看到过程。
1. 在javascript获取数据
    * 发送表单数据,获取响应(text或者json)
    * 以下事先方法都在html/register.html的js代码中实现。并将获取到的json解析，并将jsData['message']的字符串写入到了id='name_imply'的html元素中
    * 使用xmlHttpRequest
        * 问GPT (javascript中，如何用xmr将表单数据发送给"handle.php",并将响应中的json内容解析?)
    * 使用fetch
        * 问GPT(javascript中，如何用fetch将表单数据发送给"handle.php",并将响应中的json内容解析?)
3. 在javascript数据处理


##可能会用到(不确定)
1. 开启php内建服务器
