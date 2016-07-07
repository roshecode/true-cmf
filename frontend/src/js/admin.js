+function ($) {
// $(document).ready(function () {

    let settings = {
        selector:'#editor',
        plugins: "image save",
        toolbar: "save | insertfile undo redo | styleselect | fontselect | fontsizeselect | bold italic | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link image"
        // content_css : "https://fonts.googleapis.com/css?family=Roboto+Condensed&subset=latin,cyrillic"
    };

    tinymce.init(settings);
    // $(document).on('close.fndtn.reveal', '[data-reveal]', function () {
    //     tinymce.execCommand('mceRemoveEditor',true,"textarea#expenseComments");
    // });

    // $.extend(ajax.scripts, {
    ajax.scripts = {
        editor: (node) => {
            // alert('hellow');
            tinymce.remove();
            tinymce.init(settings);
            // tinymce.mceRemoveEditor();
            // tinymce.mceAddEditor();
        },
        import: (node) => {
            // node.find('input[type=submit]').click(function (e) {
            //     e.preventDefault();
            //     // console.log(node.find('input[type=file]').val());
            //     console.log(node.serialize());
            //     // $.post(node.attr('action'), node.serialize());
            //     $.ajax({
            //         url: node.attr('action') + '?' + 'ajax=1',
            //         method: 'post',
            //         data: node.serialize(),
            //         success: function (msg) {
            //             alert(msg);
            //         }
            //     });
            // });
            node.ajaxForm({
                success: function (msg) {
                    node.append(msg);
                }
            });
        }
    };
    // });

    ajax.init();

// });
}(jQuery);