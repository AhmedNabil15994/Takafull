{{-- Extends layout --}}
@extends('Layouts.master')
@section('title','إعدادات لوحه التحكم')
@section('styles')
<link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/default-skin.css') }}">
<link rel="stylesheet" type="text/css" href="{{ asset('/assets/css/photoswipe.css') }}">
<style type="text/css" media="screen">
    body{
        overflow-x: hidden;
    }
</style>
@endsection
{{-- Content --}}
@section('sub-header')

@endsection

@section('content')
<div class="py-2 py-lg-6 subheader-transparent" id="kt_subheader">
    <div class="container-fluid d-flex align-items-center justify-content-between flex-wrap flex-sm-nowrap">
        <!--begin::Info-->
        <div class="d-flex align-items-center flex-wrap mr-1">
            <!--begin::Page Heading-->
            <div class="d-flex align-items-baseline flex-wrap mr-5">
                <!--begin::Page Title-->
                <h3 class="text-dark font-weight-bold my-1 mr-5 m-subheader__title--separator">إعدادات لوحه التحكم</h3>
                <!--end::Page Title-->
                <!--begin::Breadcrumb-->
                <ul class="breadcrumb breadcrumb-transparent breadcrumb-dot font-weight-bold p-0 my-2 font-size-sm">
                    <li class="breadcrumb-item">
                        <a href="{{ URL::to('/backend/dashboard') }}" class="text-muted"><i class="m-nav__link-icon la la-home"></i></a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ URL::to('/backend/panelSettings') }}" class="text-muted">إعدادات لوحه التحكم</a>
                    </li>
                    <li class="breadcrumb-item">
                        <a href="{{ URL::current() }}" class="text-muted">تعديل</a>
                    </li>
                </ul>
                <!--end::Breadcrumb-->
            </div>
            <!--end::Page Heading-->
        </div>
        <!--end::Info-->
        <!--begin::Toolbar-->
        <!--end::Toolbar-->
    </div>
</div>
<!--begin::Card-->
<div class="card card-custom">
    <div class="card-header">
        <div class="card-title">
            <span class="card-icon">
                <i class="menu-icon flaticon-settings-1"></i>
            </span>
            <h3 class="card-label">تعديل</h3>
        </div>
    </div>
    <div class="card-body">
        <ul class="nav nav-tabs  m-tabs-line" role="tablist">
            <li class="nav-item m-tabs__item">
                <a class="nav-link m-tabs__link active" data-toggle="tab" href="#AddTabs" role="tab"><i class="la la-refresh"></i>حفظ</a>
            </li>
        </ul>
        <div class="tab-content">
            <div class="tab-pane active" id="AddTabs" role="tabpanel">
                <form class="forms m-form m-form--group-seperator-dashed" method="POST" action="{{ URL::to('/backend/generalSettings/update/2') }}">  
                    @csrf
                    @foreach($data->data as $item => $variable)
                    <div class="form-group m-form__group row" style="padding-right: 0;padding-left: 0;padding-bottom: 10px;">
                        <div class="col-lg-12">
                            <label class="label label-danger label-pill label-inline mr-2 {{ $item == 0 ? 'mt-15' : '' }}" style="margin-bottom: 20px;">{{ $variable->key }}</label>
                            @if($variable->var_type == 0)
                            <input class="form-control" type="number" name="value{{ $variable->id }}" value="{{ $variable->value }}" maxlength="" placeholder="">
                            @elseif($variable->var_type == 4)
                            <div class="form-group m-form__group row" style="padding-right: 0;padding-left: 0;padding-bottom: 10px;">
                                <div class="col-lg-12">
                                    <label class="label label-danger label-pill label-inline mr-2" style="margin-bottom: 20px;">الصورة</label>
                                    <div class="dropzone dropzone-default" id="kt_dropzone_1" data-area="{{ $variable->id }}">
                                        <div class="dropzone-msg dz-message needsclick">
                                            <h3 class="dropzone-msg-title"><i class="flaticon-upload-1 fa-4x"></i></h3>
                                            <span class="dropzone-msg-desc">اسحب الملفات هنا أو انقر هنا للرفع .</span>
                                        </div>
                                        @if($variable->value != '')
                                        <div class="dz-preview dz-image-preview" id="my-preview">  
                                            <div class="dz-image">
                                                <img alt="image" src="{{ $variable->value }}">
                                            </div>  
                                            <div class="dz-details">
                                                <div class="dz-size">
                                                    <span><strong>{{ \App\Models\Page::getPhotoSize($variable->value) }}</strong></span>
                                                </div>
                                                <div class="dz-filename">
                                                    {{-- <span data-dz-name="">{{ $variable->value }}</span> --}}
                                                </div>
                                                <div class="PhotoBTNS">
                                                    <div class="my-gallery" itemscope="" itemtype="" data-pswp-uid="1">
                                                       <figure itemprop="associatedMedia" itemscope="" itemtype="">
                                                            <a href="{{ $variable->value }}" itemprop="contentUrl" data-size="555x370"><i class="flaticon-search"></i></a>
                                                            <img src="{{ $variable->value }}" itemprop="thumbnail" style="display: none;">
                                                        </figure>
                                                    </div>
                                                    <a class="DeletePhoto" data-area="{{ $variable->id }}"><i class="flaticon-delete" data-name="{{ $variable->value }}" data-clname="Photo"></i> </a>                                               
                                                </div>
                                            </div>
                                        </div>
                                        @endif
                                    </div>
                                </div>
                            </div>
                            @endif
                            <span class="m-form__help LastUpdate">تم الحفظ فى :  {{ $variable->created_at }}</span>
                        </div>
                    </div>   
                    @endforeach
                </form>
            </div>
        </div>
    </div>
    <div class="card-footer">
        <div class="row">
            <div class="col-lg-2"></div>
            <div class="col-lg-6">
                <input name="Submit" type="submit" class="btn btn-success AddBTN " value="حفظ" id="SubmitBTN">
                {{-- <input name="Submit" type="submit" class="btn btn-primary AddBTN " value="حفظ كمسودة" id="SaveBTN"> --}}
                <input type="reset" class="btn btn-danger Reset" value="مسح الحقول">
                <input name="Add" type="hidden" value="TRUE" id="SaveBTN">
            </div>
        </div>
    </div>
</div>
<!--end::Card-->
@endsection

@section('modals')
@include('Partials.photoswipe_modal')
@endsection

{{-- Scripts Section --}}
@section('scripts')
<script src="{{ asset('/assets/components/menu-add.js') }}"></script>  
<script src="{{ asset('/assets/components/panelSettings.js') }}"></script>
<script src="{{ asset('/assets/js/photoswipe.min.js') }}"></script>
<script src="{{ asset('/assets/js/photoswipe-ui-default.min.js') }}"></script>
<script src="{{ asset('/assets/components/myPhotoSwipe.js') }}"></script>     
@endsection

