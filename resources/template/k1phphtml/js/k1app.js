$(document).foundation();

$(function () {
//     Menu active node opeer
    if (!$('li.active').parent().is($('[data-accordion-menu]'))) {
//        if ($('li.active').parent().size() !== 0) {
            $('[data-accordion-menu]').foundation('toggle', $('li.active').parent());
//        }
    }
});

$(function () {
    $('[k1lib-data-datepickup]').fdatepicker({
//        initialDate: '',
        format: 'yyyy-mm-dd',
        disableDblClickSelection: true,
        leftArrow: '<<',
        rightArrow: '>>',
        closeIcon: 'X',
        closeButton: true,
        language: 'es'
    });
});

