$(document).ready(function(){
    let slideIndex = 1;
    showSlides(slideIndex);

    $(".prev").click(function() {
        showSlides(slideIndex -= 1);
    });

    $(".next").click(function() {
        showSlides(slideIndex += 1);
    });

    let autoSlide = setInterval(function() {
        showSlides(slideIndex += 1);
    }, 3000); // 切换间隔时间为3秒

    function showSlides(n) {
        let slides = $(".slides img");

        if (n > slides.length) {
            slideIndex = 1;
        }
        if (n < 1) {
            slideIndex = slides.length;
        }

        for (let i = 0; i < slides.length; i++) {
            $(slides[i]).hide();
        }

        $(slides[slideIndex - 1]).show();
    }
});
