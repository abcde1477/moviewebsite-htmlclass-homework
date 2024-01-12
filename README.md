# moviewebsite-htmlclass-homework



* 给前端JavaScript调用的接口位于private文件夹下
  *  


### 部署方法

1. 在github上下载
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
* 

1. 在javascript获取数据
   * 发送表单数据,获取响应(text或者json)
   * 
   * 使用xmlHttpRequest
     * 
   * 使用fetch
2.在javascript数据处理
   * 对于text
   * 对于json





    <form id="myForm">
      <!-- Your form fields go here -->
      <input type="text" name="username" />
      <input type="password" name="password" />
      <button type="button" onclick="submitForm()">Submit</button>
    </form>
    <script>
    function submitForm() {
      // 获取表单数据
      var formData = new FormData(document.getElementById('myForm'));
      // 创建XHR对象
      var xhr = new XMLHttpRequest();

    // 配置请求
    xhr.open('POST', 'public/getMovie.php', true);
    // 监听XHR状态变化
    xhr.onreadystatechange = function () {
    if (xhr.readyState == 4 && xhr.status == 200) {
    // 处理JSON响应
    var jsonResponse = JSON.parse(xhr.responseText);
      //console.log(jsonResponse);
      .....
      //json是一种以javscript格式存储信息的文本
      //用json将数据填入到页面上
      }
    };

    var formData = new FormData(document.getElementById('myForm'));

    xhr.send(formData);
    }
