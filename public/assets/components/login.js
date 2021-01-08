$(function(){
    $('#kt_login_forgot').on('click',function(e){
        e.preventDefault();
        e.stopPropagation();
        $(".login-signin").removeClass("animate__flipInX");
        $(".login-signin").addClass("animate__flipOutX");
        $(".login-signin").css("display",'none');
        $('.login-forgot').css('display','block')
        $(".login-forgot").removeClass("animate__flipOutX");
        $(".login-forgot").addClass("animate__flipInX");
    });

    $('#kt_login_forgot_cancel').on('click',function(e){
        e.preventDefault();
        e.stopPropagation();
        $(".login-forgot").removeClass("animate__flipInX");
        $(".login-forgot").addClass("animate__flipOutX");
        $(".login-forgot").css("display",'none');
        $('.login-signin').css('display','block')
        $(".login-signin").removeClass("animate__flipOutX");
        $(".login-signin").addClass("animate__flipInX");
    });

    $('#kt_login_forgot_submit').on('click',function(e){
        e.preventDefault();
        e.stopPropagation();
        var email = $('input[name="email"]').val();
        if(email){
            $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
            $.ajax({
                type: 'POST',
                url: '/backend/resetPassword',
                data:{
                    '_token': $('#kt_login_forgot_form input[type="hidden"]').val(),
                    'email': email,
                },
                success:function(data){
                    if(data.status.status == 1){
                        successNotification(data.status.message);
                    }else{
                        errorNotification(data.status.message);
                    }
                },
            });
        }
    });

});