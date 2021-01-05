function deleteItem($id) {
    Swal.fire({
        title: "هل متأكد من هذا الحذف ؟",
        text: "لا يمكنك التراجع عن هذه الخطوة!",
        type: "warning",
        showCancelButton: true,
        confirmButtonColor: "#DD6B55",
        confirmButtonText: "تأكيد",
        cancelButtonText: "الغاء",
        closeOnConfirm: false
    }).then(function(result){
        if (result.value) {
            Swal.fire("تم الحذف بنجاح!", "تم العملية بنجاح.", "success");
            $.get('/backend/blockedUsers/delete/' + $id,function(data) {
                if (data.status.original.status.status == 1) {
                    successNotification(data.status.original.status.message);
                    setTimeout(function(){
                        $('#kt_datatable').DataTable().ajax.reload();
                    },2500)
                } else {
                    errorNotification(data.status.original.status.message);
                }
            });
        } else if (result.dismiss === "cancel") {
            Swal.fire(
                "تم الالغاء",
                "تم الالغاء بنجاح :)",
                "error"
            )
        }
    });
}

$('.quickEdit').on('click',function(e){
    e.preventDefault();
    e.stopPropagation();

    $(this).toggleClass('opened');
    var myDataObjs = [];
    var i= 190;
    $(document).find('table tbody tr td.edits').each(function(index,item){
        var oldText = '';
        i++;
        if($('.quickEdit').hasClass('opened')){
            var myText = $(item).find('a.editable').text();
            $(item).find('a.editable').hide();
            var myElem = '<span qe="scope">'+
                            '<span>'+
                                '<input type="text" class="form-control" qe="input" value="'+myText+'"/>'+
                            '</span>'+
                        '</span>';
            if($(this).hasClass('dates')){
                myElem = '<span qe="scope">'+
                            '<span>'+
                                '<input type="text" class="form-control datetimepicker-input" id="kt_datetimepicker_'+i+'" value="'+myText+'" data-toggle="datetimepicker" data-target="#kt_datetimepicker_'+i+'"'+
                            '</span>'+
                        '</span>';
            }
            $(item).append(myElem);
            oldText = myText;
        }else{
            var myText = $(item).find('input.form-control').val();
            $(item).children('span').remove();
            oldText = $(item).find('a.editable').text();
            $(item).find('a.editable').text(myText);
            $(item).find('a.editable').show();
            if(myText != oldText){
                var myCol = $(item).find('a.editable').data('col');
                var myValue = myText;
                var myId = $(item).find('a.editable').data('id');
                myDataObjs.push([myId,myCol,myValue]);
            }

        }
    });

    $('td.dates span span input.datetimepicker-input').datetimepicker({
        format: 'YYYY-MM-DD H:m:s',
    });
    
    if(myDataObjs[0] != null){
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            type: 'POST',
            url: '/backend/blockedUsers/fastEdit',
            data:{
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'data': myDataObjs,
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

$('#kt_datetimepicker_7_1').datetimepicker({
    format: 'YYYY-MM-DD'
});
$('#kt_datetimepicker_7_2').datetimepicker({
    useCurrent: false,
    format: 'YYYY-MM-DD'
});

$('#kt_datetimepicker_7_1').on('change.datetimepicker', function (e) {
    $('#kt_datetimepicker_7_2').datetimepicker('minDate', e.date);
});
$('#kt_datetimepicker_7_2').on('change.datetimepicker', function (e) {
    $('#kt_datetimepicker_7_1').datetimepicker('maxDate', e.date);
});
