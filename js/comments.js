let comments = [
    {
        comment_content: '这是第一个评论',
        username: 'user1',
        avatar: 'css/images/movie1.jpg',
        comment_time: '2024-01-05 10:30:00'
    },
    {
        comment_content: '这是第二个评论',
        username: 'user2',
        avatar: 'css/images/movie1.jpg',
        comment_time: '2024-01-07 14:20:00'
    },
    {
        comment_content: '感觉不如圆神，我要原神启动了哈哈哈哈哈哈哈感觉不如圆神，我要原神启动了哈哈哈哈哈哈哈感觉不如圆神，我要原神启动了哈哈哈哈哈哈哈感觉不如圆神，我要原神启动了哈哈哈哈哈哈哈感觉不如圆神，我要原神启动了哈哈哈哈哈哈哈感觉不如圆神，我要原神启动了哈哈哈哈哈哈哈感觉不如圆神，我要原神启动了哈哈哈哈哈哈哈',
        username: 'user3',
        avatar: 'css/images/movie3.jpg',
        comment_time: '2024-01-09 08:45:00'
    },
    {
        comment_content: '这是第四个评论',
        username: 'user3',
        avatar: 'css/images/movie6.jpg',
        comment_time: '2024-01-09 08:45:10'
    },
];

const commentsContainer = document.getElementById('comments');



async function getCommentData(){
    await fetch('public/getComments.php')
        .then(data=>data.json())
        .then(jsData=>{
            comments = jsData;
        });
    let PromiseList = [];
    comments.forEach(comment=>{
        if(comment.id !== undefined) {
            const userDataQuery = new FormData();
            userDataQuery.append('query_id', comment.user_id)
            PromiseList.push(
                fetch("public/getUserData.php", {
                    method: 'post',
                    body: userDataQuery
                })
                    .then(data => data.json())
                    .then(jsData => {
                        comment['username'] = jsData['userdata']['user_name'];
                        comment['profile'] = jsData['userdata']['profile_url'];
                        console.log(comment);
                        return 'done';
                    })
            );
        }
    })
    await Promise.all(PromiseList);
    comments.forEach(comment => {
        const commentElement = document.createElement('div');
        commentElement.classList.add('comment');

        const userAvatar = document.createElement('img');
        userAvatar.src = comment.profile;
        userAvatar.alt = '用户头像';
        userAvatar.classList.add('avatar');
        commentElement.appendChild(userAvatar);

        const commentDetails = document.createElement('div');
        commentDetails.classList.add('comment-details');

        const username = document.createElement('p');
        username.innerText = `用户名: ${comment.username}`;
        commentDetails.appendChild(username);

        const commentText = document.createElement('p');
        commentText.innerText = comment.comment_content;
        commentDetails.appendChild(commentText);

        const timestamp = document.createElement('p');
        timestamp.innerText = `发表时间:`+comment.comment_time;
        commentDetails.appendChild(timestamp);

        commentElement.appendChild(commentDetails);
        commentsContainer.appendChild(commentElement);
    });
}
getCommentData();