$(document).ready(function () {
    $().alert();


    //load intagram content
    $('.js-instagram-content').on('click', 'img', function () {
        var t = this;
        if ($(t).parent('.instagram_block').hasClass('inDb')) {
            return;
        }
        $(t).parent('.instagram_block').addClass('inDb');
        $.post("/site/ajaxAddPostFromInstagram", {id: $(t).data('id')})
                .done(function (data) {

                    data = jQuery.parseJSON(data);


                });
    });


    $('#js-instagram-content-loader').on('click', function () {
        var $btn = $(this).button('loading');
        var button = this;
        $.post("/site/ajaxInstagram", {max_tag_id: $(button).data('max_tag_id'), tag: $(button).data('tag')})
                .done(function (data) {
                    data = jQuery.parseJSON(data);
                    if (data.code == 200) {
                        $('.js-instagram-content').append(data.result.content);
                        $btn.data({'max_tag_id': data.result.max_tag_id});
                    }
                    $btn.button('reset')
                });

    });

});