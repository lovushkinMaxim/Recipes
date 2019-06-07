export default function (){
    scrollAnimation($('[data-animation]'))
}

function scrollAnimation(animatedEls) {
    animatedEls.css('visibility', 'hidden');
    var hideEls = animatedEls;
    var animationName = 'bounceInUp';
    var delay = 100;

    $(window).scroll(function() {
        hideEls = hideEls.filter(function () {
            return $(this).css('visibility') == 'hidden';
        });

        var scroll = $(window).scrollTop();
        var wHeight = $(window).height();
        var showEls = hideEls.filter(function () {
            var top = $(this).offset().top;
            var height = $(this).height();
            return scroll + wHeight > top + (height * 0.6)
        });

        showEls.each(function (index) {
                animationName = $(this).data('animation')?$(this).data('animation'):animationName;
                delay = $(this).data('delay')?$(this).data('delay'):delay;
                $(this).css('visibility', 'visible');
                $(this).css('animation-delay', delay*index+'ms');
                $(this).addClass('animated '+ animationName);
        })
    }).scroll();
}

