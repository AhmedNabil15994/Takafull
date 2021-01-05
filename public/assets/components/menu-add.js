$(function(){
	$('#SubmitBTN').on('click',function(){
		$('input[name="status"]').val(1);
		$('form').submit();
	});
	$('#SaveBTN').on('click',function(){
		$('input[name="status"]').val(0);
		$('form').submit();
	});
	$('.Reset').on('click',function(){
		$('form input').val('');
		$('#kt_summernote_1').summernote('code', '');
		$('form textarea').val('');
	});
	$('.pageReset').on('click',function(){
		location.reload();
	})
});