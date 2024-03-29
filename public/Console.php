<?php
if (session_status() == PHP_SESSION_NONE) {
    session_start();
}
if(isset($_SESSION['admin_permission']) &&$_SESSION['admin_permission']){

    echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>开发测试控制台</title>
</head>
<body>
    
    <form action="logOut.php" method="post">
        <label>一键登出<input type="submit"></label>
    </form>
    <br>
    <a href="addComment.html">去添加评论</a>
    <br>
    <a href="addMovie.html"> 去添加电影</a>
    <br>
    <form action="ElevatingPrivileges.php" method="post">
        <label>给用户管理权限<input type="text" name="user_id" placeholder="输入用户id" required/></label>
        <input type="submit">
    </form>
    <br>

    <!--删除三项-->
    <form action="deleteMovie.php" method="post">
        <label>删除电影
            <input type="text" name="delete_movie_id" placeholder="输入电影id" required/>
        </label>
        <input type="submit" value="删除">
        <br>
    </form>
    <form action="deleteComment.php" method="post">
        <label>删除评论
            <input type="number" step="1" name="user_id" placeholder="输入用户id" required/>
            <input type="number" step="1" name="movie_id" placeholder="输入电影id" required/>
        </label>
        <input type="submit" value="删除">
        <br>
    </form>
    <form action="deleteUser.php" method="post">
        <label>删除用户
            <input type="text" name="delete_user" placeholder="输入用户id" required/>
        </label>
        <input type="submit" value="删除">
        <br>
    </form>

    <!--修改三项-->
    <p>修改电影</p>
    <form action="modifyMovie.php" method="post" enctype="multipart/form-data">
        <label> 电影id<input type="text" name="modify_movie"  placeholder="输入电影id" required/> </label><br>
        <label> 电影名<input type="text" name="movie_name" required/> </label><br>
        <label> 电影属性<br><textarea rows="5" cols="30" name="attribution" required></textarea></label><br>
        <label> 电影简介<br><textarea rows="5" cols="30" name="movie_content"></textarea></label><br>
        <label> 上映时间<input type="date" name="releaseTime" required/></label><br>
        <label> 电影封面<input type="file" name="cover"/></label><br>
        <label> 电影剧照<input type="file" name="photos[]" multiple="multiple"/></label><br>
        <input type="submit" value="修改电影"/><br>
    </form>
    <p>修改评论</p>
    <form action="modifyComment.php" method="post">
        <input type="number" step="1" name="modify_user" placeholder="输入用户id"/><br>
        <input type="number" step="1" name="movie_id" placeholder="输入电影id"/><br>
        <label>编辑评论内容<br>
            <textarea rows="5" cols="44" name="comment_content" required></textarea>
        </label>
        <br>
        <label>评分(0.0~10.0)
            <input type="number" name="rating" step="0.1" min="0.0" max="10.0" required/>
        </label>
        <input type="submit"/>
        <br>
    </form>
    <p>修改用户</p>
    <form action="modifyUser.php" method="post" enctype="multipart/form-data">
        <label>用户id
            <input type="text" name="modify_user" placeholder="输入用户id" required/>
        </label>
        <label> 个人简介<br><textarea rows="5" cols="30" name="homepage_content" required></textarea></label><br>

        <input type="submit" value="修改">
        <br>
    </form>

</body>
</html>';
}else{
    echo "您不是管理员，不能访问此页面<br>";
    echo '<a href="../home.php" >回到主页</a>';
}
?>



