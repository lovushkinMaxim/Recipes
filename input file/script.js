export default {
    init: initFileWatcher
};

function initFileWatcher(){
    $('body').on('change','input[type="file"]',function(event){
        var $label = $(this).siblings('label');
        var files = event.target.files, name, type, size, date;

        var output = '';
        for (var i = 0, f; f = files[i]; i++) {
            name = encodeURI(f.name);
            size = ' (' + Math.round(f.size / 1024) + ' КБ)';
            output += name + size ;
        }

        $label.html(output);
    });
};