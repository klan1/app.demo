
jQuery(document).ready(function ($) {
    //slik (carousel) start and settings
    $('.carousel ul').slick({
        dots: false,
        prevArrow: '<a class="prev"><img src="//eebunny.com/ecards/resources/template/frontend/img/prev.png" alt="<<"/></a>',
        nextArrow: '<a class="next"><img src="//eebunny.com/ecards/resources/template/frontend/img/next.png" alt=">>"/></a>',
        infinite: true,
        speed: 300,
        slidesToShow: 5,
        slidesToScroll: 1,
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 3,
                    infinite: true,
                    dots: false
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 2
                }
            },
            {
                breakpoint: 480,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            } // You can unslick at a given breakpoint now by adding:  // settings: "unslick" // instead of a settings object
        ]
    });
    //slick carousel for header
    $('.header-slider').slick({
        dots: true,
        autoplay: true,
        autoplaySpeed: 5000,
        infinite: true,
        speed: 500,
        fade: true,
        cssEase: 'linear'
    });
    //following lines make smooth-scroll for internal links
    $('a[href*="#"]:not([href="#"])').click(function () {
        if (location.pathname.replace(/^\//, '') == this.pathname.replace(/^\//, '') && location.hostname == this.hostname) {
            var target = $(this.hash);
            target = target.length ? target : $('[name=' + this.hash.slice(1) + ']');
            if (target.length) {
                $('html, body').animate({
                    scrollTop: target.offset().top
                }, 1000);
                return false;
            }
        }
    });
    //search field control
//    $('.searchform').submit(function (e) {
//        if ($('#search').val().length > 0) {
////            $('#search').addClass('active');
//        } else {
//            e.preventDefault();
//            $('#search').addClass('active');
//        }
//    });
//    $('#search-btn').hover(function (e) {
//        $('#search').addClass('active');
//    })
    //clone and duplicate title to hide/show it responsively 
    /*$('.cards-section h2').each(function () {
     tt = $(this).clone();
     $(this).parent().parent().find('.header-left').prepend(tt);
     });*/
    //clone and duplicate join-button to hide/show it responsively 
    /*$('.cards-section .join-wrapper-home').each(function () {
     jj = $(this).clone();
     $(this).parent().parent().find('.header-left').apppend(jj);
     });*/

    //Each item with the .has-popup class is checked
    //if it also has a data-pupup-id attribute then the element
    //with that id is relocated as a popup within the item.
    $('.has-popup').each(function () {
        if ($(this).attr('data-popup-id').length > 0) {
            var popupid = '#' + $(this).attr('data-popup-id');
            var content = $(popupid);
            $(this).prepend(content);
            $(this).children(popupid).addClass('popup');
            $(this).hover(function () {
                $(this).children(popupid).show(0);
            }, function () {
                $(this).children(popupid).hide(0);
            });
        }
        ;
    });
    //implements select input using links and a hidden field
    $('.p-options .op1,.p-options .op2, .p-options .op3').click(function (e) {
        e.preventDefault();
        if (!$(this).hasClass('selected')) {
            $(this).addClass('selected').parent().siblings().children('a').removeClass('selected');
            if ($(this).hasClass('op1')) {
                $('#payment-data input[name="payment_option"]').val('1');
            }
            ;
            if ($(this).hasClass('op2')) {
                $('#payment-data input[name="payment_option"]').val('2');
            }
            ;
            if ($(this).hasClass('op3')) {
                $('#payment-data input[name="payment_option"]').val('3');
            }
            ;
        }
    });
    // hide/show thumbnails for categories
    function select_items_from(cat) {
        $('.categories .carousel a[href$="' + cat + '"]').click(function (e) {
            e.preventDefault();
            $('.thumb-set a').hide(0);
            $('.thumb-set a.' + cat).show(0);
        });
    }
    select_items_from('eggs');
    select_items_from('watercolor');
    select_items_from('vintage');
    select_items_from('lines');
    select_items_from('vines');

    //place a magnifying glass when pointer hover thumbnails
    /*th = $('.thumb-set .preview-box').css('height');
     $('.thumb-set .preview-box').prepend('<div class="overlay-table"><div class="overlay-cell"><img src="' + img_dir_url + 'mglass.png"></div></div>');
     $('.overlay-table').css('height', th);*/

    //.seemore and .seeall buttons toggle visibility for .preview-box hidden items
    $('.seemore').click(function (e) {
        e.preventDefault();
        $(this).parent().siblings('.preview-box').removeClass('hidden');
        $(this).siblings('.seeall').removeClass('hidden');
        $(this).addClass('hidden');
    });
    //select different modes for different buttons on #ecard-customizer
    $('#ecard-customizer #btn-preview').click(function (e) {
        $('#form-mode').attr('value', 'preview');
        $('#ecard-customizer').submit();
    });
    $('#ecard-customizer #btn-send').click(function (e) {
        $('#form-mode').attr('value', 'send');
        $('#ecard-customizer').submit();
    });
    //this enables .close-button to close their container .callout
    $('.callout .close-button').click(function (e) {
        e.preventDefault();
        $(this).parent().hide(0);
    });
    //detect text color change on "step1" page and set #form-mode hidden field accordingly
    initial_color = $('body.step1 .jscolor').css('background-color');
    //console.log(initial_color);
    $('body.step1 #ecard-customizer').on('submit', function (e) {
        //e.preventDefault();
        if ($('body.step1 .jscolor').css('background-color') !== initial_color) {
            $('#form-mode').attr('value', 'preview');
        }
    });
    //following lines trigger form submit when some imputs change.
    $('body.step1 #ecard-customizer select[name="font"]').on('change', function () {
        $('#form-mode').attr('value', 'preview');
        $('#ecard-customizer').trigger('submit');
    });
    $('body.step1 #ecard-customizer select[name="size"]').on('change', function () {
        $('#form-mode').attr('value', 'preview');
        $('#ecard-customizer').trigger('submit');
    });
    $('body.step1 #ecard-customizer .jscolor').on('change', function () {
        $('#form-mode').attr('value', 'preview');
        $('#ecard-customizer').trigger('submit');
    });
    // DATEPICKER
    $(".datepicker").datepicker({
        changeMonth: true,
        changeYear: true,
        dateFormat: "yy-mm-dd",
        showButtonPanel: true
    });
    //clones the message from a select field to a textarea field, on change
    $('select#predefined_message').on('change', function () {
        msg = $(this).children('option:selected').text();
        if (msg !== undefined) {
            $('textarea[name="user_message"]').html(msg);
        }
        ;
    });
    //preset select tag color propertie
    /*$('.eebunny-form select').css('color','#f2c3d4');
     //change select field color on focus
     $('.eebunny-form select').focus(function(){
     $(this).css('color','#b76f80');
     });
     //recover select color
     $('.eebunny-form select').blur(function(){
     if($(this).val() != $(this).children('option').eq(0).val()){
     $(this).css('color','#b76f80');
     } else {
     $(this).css('color','#f2c3d4');
     }
     });*/
});