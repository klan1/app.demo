$(document).foundation();

$(function () {
    $(".datepicker").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        showButtonPanel: true,
    });
});

function use_select_row_keys(form_obj, url_to_submit) {
    form_obj.action = url_to_submit;
    form_obj.submit();
}

function use_select_option_to_url_go(select_obj) {
    document.location = select_obj.options[select_obj.selectedIndex].value;
}