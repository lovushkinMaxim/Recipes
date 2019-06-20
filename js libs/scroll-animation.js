export default function (){
    scrollAnimation($('[data-animation]'))
}

function scrollAnimation(animatedEls) {
    animatedEls.css('visibility', 'hidden');
    var hideEls = animatedEls;
    var animationNameDefault = 'fadeIn';
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
        showEls.sort(sortElements).each(function (index) {
            let animationName = $(this).data('animation')?$(this).data('animation'):animationNameDefault;
            delay = $(this).data('delay')?$(this).data('delay'):delay;
            $(this).css('visibility', 'visible');
            $(this).css('animation-delay', delay*index+'ms');
            $(this).addClass('animated '+ animationName);
        })
    }).scroll();
}

function sortElements(a,b) {
    if (a > b) return 1;
    if (a < b) return -1;
    return 1
}