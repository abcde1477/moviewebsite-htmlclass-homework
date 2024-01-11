const user = {
    username: "User123",
    email: "user@example.com",
    // 其他用户信息...
};

// 获取登录链接元素
const loginLink = document.getElementById("loginLink");

// 点击登录链接事件
loginLink.addEventListener("click", function (event) {
    event.preventDefault(); // 阻止默认行为（这里是防止链接跳转）

    // 假设这里有登录逻辑，比如弹出登录框、输入用户名密码等，然后完成登录...

    // 模拟登录成功后更新界面
    const loginInfo = document.getElementById("loginInfo");
    const userInfo = document.createElement("div");
    userInfo.classList.add("user-info");

    const userImgDiv = document.createElement("div");
    userImgDiv.classList.add("user-img");
    const userImg = document.createElement("img");
    userImg.src = "css/images/111.jpg";
    userImg.alt = "movie";
    userImgDiv.appendChild(userImg);

    const userDetailsDiv = document.createElement("div");
    userDetailsDiv.classList.add("user-details");
    const userName = document.createElement("div");
    userName.classList.add("user-name");
    userName.innerText = `用户名: ${user.username}`;
    const userEmail = document.createElement("div");
    userEmail.innerText = `邮箱: ${user.email}`;
    const logoutLink = document.createElement("a");
    logoutLink.href = "/logout";
    logoutLink.innerText = "注销";
    userDetailsDiv.appendChild(userName);
    userDetailsDiv.appendChild(userEmail);
    userDetailsDiv.appendChild(logoutLink);

    userInfo.appendChild(userImgDiv);
    userInfo.appendChild(userDetailsDiv);

    loginInfo.innerHTML = ""; // 清空原有内容
    loginInfo.appendChild(userInfo);
});