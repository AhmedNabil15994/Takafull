<?php namespace App\Http\Controllers;


use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Models\Slider;
use App\Models\Photo;
use App\Models\WebActions;
use Illuminate\Http\Request;
use DataTables;


class SliderControllers extends Controller {

    use \TraitsFunc;

    protected function validateObject($input){
        $rules = [
            'title' => 'required',
        ];

        $message = [
            'title.required' => "يرجي ادخال العنوان",
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index(Request $request)
    {   
        if($request->ajax()){
            $data = Slider::dataList();
            return Datatables::of($data['data'])->make(true);
        }
        return view('Slider.Views.index');
    }

    public function add() {
        return view('Slider.Views.add');
    }

    public function edit($id) {
        $id = (int) $id;

        $menuObj = Slider::find($id);
        if($menuObj == null) {
            return Redirect('404');
        }

        $data['data'] = Slider::getData($menuObj);
        return view('Slider.Views.edit')->with('data', (object) $data);      
    }

    public function update($id) {
        $id = (int) $id;
        $input = \Request::all();

        $menuObj = Slider::find($id);

        if($menuObj == null) {
            return Redirect('404');
        }

        $validate = $this->validateObject($input);
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        $menuObj->title = $input['title'];
        $menuObj->status = $input['status'];
        $menuObj->updated_at = DATE_TIME;
        $menuObj->updated_by = USER_ID;
        $menuObj->save();


        $photos = \Session::get('photos');

        if($photos){
            $imagesData = Photo::where('imageable_type','App\Models\Slider')->whereIn('id',$photos);
            $imagesData->update(['imageable_id'=>$menuObj->id]);
            foreach ($imagesData->get() as $image) {
                if($image->link == $image->filename){
                    $image->link = \URL::to('/uploads').'/sliders/'.$menuObj->id.'/'.$image->filename;
                    $image->save();

                    $menuObj->image = $image->filename;
                    $menuObj->save();
                }
            }
        }

        \Session::forget('photos');
        WebActions::newType(2,'Slider');
        \Session::flash('success', "تنبيه! تم التعديل بنجاح");
        return \Redirect::back()->withInput();
    }
    
    public function create() {
        $input = \Request::all();
        
        $validate = $this->validateObject($input);
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }
        
        $menuObj = new Slider;
        $menuObj->title = $input['title'];
        $menuObj->status = $input['status'];
        $menuObj->sort = Slider::newSortIndex();
        $menuObj->created_at = DATE_TIME;
        $menuObj->created_by = USER_ID;
        $menuObj->save();

        $photos = \Session::get('photos');

        if($photos){
            $imagesData = Photo::where('imageable_type','App\Models\Slider')->whereIn('id',$photos);
            $imagesData->update(['imageable_id'=>$menuObj->id]);
            foreach ($imagesData->get() as $image) {
                if($image->link == $image->filename){
                    $image->link = \URL::to('/uploads').'/sliders/'.$menuObj->id.'/'.$image->filename;
                    $image->save();

                    $menuObj->image = $image->filename;
                    $menuObj->save();
                }
            }
        }

        \Session::forget('photos');
        WebActions::newType(1,'Slider');
        \Session::flash('success', "تنبيه! تم الحفظ بنجاح");
        return redirect()->to('backend/sliders/');
    }

    public function delete($id) {
        $id = (int) $id;
        $menuObj = Slider::getOne($id);
        WebActions::newType(3,'Slider');
        return \Helper::globalDelete($menuObj);
    }

    public function fastEdit() {
        $input = \Request::all();
        foreach ($input['data'] as $item) {
            $col = $item[1];
            $menuObj = Slider::find($item[0]);
            $menuObj->$col = $item[2];
            $menuObj->updated_at = DATE_TIME;
            $menuObj->updated_by = USER_ID;
            $menuObj->save();
        }

        WebActions::newType(4,'Slider');
        return \TraitsFunc::SuccessResponse('تم التعديل بنجاح');
    }

    public function arrange() {
        $data = Slider::dataList();
        return view('Slider.Views.arrange')->with('data', (Object) $data);;
    }

    public function sort(){
        $input = \Request::all();

        $ids = json_decode($input['ids']);
        $sorts = json_decode($input['sorts']);

        for ($i = 0; $i < count($ids) ; $i++) {
            Slider::where('id',$ids[$i])->update(['sort'=>$sorts[$i]]);
        }
        return \TraitsFunc::SuccessResponse('تم الترتيب بنجاح');
    }

    public function uploadImage(Request $request,$id=false){
        \Session::put('photos', []);
        if ($request->hasFile('file')) {
            $files = $request->file('file');
            $images = self::addImage($files,$id);
            if ($images == false) {
                return \TraitsFunc::ErrorMessage("حدث مشكلة في رفع الملفات");
            }
            $myArr = \Session::get('photos');
            $myArr[] = $images;
            \Session::put('photos',$myArr);
            return \TraitsFunc::SuccessResponse('');
        }
    }

    public function addImage($images,$nextID=false) {
        $lastID = Slider::orderBy('id','DESC')->first();
        if($lastID && ! $nextID){
            $nextID = $lastID->id + 1;
        }        
        $fileName = \ImagesHelper::UploadImage('sliders', $images, $nextID);
        if($fileName == false){
            return false;
        }
        
        $photoObj = new Photo;
        $photoObj->filename = $fileName;
        $photoObj->imageable_type = 'App\Models\Slider';
        $photoObj->imageable_id = $nextID;
        $photoObj->link = $fileName;
        $photoObj->status = 1;
        $photoObj->sort = Photo::newSortIndex();
        $photoObj->created_at = DATE_TIME;
        $photoObj->created_by = USER_ID;
        $photoObj->save();
        
        return [$photoObj->id,$fileName];        
    }

    public function deleteImage($id){
        $id = (int) $id;
        $input = \Request::all();

        $menuObj = Slider::find($id);

        if($menuObj == null) {
            return \TraitsFunc::ErrorMessage("هذه الصفحة غير موجودة");
        }

        Photo::where('imageable_id',$id)->where('imageable_type','App\Models\Slider')->update(['updated_at'=> DATE_TIME,'updated_by' => USER_ID]);
        $menuObj->image = '';
        $menuObj->save();

        return \TraitsFunc::SuccessResponse('تم حذف الصورة بنجاح');
    }

    public function charts() {
        return view('Slider.Views.charts');
    }


}
