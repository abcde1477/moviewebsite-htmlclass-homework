let listeningMovieButton = LastOrNext => {
    let data ={
        sort_by:"rating",
        sort_order:"decrease",
    }

    //从html中读取设置pageNum
    //从html中读取设置sort_by     通过没有提交键的表单
    //从html中读取设置sort_order  通过没有提交键的表单

    let pageNum = 0;
    if(LastOrNext ==='Next') {
        data.from = 1 + pageNum * 8;
        data.to = 8 + pageNum * 8;
    }else{
        data.from = 1 - pageNum * 8;
        data.to = 8 - pageNum * 8;
    }


    fetch("/public/getMovie.php", {
        method: 'post',
        body: data
    }).then(data => data.json())
        .then(jsData => {
            if(jsData['errorMessage'] === 'NoError')
            {
/*
            这里写获取HTML元素

*/

                for (let i = 0; i < 8; i++) {
                    jsData['movies'][i]['movie_name'];
                    jsData['movies'][i]['attribution'];
                    jsData['movies'][i]['rating'];
                    jsData['movies'][i]['movie_content'];
                    jsData['movies'][i]['releaseTime'];
                    //jsData['movies'][i]['photots'];


                    //写入元素内

                    if(i < jsData['movies'].length){
                        //空信息写入元素内
                    }

                }


                //在这里将pageNum写入html


            }else if(jsData['errorMessage'] === 'NoFound'){
                alert('没有了');
            }
        });
}

