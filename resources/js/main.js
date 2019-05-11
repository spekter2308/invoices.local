$('#customreTabs a[href="#finance-info"]').click(function (e) {
    e.preventDefault()
    $(this).tab('show')
})

$('#customreTabs a[href="#contact-info"]').click(function (e) {
    e.preventDefault()
    $(this).tab('show')
})

$(function () {
    $('.js-show-invoice-filter').on('click', function (e) {
        e.preventDefault();
        var text = $(this).text();

        $('.js-invoice-filter').toggle("slow", function () {
            $('.js-show-invoice-filter').text((text == 'Show filter') ? 'Hide filter' : 'Show filter');
        });
    })
});
