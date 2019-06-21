export default function (){
    scrollAnimation($('[data-animation]'))
}

function scrollAnimation(animatedEls) {
    animatedEls.css('visibility', 'hidden');
    var hideEls = animatedEls;
    var animationNameDefault = 'fadeIn';
    var delay = 300;
    var timeout = false;

    $(window).scroll(function() {
        if (timeout !== false) {
            clearTimeout(timeout);
        }

        timeout = setTimeout(function() {
            hideEls = hideEls.filter(function () {
                return $(this).css('visibility') == 'hidden';
            });

            var scroll = $(window).scrollTop();
            var wHeight = $(window).height();
            var showEls = hideEls.filter(function () {
                let res = false;
                var top = $(this).offset().top;
                var height = $(this).height();
                if (
                    scroll + wHeight > top + (height * 0.4)
                    && scroll < top + (height * 0.4)
                ){
                    res = true;
                }
                return res
            });

            showEls.sort(sortElements).each(function (index) {
                let animationName = $(this).data('animation')?$(this).data('animation'):animationNameDefault;
                delay = $(this).data('delay')?$(this).data('delay'):delay;
                $(this).css('visibility', 'visible');
                $(this).css('animation-delay', delay*index+'ms');
                $(this).addClass('animated '+ animationName);
            })
        }, delay);


    }).scroll();
}

function sortElements(a,b) {
    a = $(a).index();
    b = $(b).index();
    if (a > b) return 1;
    if (a < b) return -1;
    return 1
}