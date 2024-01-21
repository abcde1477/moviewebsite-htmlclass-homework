﻿# moviewebsite-htmlclass-homework





### 部署方法

1. 在github上下载,尽量不要放在中文目录
2. 打开wamp，确保数据库开启。
3. 在根目录运行php服务器
4. 数据库账号密码在private/DBInit处修改。默认为 'root'/''
5. 运行login.php或register.php会自动创建数据库并添加表
6. 依次运行AutoRegister.php,AutoAddMovie.php,AutoComment.php可以自动创建数据集用于测试

注:[TestUser,666666]是AutoRegister.php生成的管理员账户，register.php注册的账户不是管理员,可在管理页面将普通用户提权



### 目录结构
  * 非测试
    * public目录下的php,是前端的javascript或者表单发送的交互接口,为可扩展性做了充足的准备
    * private是后端写的函数文件,后端运行时会调用
    * html是页面模板,后端根据html页面模板,通过修改部分数据再交付给用户
    * movie_file存放电影封面以及剧照图片
    * user_file存放用户头像
  * 测试
    * testData存放了测试数据
    * getTest存放了查询表单页面

    
 
