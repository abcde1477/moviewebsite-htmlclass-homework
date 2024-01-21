$(window).scroll(function() {
    scrollFunction();
});

function scrollFunction() {
    var scrollToTopBtn = $("#scrollToTopBtn");

    // 如果页面滚动超过 300 像素，则显示返回顶部按钮，否则隐藏
    if ($(document).scrollTop() > 300) {
        scrollToTopBtn.fadeIn();
    } else {
        scrollToTopBtn.fadeOut();
    }
}

// 平滑滚动到页面顶部
$("#scrollToTopBtn").click(function() {
    $("html, body").animate({ scrollTop: 0 }, 500); // 调整时间以控制滚动速度
});