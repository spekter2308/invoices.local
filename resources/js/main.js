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
