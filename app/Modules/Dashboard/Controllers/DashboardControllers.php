<?php namespace App\Http\Controllers;


use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;

class DashboardControllers extends Controller {

    use \TraitsFunc;

    public function Dashboard()
    {   
        $this->data['user'] = \Auth::user();
        return view('Dashboard.Views.dashboard',$this->data);
    }

    public function changeLang(Request $request){
        if($request->ajax()){
            if(!Session::has('locale')){
                Session::put('locale', $request->locale);
            }else{
                Session::forget('locale');
                Session::put('locale', $request->locale);
            } 
            return Redirect::back();
        }
    }
}
