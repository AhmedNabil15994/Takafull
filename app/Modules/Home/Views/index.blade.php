@extends('Frontend.Layouts.master')

@section('title','الصفحة الرئيسية')

@section('styles')
@endsection

@section('content')
	<div class="slider">
        <ul id="slider" class="the-slider-inner">
            <li>
                <div class="background-slider"></div>
                <img src="{{ URL::to('/assets/images/sliderImg.png') }}" alt="" />
                <div class="sliderDetails">
                    <div class="container">
               		 	<div class="titleSlider">
               		 		نقدم لكم بطاقة تكافل العربية تمنح حاملها خصم على
							جميع التخصصات الطبية في القطاع الصحي
						</div>
						<div class="clearfix">
							<a href="{{ \App\Models\Variable::getVar('الفيديو التعريفى:') }}" data-toggle="modal" data-target="#video">الفيديو التعريفي</a>
							<a href="#" data-scroll-nav="1">اشترك الآن</a>
						</div>
                    </div>
                </div>
            </li>
            <li>
                <div class="background-slider"></div>
                <img src="{{ URL::to('/assets/images/sliderImg.png') }}" alt="" />
                <div class="sliderDetails">
                    <div class="container">
               		 	<div class="titleSlider">
               		 		نقدم لكم بطاقة تكافل العربية تمنح حاملها خصم على
							جميع التخصصات الطبية في القطاع الصحي
						</div>
						<div class="clearfix">
							<a href="{{ \App\Models\Variable::getVar('الفيديو التعريفى:') }}" data-toggle="modal" data-target="#video">الفيديو التعريفي</a>
							<a href="#" data-scroll-nav="1">اشترك الآن</a>
						</div>
                    </div>
                </div>
            </li>   
         
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
								<input type="text" placeholder="ضع اسمك الثلاثي هنا" />
							</div>
							<div class="inputStyle">
								<label class="nameInput">رقم الهوية أو جواز السفر :</label>
								<input type="number" placeholder="ضع رقم الهوية أو جواز السفر" />
							</div>
							<div class="inputStyle">
								<label class="nameInput dateInput">ضع تاريخ ميلادك هنا :</label>
								<input type="text" id="dateInput" class="datepicker" placeholder="ضع تاريخ ميلادك هنا" />
								<label for="dateInput" class="iconLeft fa fa-angle-down"></label>
							</div>
							<div class="inputStyle inputNumb">
								<label class="nameInput">أضف رقم جوالك الحالي</label>
								<input type="number" id="inputNumb" placeholder="رقم الجوال :" />
								<i class="numberIcon fa fa-plus"></i>
							</div>
							<div class="inputStyle">
								<label class="nameInput">المدينة :</label>
								<select class="selectmenu" id="selectmenu">
									<option>الرياض</option>
									<option>المدينة</option>
									<option>المدينة</option>
									<option>المدينة</option>
								</select>
								<label for="selectmenu" class="iconLeft fa fa-angle-down"></label>
							</div>
							<div class="inputStyle">
								<label class="nameInput">أضف العنوان بشكل صحيح</label>
								<input type="number" placeholder="العنوان :" />
							</div>
							<div class="inputStyle">
								<label class="nameInput">اكتب الرقم الظاهر امامك : <span>93</span></label>
								<input type="number" placeholder="أضف الرقم هنا" />
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
							<button>ارسل الآن</button>
						
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
						<div class="col-md-4 wow fadeInUp">
							<div class="itemFeatures">
								<i class="flaticon-team"></i>
								<div class="desc">
									تشمل جميع فئات المجتمع  حكومين ـ 
									قطاع خاص ـ مقيمين ـ زائرين ـ عمره او حج
								</div>
							</div>
						</div>
						<div class="col-md-4 wow fadeInUp">
							<div class="itemFeatures">
								<i class="flaticon-money-bag"></i>
								<div class="desc">
									السعر موحد للجميع وعدم ارتفاعها  لكبار 
									السن ومعاناتهم من اسعار بطاقات التأمين
								</div>
							</div>
						</div>
						<div class="col-md-4 wow fadeInUp">
							<div class="itemFeatures">
								<i class="flaticon-check-mark"></i>
								<div class="desc">
									يمكن استخدام البطاقة 
									<br>
									مباشرةً
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4 wow fadeInUp">
							<div class="itemFeatures">
								<i class="flaticon-id-card-2"></i>
								<div class="desc">
									يمكن استخدام البطاقة 
									مباشرةً عند الحصول عليها
								</div>
							</div>
						</div>
						<div class="col-md-4 wow fadeInUp">
							<div class="itemFeatures">
								<i class="flaticon-profit"></i>
								<div class="desc">
									لا يوجد حد ائتماني لاستخدام 
									<br>
									البطاقة
								</div>
							</div>
						</div>
						<div class="col-md-4 wow fadeInUp">
							<div class="itemFeatures">
								<i class="flaticon-social-care"></i>
								<div class="desc">
									بطاقة تكافل تقدم خدمات انسانية 
									واقتصادية للمجتمع تتوافق مع رؤية 2030
								</div>
							</div>
						</div>
					</div>
		
				</div>
				<div class="tab2 tab">
					<div class='row'>
						<div class="col-md-3 wow fadeInUp">
							<div class="itemFeatures">
								<i class="flaticon-heart"></i>
								<div class="desc">تغطي جميع 
									<br>
									التخصصات الطبية
									</div>
							</div>
						</div>
						<div class="col-md-3 wow fadeInUp">
							<div class="itemFeatures">
								<i class="flaticon-surgery"></i>
								<div class="desc">خصم على العمليات 
									<br>
									الجراحية والتجميلية
								</div>
							</div>
						</div>
						<div class="col-md-3 wow fadeInUp">
							<div class="itemFeatures">
								<i class="flaticon-scientist"></i>
								<div class="desc">خصم على كافة
									<br>
									 الفحوصات والتحاليل</div>
							</div>
						</div>
						<div class="col-md-3 wow fadeInUp">
							<div class="itemFeatures">
								<i class="flaticon-maternity"></i>
								<div class="desc">خصم على الولادة الطبيعية
									<br>
									 والقيصرية ومتابعة الحمل
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-md-4 wow fadeInUp">
							<div class="itemFeatures">
								<i class="flaticon-vision"></i>
								<div class="desc">تخصم على تصحيح النظر بالليزك
									<br>
									 والليزر والنظارات الشمسية والطبية
								</div>
							</div>
						</div>
						<div class="col-md-4 wow fadeInUp">
							<div class="itemFeatures">
								<i class="flaticon-makeup-1"></i>
								<div class="desc">خصم على العناية
									<br>
									 بالبشرة</div>
							</div>
						</div>
						<div class="col-md-4 wow fadeInUp">
							<div class="itemFeatures">
								<i class="flaticon-braces"></i>
								<div class="desc">خصم على جميع خدمات الاسنان الحشو والتركيبات
									 وتقويم الاسنان حتى التجميلية
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>



    <div class="JoinNow background-cover">
	  	<div class="container">
				<h2 class="title">عرض لفترة محدودة</h2>
	   		<div class="desc">
	   		 سنة عليك وسنة علينا فقط بـ 200 ريال لمدة سنتين 
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
@endsection