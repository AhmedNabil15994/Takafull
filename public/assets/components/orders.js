var myURL = window.location.href;
if(myURL.indexOf("#") != -1){
    myURL = myURL.replace('#','');
}

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
            $.get(myURL+'/delete/' + $id,function(data) {
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
        if($('.quickEdit').hasClass('opened')){
            var myText = $(item).find('a.editable').text();
            $(item).find('a.editable').hide();
            var myElem = '<span qe="scope">'+
                            '<span>'+
                                '<input type="text" class="form-control" qe="input" value="'+myText+'"/>'+
                            '</span>'+
                        '</span>';
            if($(this).hasClass('selects')){
                var selectOptions = '';
                $("select[name='status'] option").each(function(){
                    var selected = '';
                    if($(this).text() == myText){
                        selected = ' selected';
                    }
                    if($(this).val() > 0){
                        selectOptions+= '<option value="'+$(this).val()+'" '+selected+'>'+$(this).text()+'</option>';
                    }
                });
                myElem = '<span qe="scope">'+
                            '<span>'+
                                '<select class="form-control">'+
                                    selectOptions+
                                '</select>'+
                            '</span>'+
                        '</span>';
            }
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
            var myText = '';
            var newVal = 0;
            if($(this).hasClass('selects')){
                myText = $(item).find('select option:selected').text();
                newVal = $(item).find('select option:selected').val();
            }else{
                myText = $(item).find('input.form-control').val();
            }
            $(item).children('span').remove();
            oldText = $(item).find('a.editable').text();
            $(item).find('a.editable').text(myText);
            $(item).find('a.editable').show();

            if(myText != oldText){
                var myCol = $(item).find('a.editable').data('col');
                if($(this).hasClass('selects')){
                    var myValue = newVal;
                }else{
                    var myValue = myText;
                }
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
            url: myURL+'/fastEdit',
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

$('.moveToTrash').on('click',function(e){
    e.preventDefault();
    e.stopPropagation();
    if($('table tr.selected').length){
        var myArrs = [];
        $('table tr.selected').each(function(index,item){
            myArrs.push($(item).find('input[type="checkbox"]:checked').data('cols'));
        });
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            type: 'POST',
            url: myURL+'/changeStatus/7',
            data:{
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'data': myArrs,
            },
            success:function(data){
                if(data.status.original.status.status == 1){
                    successNotification(data.status.original.status.message);
                    setTimeout(function(){
                        $('#kt_datatable').DataTable().ajax.reload();
                    },2500)
                }else{
                    errorNotification(data.status.original.status.message);
                }
            },
        });
    }else{
        errorNotification('من فضلك قم باختيار الطلبات');      
    }
});

$('.delete').on('click',function(e){
    e.preventDefault();
    e.stopPropagation();
    if($('table tr.selected').length){
        var myArrs = [];
        $('table tr.selected').each(function(index,item){
            myArrs.push($(item).find('input[type="checkbox"]:checked').data('cols'));
        });
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            type: 'POST',
            url: myURL+'/delete',
            data:{
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'data': myArrs,
            },
            success:function(data){
                if(data.status.original.status.status == 1){
                    successNotification(data.status.original.status.message);
                    setTimeout(function(){
                        $('#kt_datatable').DataTable().ajax.reload();
                    },2500)
                }else{
                    errorNotification(data.status.original.status.message);
                }
            },
        });
    }else{
        errorNotification('من فضلك قم باختيار الطلبات');      
    }
});

$('.backToNew').on('click',function(e){
    e.preventDefault();
    e.stopPropagation();
    if($('table tr.selected').length){
        var myArrs = [];
        $('table tr.selected').each(function(index,item){
            myArrs.push($(item).find('input[type="checkbox"]:checked').data('cols'));
        });
        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            type: 'POST',
            url: myURL+'/changeStatus/1',
            data:{
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'data': myArrs,
            },
            success:function(data){
                if(data.status.original.status.status == 1){
                    successNotification(data.status.original.status.message);
                    setTimeout(function(){
                        $('#kt_datatable').DataTable().ajax.reload();
                    },2500)
                }else{
                    errorNotification(data.status.original.status.message);
                }
            },
        });
    }else{
        errorNotification('من فضلك قم باختيار الطلبات');      
    }
});