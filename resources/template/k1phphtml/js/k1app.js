$(document).foundation();

$(function () {
    // Menu active node opeer
    if (!$('li.active').parent().is($('[data-accordion-menu]'))) {
        $('[data-accordion-menu]').foundation('toggle', $('li.active').parent());
    }
});