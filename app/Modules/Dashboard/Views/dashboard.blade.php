@extends('Layouts.master')
@section('title','الصفحة الرئيسية')
@section('styles')
<style type="text/css" media="screen">
    #kt_daterangepicker_6{
        display: inline-block;
        border-radius: 2rem;
        padding: 5px;
        cursor: pointer;
        background: #fff;
        padding-bottom: 0;
        overflow: hidden;
        cursor: pointer;
    }
    #kt_daterangepicker_6:hover .input-group-text{
        background-color: #2ca189;
    } 
    #kt_daterangepicker_6 input{
        display: inline-block;
        width: 50px;
        padding-left: 2px;
        padding-right: 2px;
        color: #2ca189 !important;
        font-weight: bold;
    }
    .input-group-append{
        display: inline-block;
    }
    .input-group>.input-group-append>.input-group-text{
        display: block;
        padding: 10px 5px;
        color: #fff;
        background-color: #2ca189;
        border-color: #2ca189;
        width: 32px;
        height: 32px;
        transition: all ease-in-out .25s;
        border-radius: 50% !important;
    }
    .input-group>.form-control:not(:last-child){
        border: 0;
    }
    .input-group i{
        color: #fff;
    }
    span.my-title{
        color: #aaaeb8;
    }
    .daterangepicker .ranges {
        padding: 10px;
        margin: 5px 10px 5px 5px;
    }
    .daterangepicker .ranges li.active {
        background: #2ca189;
        color: #fff;
        border: 1px solid #2ca189;
    }
    .daterangepicker .ranges li{
        text-align: right;
    }
    .dt-buttons.btn-group{
        display: none !important; 
    }
    .m--font-brand {
        color: #9816f4 !important;
    }
    .label-brand {
        background-color: #9816f4 !important;
        color: #FFF;
    }
    .text-brand{
        color: #2ca189 !important;
    }
    .col.px-6.rounded-xl{
        background-color: #FFF !important;
    }
    .myText{
        display: block;
        color: #6f727d;
        margin-top: 1.8rem;
    }
    .myP{
        color: #7b7e8a;
    }
    .svg-icon{
        padding-top: 1.5rem;
    }
    .svg-icon i{
        font-size: 2rem;
    }
    .m--font-success {
        color: #34bfa3 !important;
    }
    .m--font-info {
        color: #36a3f7 !important;
    }
    .m--font-danger {
        color: #f4516c !important;
    }
    .timeline.timeline-6 .timeline-item .timeline-label{
        width: 100px;
    }
    .timeline.timeline-6:before{
        right: 0;
        height: 25px !important;
        top: 10px;
    }
    .tiles{
        max-height: 300px;
        overflow-y: scroll;
    }
</style>
@endsection


{{-- Content --}}
@section('content')
<div class="py-2 py-lg-6 subheader-transparent" id="kt_subheader">
    <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-1">
            <!--begin::Page Heading-->
            <!--end::Page Heading-->
        </div>
        <!--end::Info-->
        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <!--begin::Dropdown-->
            <form action="{{ URL::current() }}" class="chart-form" method="get" >
                <div class='input-group' id='kt_daterangepicker_6'>
                    <span class="my-title"> {{ \Request::has('to') ? date('M d',strtotime(Request::get('to'))).' - '  : 'Today :' }} </span>
                    <input type="hidden" name="from">
                    <input type="hidden" name="to">
                    <input type='text' class="form-control" readonly="readonly" placeholder="Select date range" value="{{ \Request::has('from') ? date('M d',strtotime(Request::get('from')))  : date('M d') }} " />
                    <div class="input-group-append">
                        <span class="input-group-text">
                            <i class="la la-angle-down"></i>
                        </span>
                    </div>
                    <div class="clearfix"></div>
                </div>
            </form>
            <!--end::Dropdown-->
        </div>
        <!--end::Toolbar-->
    </div>
</div>
<!--begin::Row-->
<div class="row">
    <div class="col-lg-6 col-xxl-6">
        <!--begin::Stats Widget 11-->
        <input type="hidden" name="chartData1" value="{{ json_encode($data->chartData1) }}">
        <input type="hidden" name="chartData2" value="{{ json_encode($data->chartData2) }}">
        <div class="card card-custom card-stretch card-stretch gutter-b">
            <!--begin::Body-->
            <div class="card-body p-0">
                <div class="d-flex align-items-center justify-content-between card-spacer flex-grow-1">
                    <h3 class="m-portlet__head-text">الاتصال بنا</h3>
                    <div class="dropdown dropdown-inline">
                        <span class="label label-danger label-wide">{{ $data->contactUsCount }}</span>
                    </div>
                </div>
                <div id="kt_stats_widget_11_chart" class="card-rounded-bottom" data-color="success" style="height: 300px"></div>
            </div>
            <!--end::Body-->
        </div>
        <!--end::Stats Widget 11-->
    </div>
    <div class="col-lg-6 col-xxl-6">
        <!--begin::Mixed Widget 1-->
        <div class="card card-custom bg-gray-100 card-stretch gutter-b">
            <!--begin::Header-->
            <div class="card-header border-0 bg-danger py-5">
                <h3 class="card-title font-weight-bolder text-white">النشاطات</h3>
                <div class="card-toolbar">
                    <div class="dropdown dropdown-inline">
                        <span class="label label-warning label-wide">{{ $data->webActionsCount }}</span>
                    </div>
                </div>
            </div>
            <input type="hidden" id="froms" value="{{ \Request::get('from') }}">
            <input type="hidden" id="tos" value="{{ \Request::get('to') }}">
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body p-0 position-relative overflow-hidden">
                <!--begin::Chart-->
                <div id="kt_mixed_widget_1_chart" class="card-rounded-bottom bg-danger" style="height: 200px"></div>
                <!--end::Chart-->
                <!--begin::Stats-->
                <div class="card-spacer mt-n25">
                    <!--begin::Row-->
                    <div class="row m-0">
                        <div class="col bg-light-warning px-6 rounded-xl mr-7 mb-7">
                            <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2">
                                <i class="flaticon-add-circular-button m--font-brand"></i>
                            </span>
                            <a href="#" class="myText font-weight-bold font-size-h6">اضافة</a>
                            <p class="myP">{{ $data->addCount }} عملية جديدة</p>
                        </div>
                        <div class="col bg-light-primary px-6 rounded-xl mb-7">
                            <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2">
                                <i class="flaticon-edit m--font-success"></i>
                            </span>
                            <a href="#" class="myText font-weight-bold font-size-h6">تعديل</a>
                            <p class="myP">{{ $data->editCount }} عملية جديدة</p>
                        </div>
                    </div>
                    <!--end::Row-->
                    <!--begin::Row-->
                    <div class="row m-0">
                        <div class="col bg-light-danger px-6 rounded-xl mr-7">
                            <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2">
                                <i class="flaticon-edit-1 m--font-info"></i>
                            </span>
                            <a href="#" class="myText font-weight-bold font-size-h6">سريع</a>
                            <p class="myP">{{ $data->fastEditCount }} عملية جديدة</p>
                        </div>
                        <div class="col bg-light-success px-6 rounded-xl">
                            <span class="svg-icon svg-icon-3x svg-icon-warning d-block my-2">
                                <i class="flaticon-delete m--font-danger"></i>
                            </span>
                            <a href="#" class="myText font-weight-bold font-size-h6">حذف</a>
                            <p class="myP">{{ $data->deleteCount }} عملية جديدة</p>
                        </div>
                    </div>
                    <!--end::Row-->
                </div>
                <!--end::Stats-->
            </div>
            <!--end::Body-->
        </div>
        <!--end::Mixed Widget 1-->
    </div>
    <div class="col-lg-12">
        <!--begin::List Widget 9-->
        <div class="card card-custom card-stretch gutter-b">
            <!--begin::Header-->
            <div class="card-header align-items-center border-1">
                <h3 class="card-title align-items-start flex-column">
                    <span class="font-weight-bolder text-dark">سجل النشاطات</span>
                </h3>
                <div class="card-toolbar">
                    <div class="dropdown dropdown-inline">
                        <span class="label label-danger label-wide">{{ $data->webActionsCount }}</span>
                    </div>
                </div>
            </div>
            <!--end::Header-->
            <!--begin::Body-->
            <div class="card-body pt-4 tiles timlines">
                @foreach($data->webActions as $action)
                <!--begin::Timeline-->
                <div class="timeline timeline-6 mt-3">
                    <!--begin::Item-->
                    <div class="timeline-item align-items-start">
                        <!--begin::Label-->
                        <!--end::Label-->
                        <!--begin::Badge-->
                        <div class="timeline-badge">
                            <i class="fa fa-genderless text-{{ $action->label }} icon-xl"></i>
                        </div>
                        <!--end::Badge-->
                        <!--begin::Text-->
                        <div class="font-weight-mormal font-size-lg timeline-content text-muted pl-3">{{ $action->username }} قام {{ $action->typeText }} بيانات في {{ $action->module_page }} <label class="label label-{{ $action->label }} label-wide label-inline">{{ $action->typeText }}</label></div>
                        <!--end::Text-->
                        <div class="timeline-label font-weight-bolder text-dark-75 font-size-lg text-muted">{{ $action->created_at2 }}</div>
                    </div>
                    <!--end::Item-->
                </div>
                <!--end::Timeline-->
                @endforeach
            </div>
            <!--end: Card Body-->
        </div>
        <!--end: List Widget 9-->
    </div>
</div>
<!--end::Row-->

@endsection

{{-- Scripts Section --}}
@section('scripts')
<script src="{{ asset('/assets/components/dashboard.js') }}"></script>
@endsection
