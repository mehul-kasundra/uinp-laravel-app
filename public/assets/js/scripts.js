$(document).ready(function(){
	$('.show-menu').on('click',function(){
		$(this).parent().parent().find('ul').slideToggle();
	});
});