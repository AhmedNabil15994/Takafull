{{-- Extends layout --}}
@extends('Layouts.master')
@section('title','مجموعات المشرفين - الاحصائيات')

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
        background-color: #564FC0;
    } 
    #kt_daterangepicker_6 input{
        display: inline-block;
        width: 50px;
        padding-left: 2px;
        padding-right: 2px;
        color: #716aca !important;
        font-weight: bold;
    }
    .input-group-append{
        display: inline-block;
    }
    .input-group>.input-group-append>.input-group-text{
        display: block;
        padding: 10px 5px;
        color: #fff;
        background-color: #716aca;
        border-color: #716aca;
        width: 32px;
        height: 32px;
        transition: all ease-in-out .25s;
        border-radius: 50%;
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
        background: #716aca;
        color: #fff;
        border: 1px solid #716aca;
    }
    .daterangepicker .ranges li{
        text-align: right;
    }
    .dt-buttons.btn-group{
        display: none !important; 
    }
</style>
@endsection

@section('content')
<div class="py-2 py-lg-6 subheader-transparent" id="kt_subheader">
    <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-1">
            <!--begin::Page Heading-->
            <div class="d-flex align-items-baseline flex-wrap mr-5">
                <!--begin::Page Title-->
                <h3 class="text-dark font-weight-bold my-1 mr-5 m-subheader__title--separator">مجموعات المشرفين</h3>
                <!--end::Page Title-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                    <li class="breadcrumb-item">
                        <a href="{{ URL::to('/backend/dashboard') }}" class="text-muted"><i class="m-nav__link-icon la la-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ URL::to('/backend/groups') }}" class="text-muted">مجموعات المشرفين</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ URL::current() }}" class="text-muted">الاحصائيات</a>
                    </li>
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page Heading-->
        </div>
        <!--end::Info-->
        <!--begin::Toolbar-->
        <div class="d-flex align-items-center">
            <!--begin::Dropdown-->
            <div class='input-group' id='kt_daterangepicker_6'>
                <span class="my-title"> Today : </span>
                <input type='text' class="form-control" readonly="readonly" placeholder="Select date range" value="{{ date('M d') }} " />
                <div class="input-group-append">
                    <span class="input-group-text">
                        <i class="la la-angle-down"></i>
                    </span>
                </div>
            </div>
            <!--end::Dropdown-->
        </div>
        <!--end::Toolbar-->
    </div>
</div>
<!--begin::Cards-->
<div class="card card-custom gutter-b">
    <div class="card-body">
        <h3 class="card-label">عدد اضافة مجموعات المشرفين</h3>
        <p class="label-desc">هنا يتم عرض عدد اضافة مجموعات المشرفين في الموقع</p>
        <div id="chart_3"></div>
    </div>
</div>

<div class="card card-custom gutter-b">
    <div class="card-body">
        <h3 class="card-label">عدد تعديل مجموعات المشرفين</h3>
        <p class="label-desc">هنا يتم عرض عدد تعديل مجموعات المشرفين في الموقع</p>
        <div id="chart_1"></div>
    </div>
</div>

<div class="card card-custom gutter-b">
    <div class="card-body">
        <h3 class="card-label">عدد تعديل سريع مجموعات المشرفين</h3>
        <p class="label-desc">هنا يتم عرض عدد تعديل سريع مجموعات المشرفين في الموقع</p>
        <div id="chart_2"></div>
    </div>
</div>

<div class="card card-custom gutter-b">
    <div class="card-body">
        <h3 class="card-label">عدد حذف مجموعات المشرفين</h3>
        <p class="label-desc">هنا يتم عرض عدد حذف مجموعات المشرفين في الموقع</p>
        <div id="chart_5"></div>
    </div>
</div>

<div class="card card-custom gutter-b">
    <div class="card-body">
        <h3 class="card-label">اجمالي عمليات مجموعات المشرفين</h3>
        <p class="label-desc">هنا يتم عرض عدد جميع العمليات علي مجموعات المشرفين في الموقع</p>
        <div id="chart_13"></div>
    </div>
</div>
<!--end::Cards-->
@endsection

{{-- Scripts Section --}}
@section('scripts')
<script src="{{ URL::to('/assets/js/pages/features/charts/apexcharts.js') }}"></script>
<script src="{{ URL::to('/assets/js/pages/crud/forms/widgets/bootstrap-daterangepicker.js') }}"></script>
@endsection
