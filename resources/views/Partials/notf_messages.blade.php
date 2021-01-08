<style type="text/css" media="screen">
    div.toast-title{
        color: #FFF !important;
    }
</style>
<script>
    @if(Session::has('success'))
        setTimeout(function () {
            toastr.options = {
                "closeButton": true,
                "debug": false,
                "progressBar": true,
                "preventDuplicates": false,
                "newestOnTop": true,
                "positionClass": "toast-top-right",
                "onclick": null,
                "showDuration": "400",
                "hideDuration": "1000",
                "timeOut": "7000",
                // "extendedTimeOut": "1000",
                "showEasing": "swing",
                "hideEasing": "linear",
                "showMethod": "fadeIn",
                "hideMethod": "fadeOut",
                "icon" : false,
            };
            toastr.success('', "{{ Session::get('success') }}" );
        }, 1300);
    @endif
    @if(Session::has('error'))
     setTimeout(function () {
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "progressBar": true,
            "preventDuplicates": false,
            "newestOnTop": true,
            "positionClass": "toast-top-right",
            "onclick": null,
            "showDuration": "400",
            "hideDuration": "1000",
            "timeOut": "7000",
            // "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut",
            "icon" : false,
        };
        toastr.error('', "{{ Session::get('error') }}" );
    }, 1300);
    @endif
</script>
