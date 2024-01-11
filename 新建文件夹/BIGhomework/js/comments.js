const comments = [
    {
        text: '这是第一个评论',
        username: 'user1',
        avatar: 'css/images/movie1.jpg',
        timestamp: '2024-01-05 10:30:00'
    },
    {
        text: '这是第二个评论',
        username: 'user2',
        avatar: 'css/images/movie1.jpg',
        timestamp: '2024-01-07 14:20:00'
    },
    {
        text: '感觉不如圆神，我要原神启动了哈哈哈哈哈哈哈感觉不如圆神，我要原神启动了哈哈哈哈哈哈哈感觉不如圆神，我要原神启动了哈哈哈哈哈哈哈感觉不如圆神，我要原神启动了哈哈哈哈哈哈哈感觉不如圆神，我要原神启动了哈哈哈哈哈哈哈感觉不如圆神，我要原神启动了哈哈哈哈哈哈哈感觉不如圆神，我要原神启动了哈哈哈哈哈哈哈',
        username: 'user3',
        avatar: 'css/images/movie3.jpg',
        timestamp: '2024-01-09 08:45:00'
    },
    {
        text: '这是第四个评论',
        username: 'user3',
        avatar: 'css/images/movie6.jpg',
        releaseTime: '2024-01-09 08:45:10'
    },
];

// 将评论按照时间戳从新到旧排序
comments.sort((a, b) => new Date(b.releaseTime) - new Date(a.releaseTime));

// 选择最新的三个评论
const latestComments = comments.slice(0, 3);

// 在页面上展示最新评论
const commentsContainer = document.getElementById('comments');
latestComments.forEach(comment => {
    const commentElement = document.createElement('div');
    commentElement.classList.add('comment');

    const userAvatar = document.createElement('img');
    userAvatar.src = comment.avatar;
    userAvatar.alt = '用户头像';
    userAvatar.classList.add('avatar');
    commentElement.appendChild(userAvatar);

    const commentDetails = document.createElement('div');
    commentDetails.classList.add('comment-details');

    const username = document.createElement('p');
    username.innerText = `用户名: ${comment.username}`;
    commentDetails.appendChild(username);

    const commentText = document.createElement('p');
    commentText.innerText = comment.text;
    commentDetails.appendChild(commentText);

    const timestamp = document.createElement('p');
    const commentTime = new Date(comment.releaseTime);
    timestamp.innerText = `发表时间: ${commentTime.toLocaleString()}`;
    commentDetails.appendChild(timestamp);

    commentElement.appendChild(commentDetails);
    commentsContainer.appendChild(commentElement);
});
