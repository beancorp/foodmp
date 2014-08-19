function positionFooter() {
	if (!$("#sticky-footer-push").length) {
		$("#div_footer").before('<div id="sticky-footer-push"></div>');
	}
	var docHeight = $(document.body).height() - $("#sticky-footer-push").height();
	if(docHeight < $(window).height()){
		var diff = $(window).height() - docHeight - 20;
		$("#sticky-footer-push").height(diff);
	}
}
$(window).scroll(positionFooter).resize(positionFooter).load(positionFooter);