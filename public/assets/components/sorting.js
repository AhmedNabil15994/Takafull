$('#Arrange').nestedSortable({
    handle: 'div',
    items: 'li',
    toleranceElement: '> div',
    maxLevels: 1,
    rtl: true,
    update: function (event, ui) {
        var ids = [];
        var sorts = [];
        $("#Arrange li").each(function (index, element) {
            ids.push($(element).attr("data-id"));
            sorts.push(index + 1);
        });

        var url = window.location.href;
        if(url.indexOf("#") != -1){
            url = url.replace('#','');
        }

        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });
        $.ajax({
            type:'post',
            url: url+'/sort',
            data:{
                '_token': $('meta[name="csrf-token"]').attr('content'),
                'ids': JSON.stringify(ids),
                'sorts': JSON.stringify(sorts),
            },
            success:function(data){
                if (data.status.status == 1) {
                    successNotification(data.status.message);
                } else {
                    errorNotification(data.status.message);
                }
            },
        });   
    }
});