window.onload = function() {
    var Images= [].slice.call(document.querySelectorAll("img.lazyloading"));

    if ("IntersectionObserver" in window) {
        var lazyImageObserver = new IntersectionObserver(function(entries, observer) {
            entries.forEach(function(entry) {
                if (entry.isIntersecting){
                    var Image= entry.target;
                    Image.src = Image.dataset.src;
                    Image.onload = function(){
                        this.classList.remove("lazyloading");
                        delete this.dataset.src;
                        delete this.dataset.srcset;
                    }
                    lazyImageObserver.unobserve(Image);
                }
            });
        });

        Images.forEach(function(Image) {
            lazyImageObserver.observe(Image);
        });
    } else {
        console.log('IntersectionObserver не пашет');
        [].forEach.call(document.querySelectorAll('img[data-src]'), function(img) {
            img.setAttribute('src', img.getAttribute('data-src'));
            img.onload = function() {
                this.classList.remove("lazyloading");
                img.removeAttribute('data-src');
            };
        });
        // дополнительный метод который будет активирован при отсутствии IntersectionObserver
    }
};