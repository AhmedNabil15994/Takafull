<?php namespace App\Http\Controllers;


use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Models\Photo;
use App\Models\WebActions;
use Illuminate\Http\Request;
use DataTables;


class PhotoControllers extends Controller {

    use \TraitsFunc;

    public function index(Request $request)
    {   
        if($request->ajax()){
            $data = Photo::dataList('library');
            return Datatables::of($data['data'])->make(true);
        }
        return view('Photo.Views.index');
    }

    public function add() {
        return view('Photo.Views.add');
    }

    public function edit($id) {
        $id = (int) $id;

        $menuObj = Photo::find($id);
        if($menuObj == null) {
            return Redirect('404');
        }

        $data['data'] = Photo::getData($menuObj);
        return view('Photo.Views.edit')->with('data', (object) $data);      
    }

    public function update($id) {
        $id = (int) $id;
        $input = \Request::all();

        $photoObj = Photo::find($id);

        if($photoObj == null) {
            return Redirect('404');
        }

        $photos = \Session::get('photos');
        if($photos == ''){
            \Session::flash('error', 'يرجي اختيار صورة');
            return redirect()->back()->withInput();
        }

        $photoObj->filename = $photos;
        $photoObj->link = \URL::to('/').'/uploads/photos/'.$photos;
        $photoObj->status = $input['status'];
        $photoObj->status = $input['status'];
        $photoObj->updated_at = DATE_TIME;
        $photoObj->updated_by = USER_ID;
        $photoObj->save();

        \Session::forget('photos');
        WebActions::newType(2,'Photo');
        \Session::flash('success', "تنبيه! تم التعديل بنجاح");
        return \Redirect::back()->withInput();
    }
    
    public function create() {
        $input = \Request::all();
        
        $photos = \Session::get('photos');
        if($photos == ''){
            \Session::flash('error', 'يرجي اختيار صورة');
            return redirect()->back()->withInput();
        }
        $photoObj = new Photo;
        $photoObj->filename = $photos;
        $photoObj->imageable_type = '';
        $photoObj->imageable_id = 0;
        $photoObj->link = \URL::to('/').'/uploads/photos/'.$photos;
        $photoObj->status = $input['status'];
        $photoObj->sort = Photo::newSortIndex();
        $photoObj->created_at = DATE_TIME;
        $photoObj->created_by = USER_ID;
        $photoObj->save();
        
        \Session::forget('photos');
        WebActions::newType(1,'Photo');
        \Session::flash('success', "تنبيه! تم الحفظ بنجاح");
        return redirect()->to('backend/photos/');
    }

    public function delete($id) {
        $id = (int) $id;
        $menuObj = Photo::getOne($id);
        WebActions::newType(3,'Photo');
        return \Helper::globalDelete($menuObj);
    }

    public function fastEdit() {
        $input = \Request::all();
        foreach ($input['data'] as $item) {
            $col = $item[1];
            $menuObj = Photo::find($item[0]);
            $menuObj->$col = $item[2];
            $menuObj->updated_at = DATE_TIME;
            $menuObj->updated_by = USER_ID;
            $menuObj->save();
        }

        WebActions::newType(4,'Photo');
        return \TraitsFunc::SuccessResponse('تم التعديل بنجاح');
    }

    public function arrange() {
        $data = Photo::dataList();
        return view('Photo.Views.arrange')->with('data', (Object) $data);;
    }

    public function sort(){
        $input = \Request::all();

        $ids = json_decode($input['ids']);
        $sorts = json_decode($input['sorts']);

        for ($i = 0; $i < count($ids) ; $i++) {
            Photo::where('id',$ids[$i])->update(['sort'=>$sorts[$i]]);
        }
        return \TraitsFunc::SuccessResponse('تم الترتيب بنجاح');
    }

    public function uploadImage(Request $request){
        \Session::put('photos', '');
        if ($request->hasFile('file')) {
            $files = $request->file('file');
            $images = self::addImage($files);
            if ($images == false) {
                return \TraitsFunc::ErrorMessage("حدث مشكلة في رفع الملفات");
            }
            $myArr = \Session::get('photos');
            $myArr = $images;
            \Session::put('photos',$myArr);
            return \TraitsFunc::SuccessResponse('');
        }
    }

    public function addImage($images) {    
        $fileName = \ImagesHelper::uploadCustom('photos', $images);
        if($fileName == false){
            return false;
        }
        
        return $fileName;        
    }

    public function deleteImage($id){
        $id = (int) $id;
        $input = \Request::all();

        $menuObj = Photo::find($id);

        if($menuObj == null) {
            return \TraitsFunc::ErrorMessage("هذه الصفحة غير موجودة");
        }

        // Photo::where('imageable_id',$id)->where('imageable_type','App\Models\Photo')->update(['updated_at'=> DATE_TIME,'updated_by' => USER_ID]);

        return \TraitsFunc::SuccessResponse('تم حذف الصورة بنجاح');
    }

    public function charts() {
        return view('Photo.Views.charts');
    }


}
