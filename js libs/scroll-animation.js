export default function (){
    scrollAnimation($('[data-animation]'))
}

function scrollAnimation(animatedEls) {
    animatedEls.css('visibility', 'hidden');
    var hideEls = animatedEls;
    var animationNameDefault = 'fadeIn';
    var delayDefault = 300;
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
                let delay = $(this).data('delay')?$(this).data('delay'):delayDefault;
                $(this).css('visibility', 'visible');
                $(this).css('animation-delay', delay*index+'ms');
                $(this).addClass('animated '+ animationName);
            })
        }, 100);


    }).scroll();
}

function sortElements(a,b) {
    var $a = $(a);
    var $b = $(b);
    var aTop = $a.offset().top;
    var bTop = $b.offset().top;
    if (aTop > bTop) return 1;
    if (aTop < bTop) return -1;
    var aLeft = $a.offset().left;
    var bLeft = $b.offset().left;
    if (aLeft > bLeft) return 1;
    if (aLeft < bLeft) return -1;
    return 1
}