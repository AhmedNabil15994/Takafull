$('#contactUs .send').on('click',function(e){
    e.preventDefault();
    e.stopPropagation();

    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
    $.ajax({
        type: 'POST',
        url: '/contactUs',
        data:{
            '_token': $('meta[name="csrf-token"]').attr('content'),
            'name': $('#contactUs input[name="name"]').val(),
            'email': $('#contactUs input[name="email"]').val(),
            'phone': $('#contactUs input[name="phone"]').val(),
            'address': $('#contactUs input[name="address"]').val(),
            'message': $('#contactUs textarea[name="message"]').val(),
        },
        success:function(data){
            if(data.status.status == 1){
                successNotification(data.status.message);
                $('#contactUs input').val('');
                $('#contactUs textarea').val('');
                $('#contactUs').modal('toggle');
            }else{
                errorNotification(data.status.message);
            }
        },
    });    
        
});


$('.sentRequest').on('click',function(e){
    e.preventDefault();
    e.stopPropagation();
    var userCheck = $('.formCard input[name="check"]').val();
    var myCheck = $('.formCard span.rand').html();
    if(userCheck != myCheck){
        errorNotification('يرجي التأكد من الرقم الظاهر امامك');
    }else{
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            type: 'POST',
            url: '/postOrder',
            data:{
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'name': $('.formCard input[name="name"]').val(),
                'identity': $('.formCard input[name="identity"]').val(),
                'phone': $('.formCard input[name="phone"]').val(),
                'address': $('.formCard input[name="address"]').val(),
                'city': $('.formCard select[name="city"]').val(),
            },
            success:function(data){
                if(data.status.status == 1){
                    successNotification(data.status.message);
                    $('.formCard input').val('');
                }else{
                    errorNotification(data.status.message);
                }
            },
        }); 
    }   
        
});
