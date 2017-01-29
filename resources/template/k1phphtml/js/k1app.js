$(document).foundation();

$(function () {
    // Menu active node opeer
    $('[data-accordion-menu]').foundation('toggle', $('li.active').parent());    
});