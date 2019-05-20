$('#customreTabs a[href="#finance-info"]').click(function (e) {
    e.preventDefault()
    $(this).tab('show')
})

$('#customreTabs a[href="#contact-info"]').click(function (e) {
    e.preventDefault()
    $(this).tab('show')
})

$(function () {
    /*if (window.location.search.replace( '?', '')){
        $('.js-invoice-filter').css('display', 'block');
    }*/
    $('#modal-edit').modal('show');

    $('.js-btn-edit-invoice').click(function (e) {
        e.preventDefault();
        $('.js-input-edit-invoice').val($("*[data-element='" + $(this).data('id') + "']").text())
    })

    var display = false;
    $('.js-show-invoice-filter').on('click', function (e) {
        e.preventDefault();
        var text = $(this).text();

        $('.js-show-invoice-filter').text((text == 'Show filter') ? 'Hide filter' : 'Show filter');

        let value = $('.js-show-invoice-filter').text();

        if (value == 'Hide filter') {
            $('.js-invoice-filter').css('display', 'block');
        } else {
            $('.js-invoice-filter').css('display', 'none');
        }

    })
});

function bs_input_file() {
    $(".input-file").before(
        function() {
            if ( ! $(this).prev().hasClass('input-ghost') ) {
                var element = $("<input type='file' class='input-ghost' style='visibility:hidden; height:0'>");
                element.attr("name",$(this).attr("name"));
                element.change(function(){
                    element.next(element).find('input').val((element.val()).split('\\').pop());
                });
                $(this).find("button.btn-choose").click(function(){
                    element.click();
                });
                $(this).find("button.btn-reset").click(function(){
                    element.val(null);
                    $(this).parents(".input-file").find('input').val('');
                });
                $(this).find('input').css("cursor","pointer");
                $(this).find('input').mousedown(function() {
                    $(this).parents('.input-file').prev().click();
                    return false;
                });
                return element;
            }
        }
    );
}
$(function() {
    bs_input_file();
});

