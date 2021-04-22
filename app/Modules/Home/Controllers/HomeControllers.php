<?php namespace App\Http\Controllers;


use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\TopMenu;
use App\Models\Page;
use App\Models\BottomMenu;
use App\Models\Slider;
use App\Models\Benefit;
use App\Models\ContactUs;
use App\Models\Advantage;
use App\Models\Variable;
use App\Models\WebActions;
use App\Models\Order;
use App\Models\City;

class HomeControllers extends Controller {

    use \TraitsFunc;

    protected function validateObject($input){
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required|min:10',//|regex:/(01)[0-9]{9}/',
            'message' => 'required',
            'address' => 'required',
        ];

        $message = [
            'name.required' => "يرجي ادخال الاسم بالكامل",
            'email.required' => "يرجي ادخال البريد الالكتروني",
            'email.email' => "يرجي ادخال بريد الكتروني متاح",
            'message.required' => "يرجي ادخال تفاصيل الرسالة",
            'address.required' => "يرجي ادخال عنوان الرسالة",
            'phone.required' => "يرجي ادخال رقم الجوال",
            'phone.min' => "رقم الجوال يجب ان يكون 10 خانات",
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    protected function validateOrder($input){
        $rules = [
            'name' => 'required',
            'phone' => 'required|min:10',//|regex:/(01)[0-9]{9}/',
            'identity' => 'required',
            'address' => 'required',
            'city' => 'required',
        ];

        $message = [
            'name.required' => "يرجي ادخال الاسم بالكامل",
            'phone.required' => "يرجي ادخال رقم الجوال",
            'phone.min' => "رقم الجوال يجب ان يكون 10 خانات",
            'identity.required' => "يرجي ادخال رقم الهوية او جواز السفر",
            'address.required' => "يرجي ادخال العنوان",
            'city.required' => "يرجي اختيار المدينة",
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index()
    {   
    	$data['topMenu'] = TopMenu::NotDeleted()->where('status',1)->orderBy('sort','ASC')->get();
    	$data['bottomMenu'] = BottomMenu::NotDeleted()->where('status',1)->orderBy('sort','ASC')->get();
    	$data['privacyContent'] = Page::NotDeleted()->where('status',1)->where('title','سياسة الخصوصية')->first();
    	$data['sliders'] = Slider::dataList(1)['data'];
    	$data['advantages'] = Advantage::dataList(1)['data'];
    	$data['benefits'] = Benefit::dataList(1)['data'];
    	$data['cities'] = City::dataList(1)['data'];
    	$data['pages'] = Page::dataList(1)['data'];
    	$data['tele'] = Variable::getVar('رقم الهاتف:');
    	$data['tele2'] = Variable::getVar('رقم الواتس اب:');
    	$data['title'] = Variable::getVar('العنوان عربي');
    	$data['desc'] = Variable::getVar('الوصف عربي');

    	$meta = Variable::getVar('الكلمات الدليلية عربي');
    	$data['meta'] = json_decode($meta)[0]->value;
        return view('Home.Views.index')->with('data',(object) $data);
    }
	
	public function contactUs() {
        $input = \Request::all();

        $validate = $this->validateObject($input);
        if($validate->fails()){
            return \TraitsFunc::ErrorMessage($validate->messages()->first());
        }
        $ip_address = \Request::ip();

        $faqObj = ContactUs::NotDeleted()->where('ip_address',$ip_address)->where('reply',null)->whereDate('created_at',date('Y-m-d'))->first();
        if($faqObj != null){
        	return \TraitsFunc::ErrorMessage('لقد تم ارسال الرسالة مسبقا');
        }

        $menuObj = new ContactUs;
        $menuObj->name = $input['name'];
        $menuObj->email = $input['email'];
        $menuObj->phone = $input['phone'];
        $menuObj->address = $input['address'];
        $menuObj->message = $input['message'];
        $menuObj->ip_address = $ip_address;
        $menuObj->reply = null;
        $menuObj->status = 1;
        $menuObj->created_at = DATE_TIME;
        $menuObj->save();

        WebActions::newType(2,'ContactUs',1);
        return \TraitsFunc::SuccessResponse("تنبيه! تم الارسال بنجاح");
    }

    public function postOrder() {
        $input = \Request::all();

        $validate = $this->validateOrder($input);
        if($validate->fails()){
            return \TraitsFunc::ErrorMessage($validate->messages()->first());
        }
        
        $menuObj = new Order;
        $menuObj->name = $input['name'];
        $menuObj->phone = $input['phone'];
        $menuObj->address = $input['address'];
        $menuObj->identity = $input['identity'];
        $menuObj->city = $input['city'];
        $menuObj->sort = Order::newSortIndex();
        $menuObj->status = 1;
        $menuObj->created_at = DATE_TIME;
        $menuObj->save();

        WebActions::newType(2,'Order',1);
        return \TraitsFunc::SuccessResponse("تنبيه! تم ارسال الطلب بنجاح");
    }
    
}
