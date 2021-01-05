<?php namespace App\Http\Controllers;


use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Models\ContactUs;
use App\Models\WebActions;
use Illuminate\Http\Request;
use DataTables;


class ContactUsControllers extends Controller {

    use \TraitsFunc;

    protected function validateObject($input){
        $rules = [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',//|regex:/(01)[0-9]{9}/',
            'message' => 'required',
        ];

        $message = [
            'name.required' => "يرجي ادخال الاسم بالكامل",
            'email.required' => "يرجي ادخال البريد الالكتروني",
            'email.email' => "يرجي ادخال بريد الكتروني متاح",
            'message.required' => "يرجي ادخال مضمون الرسالة",
            // 'phone.regex' => "يرجي ادخال رقم التليفون",
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index(Request $request)
    {   
        if($request->ajax()){
            $data = ContactUs::dataList();
            return Datatables::of($data['data'])->make(true);
        }
        return view('ContactUs.Views.index');
    }

    public function edit($id) {
        $id = (int) $id;

        $menuObj = ContactUs::NotDeleted()->find($id);

        if($menuObj == null) {
            return Redirect('404');
        }

        $data['data'] = ContactUs::getData($menuObj);
        return view('ContactUs.Views.edit')->with('data', (object) $data);      
    }

    public function update($id) {
        $id = (int) $id;
        $input = \Request::all();

        $menuObj = ContactUs::NotDeleted()->find($id);

        if($menuObj == null) {
            return Redirect('404');
        }

        $validate = $this->validateObject($input);
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        $menuObj->name = $input['name'];
        $menuObj->email = $input['email'];
        $menuObj->phone = $input['phone'];
        $menuObj->address = $input['address'];
        $menuObj->message = $input['message'];
        $menuObj->status = $input['status'];
        $menuObj->updated_at = DATE_TIME;
        $menuObj->updated_by = USER_ID;
        $menuObj->save();

        WebActions::newType(2,'ContactUs');
        \Session::flash('success', "تنبيه! تم التعديل بنجاح");
        return \Redirect::back()->withInput();
    }

    public function delete($id) {
        $id = (int) $id;
        $menuObj = ContactUs::getOne($id);
        WebActions::newType(3,'ContactUs');
        return \Helper::globalDelete($menuObj);
    }

    public function fastEdit() {
        $input = \Request::all();
        foreach ($input['data'] as $item) {
            $col = $item[1];
            $menuObj = ContactUs::NotDeleted()->find($item[0]);
            $menuObj->$col = $item[2];
            $menuObj->updated_at = DATE_TIME;
            $menuObj->updated_by = USER_ID;
            $menuObj->save();
        }

        WebActions::newType(4,'ContactUs');
        return \TraitsFunc::SuccessResponse('تم التعديل بنجاح');
    }

    public function reply($id){
        $id = (int) $id;

        $menuObj = ContactUs::NotDeleted()->find($id);

        if($menuObj == null) {
            return Redirect('404');
        }

        $data['data'] = ContactUs::getData($menuObj);
        return view('ContactUs.Views.reply')->with('data', (object) $data);     
    }

    public function postReply($id){
        $id = (int) $id;
        $input = \Request::all();

        $menuObj = ContactUs::NotDeleted()->find($id);

        if($menuObj == null) {
            return Redirect('404');
        }

        $menuObj->reply = $input['reply'];
        $menuObj->status = $input['status'];
        $menuObj->updated_at = DATE_TIME;
        $menuObj->updated_by = USER_ID;
        $menuObj->save();

        WebActions::newType(2,'ContactUs');
        \Session::flash('success', "تنبيه! تم الرد بنجاح");
        return \Redirect::back()->withInput();
    }

    public function charts() {
        return view('ContactUs.Views.charts');
    }


}
