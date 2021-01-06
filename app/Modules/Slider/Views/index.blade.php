{{-- Extends layout --}}
@extends('Layouts.master')
@section('title','الاسلايدر')

@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/default-skin.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/photoswipe.css') }}">
@endsection

{{-- Content --}}
@section('content')
<div class="py-2 py-lg-6 subheader-transparent" id="kt_subheader">
    <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-1">
            <!--begin::Page Heading-->
            <div class="d-flex align-items-baseline flex-wrap mr-5">
                <!--begin::Page Title-->
                <h3 class="text-dark font-weight-bold my-1 mr-5 m-subheader__title--separator">الاسلايدر</h3>
                <!--end::Page Title-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                    <li class="breadcrumb-item">
                        <a href="{{ URL::to('/backend/dashboard') }}" class="text-muted"><i class="m-nav__link-icon la la-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ URL::to('/backend/sliders') }}" class="text-muted">الاسلايدر</a>
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
            <div class="main-menu dropdown dropdown-inline">
                <button type="button" class="btn btn-light-primary btn-icon btn-sm" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                    <i class="ki ki-bold-more-hor"></i>
                </button>
                <div class="dropdown-menu" dropdown-toggle="hover">
                    @if(\Helper::checkRules('add-slider'))
                    <a href="{{ URL::to('/backend/sliders/add') }}" class="dropdown-item">
                        <i class="m-nav__link-icon fa fa-plus"></i>
                        <span class="m-nav__link-text">اضافة</span>
                    </a>
                    @endif
                    @if(\Helper::checkRules('sort-slider'))
                    <a href="{{ URL::to('/backend/sliders/arrange') }}" class="dropdown-item">
                        <i class="m-nav__link-icon fa fa-sort-numeric-up"></i>
                        <span class="m-nav__link-text">ترتيب</span>
                    </a>
                    @endif
                    @if(\Helper::checkRules('charts-slider'))
                    <a href="{{ URL::to('/backend/sliders/charts') }}" class="dropdown-item">
                        <i class="m-nav__link-icon flaticon-graph"></i>
                        <span class="m-nav__link-text">الاحصائيات</span>
                    </a>
                    @endif
                    <div class="dropdown-divider"></div>
                    <div href="#" class="dropdown-item">
                        <a href="{{ URL::to('/backend/logout') }}" class="btn btn-outline-danger m-btn m-btn--pill m-btn--wide btn-sm">تسجيل الخروج</a>
                    </div>
                </div>
            </div>
            <!--end::Dropdown-->
        </div>
        <!--end::Toolbar-->
    </div>
</div>
<!--begin::Card-->
<div class="card card-custom">
    <div class="card-header">
        <div class="card-title">
            <span class="card-icon">
                <i class="menu-icon flaticon-menu-1"></i>
            </span>
            <h3 class="card-label">الاسلايدر</h3>
        </div>
        <div class="card-toolbar">
            @if(\Helper::checkRules('edit-slider'))
            <a href="#" class="btn btn-outline-success quickEdit m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill " data-toggle="tooltip" data-placement="top" data-original-title="تعديل سريع">
                <i class="la la-edit"></i>
            </a>
            @endif
            <a href="#" class="btn btn-outline-danger search-mode m-btn m-btn--icon m-btn--icon-only m-btn--custom m-btn--pill " data-toggle="tooltip" data-placement="top" data-original-title="معلومات عن البحث المتقدم">
                <i class="flaticon-questions-circular-button"></i>
            </a>
            <!--begin::Dropdown-->
            <div class="dropdown dropdown-inline mr-2">
                <button type="button" class="btn btn-light-primary font-weight-bolder dropdown-toggle" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                الاجراءات</button>
                <!--begin::Dropdown Menu-->
                <div class="dropdown-menu dropdown-menu-sm dropdown-menu-right">
                    <ul class="nav flex-column nav-hover">
                        <li class="nav-header font-weight-bolder text-uppercase text-primary pb-2">خيارات التصدير</li>
                        <li class="nav-item">
                            <a href="#" class="nav-link print-but">
                                <i class="nav-icon la la-print"></i>
                                <span class="nav-text">Print</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link copy-but">
                                <i class="nav-icon la la-copy"></i>
                                <span class="nav-text">Copy</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link excel-but">
                                <i class="nav-icon la la-file-excel-o"></i>
                                <span class="nav-text">Excel</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link csv-but">
                                <i class="nav-icon la la-file-text-o"></i>
                                <span class="nav-text">CSV</span>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="#" class="nav-link pdf-but">
                                <i class="nav-icon la la-file-pdf-o"></i>
                                <span class="nav-text">PDF</span>
                            </a>
                        </li>
                    </ul>
                </div>
                <!--end::Dropdown Menu-->
            </div>
            <!--end::Dropdown-->
        </div>
    </div>
    <div class="card-body">
        <div class="accordion accordion-solid accordion-toggle-arrow" id="accordionExample6">
            <div class="card ">
                <div class="card-header" id="headingTwo6">
                    <div class="card-title collapsed" data-toggle="collapse" data-target="#collapseTwo6" aria-expanded="false">
                        <i class="flaticon-search-1"></i>البحث المتقدم
                    </div>
                </div>
                <div id="collapseTwo6" class="collapse" data-parent="#accordionExample6" style="">
                    <div class="card-body">
                        <form class="m-form m-form--fit m--margin-bottom-20" method="get" action="{{ URL::current() }}">
                            {{-- @csrf --}}
                            <div class="row">
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <label>ID:</label>
                                    <input type="text" class="form-control m-input" data-col-index="0" name="id" value="{{ Request::get('id') }}">
                                    <br>
                                </div>  
                                <div class="col-lg-3 col-md-4 col-sm-6">
                                    <label>العنوان عربي:</label>
                                    <input type="text" class="form-control m-input" data-col-index="2" name="title" value="{{ Request::get('title') }}">
                                    <br>
                                </div>
                            </div>
                            <div class="m-separator m-separator--md m-separator--dashed"></div>
                            <div class="row">
                                <div class="col-lg-12">
                                    <button class="btn btn-brand m-btn m-btn--icon" type="submit" id="m_search">
                                        <span>
                                            <i class="la la-search"></i>
                                            <span>البحث</span>
                                        </span>
                                    </button>
                                    &nbsp;&nbsp;
                                    <a href="{{ URL::to('/backend/sliders') }}" class="btn btn-secondary m-btn m-btn--icon" id="m_reset">
                                        <span>
                                            <i class="la la-close"></i>
                                            <span>الغاء</span>
                                        </span>
                                    </a>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <input type="hidden" name="data-area" value="{{ \Helper::checkRules('edit-slider') }}">
        <input type="hidden" name="data-cols" value="{{ \Helper::checkRules('delete-slider') }}">
        <!--begin: Datatable-->
        <table class="table table-separate  table-hover table-bordered table-head-custom table-foot-custom table-checkable" id="kt_datatable">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>الصورة</th>
                    <th>العنوان عربي</th>
                    <th>الاجراءات</th>
                </tr>
            </thead>
            <tfoot>
                <tr>
                    <th>ID</th>
                    <th>الصورة</th>
                    <th>العنوان عربي</th>
                    <th>الاجراءات</th>
                </tr>
            </tfoot>
        </table>
        <!--end: Datatable-->
    </div>
</div>
<!--end::Card-->
@endsection

@section('modals')
@include('Partials.search_modal')
@include('Partials.photoswipe_modal')
@endsection

{{-- Scripts Section --}}
@section('scripts')
<script src="{{ asset('/assets/components/sliders-datatables.js')}}"></script>           
<script src="{{ asset('/assets/components/sliders.js')}}"></script>    
<script src="{{ asset('/assets/js/photoswipe.min.js') }}"></script>
<script src="{{ asset('/assets/js/photoswipe-ui-default.min.js') }}"></script>
<script src="{{ asset('/assets/components/myPhotoSwipe.js') }}"></script>       
@endsection
