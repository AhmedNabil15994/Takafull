<?php namespace App\Http\Controllers;


use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Models\File;
use App\Models\WebActions;
use Illuminate\Http\Request;
use DataTables;


class FileControllers extends Controller {

    use \TraitsFunc;

    public function index(Request $request)
    {   
        if($request->ajax()){
            $data = File::dataList('library');
            return Datatables::of($data['data'])->make(true);
        }
        return view('File.Views.index');
    }

    public function add() {
        return view('File.Views.add');
    }

    public function edit($id) {
        $id = (int) $id;

        $menuObj = File::find($id);
        if($menuObj == null) {
            return Redirect('404');
        }

        $data['data'] = File::getData($menuObj);
        return view('File.Views.edit')->with('data', (object) $data);      
    }

    public function update($id) {
        $id = (int) $id;
        $input = \Request::all();

        $FileObj = File::find($id);

        if($FileObj == null) {
            return Redirect('404');
        }

        $files = \Session::get('files');
        if($files == ''){
            \Session::flash('error', 'يرجي اختيار ملف');
            return redirect()->back()->withInput();
        }

        $FileObj->filename = $files;
        $FileObj->link = \URL::to('/').'/uploads/files/'.$files;
        $FileObj->status = $input['status'];
        $FileObj->status = $input['status'];
        $FileObj->updated_at = DATE_TIME;
        $FileObj->updated_by = USER_ID;
        $FileObj->save();

        \Session::forget('files');
        WebActions::newType(2,'File');
        \Session::flash('success', "تنبيه! تم التعديل بنجاح");
        return \Redirect::back()->withInput();
    }
    
    public function create() {
        $input = \Request::all();
        
        $files = \Session::get('files');
        if($files == ''){
            \Session::flash('error', 'يرجي اختيار ملف');
            return redirect()->back()->withInput();
        }
        $FileObj = new File;
        $FileObj->filename = $files;
        $FileObj->link = \URL::to('/').'/uploads/files/'.$files;
        $FileObj->status = $input['status'];
        $FileObj->sort = File::newSortIndex();
        $FileObj->created_at = DATE_TIME;
        $FileObj->created_by = USER_ID;
        $FileObj->save();
        
        \Session::forget('files');
        WebActions::newType(1,'File');
        \Session::flash('success', "تنبيه! تم الحفظ بنجاح");
        return redirect()->to('backend/files/');
    }

    public function delete($id) {
        $id = (int) $id;
        $menuObj = File::getOne($id);
        WebActions::newType(3,'File');
        return \Helper::globalDelete($menuObj);
    }

    public function fastEdit() {
        $input = \Request::all();
        foreach ($input['data'] as $item) {
            $col = $item[1];
            $menuObj = File::find($item[0]);
            $menuObj->$col = $item[2];
            $menuObj->updated_at = DATE_TIME;
            $menuObj->updated_by = USER_ID;
            $menuObj->save();
        }

        WebActions::newType(4,'File');
        return \TraitsFunc::SuccessResponse('تم التعديل بنجاح');
    }

    public function arrange() {
        $data = File::dataList();
        return view('File.Views.arrange')->with('data', (Object) $data);;
    }

    public function sort(){
        $input = \Request::all();

        $ids = json_decode($input['ids']);
        $sorts = json_decode($input['sorts']);

        for ($i = 0; $i < count($ids) ; $i++) {
            File::where('id',$ids[$i])->update(['sort'=>$sorts[$i]]);
        }
        return \TraitsFunc::SuccessResponse('تم الترتيب بنجاح');
    }

    public function uploadImage(Request $request){
        \Session::put('files', '');
        if ($request->hasFile('file')) {
            $files = $request->file('file');
            $images = self::addImage($files);
            if ($images == false) {
                return \TraitsFunc::ErrorMessage("حدث مشكلة في رفع الملفات");
            }
            $myArr = \Session::get('files');
            $myArr = $images;
            \Session::put('files',$myArr);
            return \TraitsFunc::SuccessResponse('');
        }
    }

    public function addImage($images) {    
        $fileName = \ImagesHelper::uploadCustom('files', $images);
        if($fileName == false){
            return false;
        }
        
        return $fileName;        
    }

    public function deleteImage($id){
        $id = (int) $id;
        $input = \Request::all();

        $menuObj = File::find($id);

        if($menuObj == null) {
            return \TraitsFunc::ErrorMessage("هذه الصفحة غير موجودة");
        }

        return \TraitsFunc::SuccessResponse('تم حذف الملف بنجاح');
    }

    public function charts() {
        return view('File.Views.charts');
    }


}
