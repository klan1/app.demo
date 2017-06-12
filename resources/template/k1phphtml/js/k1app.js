$(document).foundation();

$(function () {
    // Menu active node opeer
    if (!$('li.active').parent().is($('[data-accordion-menu]'))) {
        if ($('li.active').parent().size() !== 0) {
            $('[data-accordion-menu]').foundation('toggle', $('li.active').parent());
        }
    }
    if ($(".datepicker").size() !== 0) {
        $(".datepicker").datepicker({
            changeMonth: true,
            changeYear: true,
            dateFormat: "yy-mm-dd",
            showButtonPanel: true,
        });
    }
});