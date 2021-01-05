<?php namespace App\Http\Controllers;

use App\Models\Variable;
use App\Models\WebActions;
use Illuminate\Support\Facades\Input;
use Illuminate\Http\Request;

class VariablesControllers extends Controller {

    use \TraitsFunc;


    public function index() {
        $variableList = Variable::variableList(1);
        return view('Variables.Views.index')
            ->with('data', (Object) $variableList);
    }

    public function update($type) {
        $input = \Request::all();
        if(in_array($type, [1,2])){
            foreach ($input as $key => $value) {
                if($key != '_token'){
                    Variable::where('type',$type)->where('id',str_replace('value', '', $key))->update(['var_value'=>$value]);
                }
            }
            WebActions::newType(2,'Variable');
            \Session::flash('success', "تنبيه! تم التعديل بنجاح");
            return \Redirect::back()->withInput();
        }
    }

    public function panel() {
        $variableList = Variable::variableList(2);
        return view('Variables.Views.panel')
            ->with('data', (Object) $variableList);
    }

    public function uploadImage(Request $request,$id=false){
        \Session::put('photos', []);
        if ($request->hasFile('file')) {
            $files = $request->file('file');
            $images = self::addImage($files,$id);
            if ($images == false) {
                return \TraitsFunc::ErrorMessage("حدث مشكلة في رفع الملفات");
            }
            return \TraitsFunc::SuccessResponse('');
        }
    }

    public function addImage($images,$nextID=false) {    
        $fileName = \ImagesHelper::UploadImage('variables', $images, $nextID);
        if($fileName == false){
            return false;
        }
        Variable::where('id',$nextID)->update(['var_value'=>\URL::to('/').'/uploads/variables/'.$nextID.'/'.$fileName]);
        
        return 1;        
    }

    public function deleteImage($id){
        $id = (int) $id;
        $input = \Request::all();

        $menuObj = Variable::find($id);

        if($menuObj == null) {
            return \TraitsFunc::ErrorMessage("هذه الصفحة غير موجودة");
        }

        $menuObj->var_value = '';
        $menuObj->save();
        return \TraitsFunc::SuccessResponse('تم حذف الصورة بنجاح');
    }

}
