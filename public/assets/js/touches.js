$(function(){
	$('.subheader-transparent .dropdown-inline button').hover(function(){
		$(this).siblings('.dropdown-menu').addClass('show');
	});
	$('.subheader-transparent .dropdown-inline').on('mouseleave',function(){
		$(this).children('.dropdown-menu').removeClass('show');
	});
	$('.search-mode').on('click',function(e){
		e.preventDefault();
		$('#AdvancedSearchHelp').modal('toggle');
	});
	$('.SelectAllCheckBox').on('click',function(e){
		e.preventDefault();
		e.stopPropagation();
		$('input[type="checkbox"]').attr('checked','checked');
	});
	$('.UnSelectAllCheckBox').on('click',function(e){
		e.preventDefault();
		e.stopPropagation();
		$('input[type="checkbox"]').attr('checked',false);
	});
});