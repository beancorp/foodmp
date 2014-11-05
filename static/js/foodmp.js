$(document).ready(function() {
	
    $('#block-gallery-bt').on("click", function(){
        $('#tab-nav > li').removeClass('active');
        $(this).addClass('active');
        $('.tab-gallery').removeClass('active');
        $('.block-gallery').addClass('active');
        $('#promo_page_type').val(0); 
    });

    $('#my-gallery-bt').on("click", function(){
        $('#tab-nav > li').removeClass('active');
        $(this).addClass('active');
        $('.tab-gallery').removeClass('active');
        $('.my-gallery').addClass('active');
        $('#promo_page_type').val(1);
    });
	
	 $(window).resize(function(){
    	 if($(window).width() < 767 ){
    		 $("#main-banner-mobile").show();
    		 $("#main-banner").hide();
    	 }else{
    		 $("#main-banner").show();
    		 $("#main-banner-mobile").hide();

    	 }
    });	 
	
});
