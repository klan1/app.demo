
jQuery(document).ready(function($){
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
	//following lines make smooth-scroll for internal links
    $('a[href*="#"]:not([href="#"])').click(function() {
    if (location.pathname.replace(/^\//,'') == this.pathname.replace(/^\//,'') && location.hostname == this.hostname) {
        var target = $(this.hash);
        target = target.length ? target : $('[name=' + this.hash.slice(1) +']');
        if (target.length) {
          $('html, body').animate({
            scrollTop: target.offset().top
          }, 1000);
          return false;
        }
      }
    });
    //search field control
    $('.searchform').submit(function(e){
    	if($('#search').val().length>0){
    	
        } else {
    		e.preventDefault();
    		$('#search').addClass('active');
    	}
    });
    //clone and duplicate title to hide/show it responsively 
    $('.cards-section h2').each(function(){
        tt = $(this).clone();
        $(this).parent().parent().find('.header-left').prepend(tt);
    });
    //Each item with the .has-popup class is checked
    //if it also has a data-pupup-id attribute then the element
    //with that id is relocated as a popup within the item.
    $('.has-popup').each(function(){
        if($(this).attr('data-popup-id').length>0){
            var popupid = '#' + $(this).attr('data-popup-id');
            var content = $(popupid);
            $(this).prepend(content);
            $(this).children(popupid).addClass('popup');
            $(this).hover(function(){
                    $(this).children(popupid).show(0);
                }, function(){
                    $(this).children(popupid).hide(0);
            });
        };
    });
    //implements select input using links and a hidden field
    $('.orientation .horizontal,.orientation .vertical').click(function(e){
        e.preventDefault();
        if(!$(this).hasClass('selected')){
            $(this).addClass('selected').siblings().removeClass('selected');
            var orientation_value = $(this).data('orientation');
            $('#users-data .card-orientation').val(orientation_value);
        }
    });
    // hide/show thumbnails for categories
    function select_items_from(cat) {
        $('.categories .carousel a[href$="'+cat+'"]').click(function(e){
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
    th = $('.thumb-set .preview-box').css('height');
    $('.thumb-set .preview-box').prepend('<div class="overlay-table"><div class="overlay-cell"><img src="' + img_dir_url + 'mglass.png"></div></div>');
    $('.overlay-table').css('height',th);
    
    //.seemore and .seeall buttons toggle visible .preview-box hidden items
    $('.seemore').click(function(e){
        e.preventDefault();
        $(this).parent().siblings('.preview-box').removeClass('hidden');
        $(this).siblings('.seeall').removeClass('hidden');
        $(this).addClass('hidden');
    });
});