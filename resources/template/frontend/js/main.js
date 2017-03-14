
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
});