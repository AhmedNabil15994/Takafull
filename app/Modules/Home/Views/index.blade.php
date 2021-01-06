@extends('Frontend.Layouts.master')

@section('title','الصفحة الرئيسية')

@section('styles')
@endsection

@section('content')
	<div class="slider">
        <ul id="slider" class="the-slider-inner">
            @foreach($data->sliders as $slider)
            <li>
                <div class="background-slider"></div>
                <img src="{{ $slider->photo }}" alt="" />
                <div class="sliderDetails">
                    <div class="container">
               		 	<div class="titleSlider">
               		 		{!! $slider->title !!}
						</div>
						<div class="clearfix">
							<a href="{{ \App\Models\Variable::getVar('الفيديو التعريفى:') }}" data-toggle="modal" data-target="#video">الفيديو التعريفي</a>
							<a href="#" data-scroll-nav="1">اشترك الآن</a>
						</div>
                    </div>
                </div>
            </li> 
            @endforeach        
        </ul>
        <ul class="list-unstyled the-slider-control">
            <li><span id="slider-next"></span></li>
            <li><span id="slider-prev"></span></li>
        </ul> <!-- END the slider control-->            
        
    </div>
   
   
	<div class="newCard" data-scroll-index="1">
		<div class="form wow fadeInUp">
			<div class="container">
				<div class="row">
					<div class="col-md-5">
						<form class="formCard">
							<h2 class="titleForm">طلب بطاقة جديدة</h2>
							<div class="inputStyle">
								<label class="nameInput">الإسم :</label>
								<input type="text" name="name" placeholder="ضع اسمك الثلاثي هنا" />
							</div>
							<div class="inputStyle">
								<label class="nameInput">رقم الهوية أو جواز السفر :</label>
								<input type="number" name="identity" placeholder="ضع رقم الهوية أو جواز السفر" />
							</div>
							<div class="inputStyle inputNumb">
								<label class="nameInput">أضف رقم جوالك الحالي</label>
								<input type="number" name="phone" id="inputNumb" placeholder="رقم الجوال :" />
								{{-- <i class="numberIcon fa fa-plus"></i> --}}
							</div>
							<div class="inputStyle">
								<label class="nameInput">المدينة :</label>
								<select class="selectmenu" name="city" id="selectmenu">
									@foreach($data->cities as $city)
									<option value="{{ $city->title }}">{{ $city->title }}</option>
									@endforeach
								</select>
								<label for="selectmenu" class="iconLeft fa fa-angle-down"></label>
							</div>
							<div class="inputStyle">
								<label class="nameInput">أضف العنوان بشكل صحيح</label>
								<input type="text" name="address" placeholder="العنوان :" />
							</div>
							<div class="inputStyle">
								<label class="nameInput">اكتب الرقم الظاهر امامك : <span class="rand">{{ rand(20,100) }}</span></label>
								<input type="number" name="check" placeholder="أضف الرقم هنا" />
							</div>
							<div class="checkDiv">
								<span class="text">
									الموافقة على الشروط والأحكام
								</span>
								<label class="switch">
								  <input type="checkbox">
								  <span class="sliderSwitch round"></span>
								</label>
							</div>
							<button class="sentRequest">ارسل الآن</button>
						
						</form>
						
					</div>
					<div class="col-md-7">
						<div class="imgDiv">
							<img src="{{ URL::to('/assets/images/imgForm.png') }}" alt="" />
							<div class="privcyDiv">
									<h3 class="PrivacyTitle">سياسة الخصوصية</h3>
									<div class="privcyDesc">بطلبك البطاقة فانت توافق علي سياسة الخصوصية الخاصة بنا <a href="#"  data-toggle="modal" data-target="#ModalPrivacy">الخصوصية</a></div>
									<div class="copyRights">
										Copyright 2018 - 2020, All Rights Reserved <span class="copy">&copy;</span>
									</div>
							</div>
						</div>
					
					</div>
				
				</div>
			</div>
		</div>
		
	</div>


	<div class="features" data-scroll-index="2">
		<div class="container">
			<center>
				<ul class="tabsBtns clearfix">
					<li id="tab1" class="active">من مميزات البطاقة</li>
					<li id="tab2">فائدة البطاقة</li>
				</ul>
			</center>
			<div class="tabs">
				<div class="tab1 tab">
					<div class='row'>
						@foreach($data->advantages as $advantage)
						<div class="col-md-4 wow fadeInUp">
							<div class="itemFeatures">
								<i class="{{ $advantage->icon }}"></i>
								<div class="desc">
									{!! $advantage->title !!}
								</div>
							</div>
						</div>
						@endforeach
					</div>
		
				</div>
				<div class="tab2 tab">
					<div class='row'>
						@foreach($data->benefits as $benefit)
						<div class="col-md-3 wow fadeInUp">
							<div class="itemFeatures">
								<i class="{{ $benefit->icon }}"></i>
								<div class="desc">{!! $benefit->title !!}
								</div>
							</div>
						</div>
						@endforeach
					</div>
				</div>
			</div>
		</div>
	</div>



    <div class="JoinNow background-cover">
	  	<div class="container">
			<h2 class="title">{!! $data->pages[1]->title !!}</h2>
	   		<div class="desc">
	   		 {!! $data->pages[1]->description !!} 
	   		</div>
	   		<div class="btns">
		   		<a href="{{ \App\Models\Variable::getVar('الفيديو التعريفى:') }}" data-toggle="modal" data-target="#video" class="btnVid wow fadeInUp flaticon-play-button"></a>
		   		<a class="btnNow " href="#" data-scroll-nav="1">اشترك الان</a>
				</div>
   		</div>
   	</div>
@endsection

@section('modals')
@include('Frontend.Partials.privacyModal')
@include('Frontend.Partials.contactUsModal')
@include('Frontend.Partials.videoModal')
@endsection

@section('scripts')
<script src="{{ URL::to('/assets/components/home.js') }}"></script>
@endsection