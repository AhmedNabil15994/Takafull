<?php namespace App\Http\Controllers;


use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Models\Page;
use App\Models\Photo;
use App\Models\WebActions;
use Illuminate\Http\Request;
use DataTables;


class PageControllers extends Controller {

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
            $data = Page::dataList();
            return Datatables::of($data['data'])->make(true);
        }
        return view('Page.Views.index');
    }

    public function add() {
        return view('Page.Views.add');
    }

    public function edit($id) {
        $id = (int) $id;

        $menuObj = Page::find($id);
        if($menuObj == null) {
            return Redirect('404');
        }

        $data['data'] = Page::getData($menuObj);
        return view('Page.Views.edit')->with('data', (object) $data);      
    }

    public function update($id) {
        $id = (int) $id;
        $input = \Request::all();

        $menuObj = Page::find($id);

        if($menuObj == null) {
            return Redirect('404');
        }

        $validate = $this->validateObject($input);
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        $menuObj->title = $input['title'];
        $menuObj->icon = $input['icon'];
        $menuObj->description = $input['description'];
        $menuObj->status = $input['status'];
        $menuObj->updated_at = DATE_TIME;
        $menuObj->updated_by = USER_ID;
        $menuObj->save();


        $photos = \Session::get('photos');

        if($photos){
            $imagesData = Photo::where('imageable_type','App\Models\Page')->whereIn('id',$photos);
            $imagesData->update(['imageable_id'=>$menuObj->id]);
            foreach ($imagesData->get() as $image) {
                if($image->link == $image->filename){
                    $image->link = \URL::to('/uploads').'/pages/'.$menuObj->id.'/'.$image->filename;
                    $image->save();

                    $menuObj->image = $image->filename;
                    $menuObj->save();
                }
            }
        }

        \Session::forget('photos');
        WebActions::newType(2,'Page');
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
        
        $menuObj = new Page;
        $menuObj->title = $input['title'];
        $menuObj->icon = $input['icon'];
        $menuObj->description = $input['description'];
        $menuObj->status = $input['status'];
        $menuObj->sort = Page::newSortIndex();
        $menuObj->created_at = DATE_TIME;
        $menuObj->created_by = USER_ID;
        $menuObj->save();

        $photos = \Session::get('photos');
        if($photos){
            $imagesData = Photo::where('imageable_type','App\Models\Page')->whereIn('id',$photos);
            $imagesData->update(['imageable_id'=>$menuObj->id]);
            foreach ($imagesData->get() as $image) {
                if($image->link == $image->filename){
                    $image->link = \URL::to('/uploads').'/pages/'.$menuObj->id.'/'.$image->filename;
                    $image->save();

                    $menuObj->image = $image->filename;
                    $menuObj->save();
                }
            }
        }

        \Session::forget('photos');
        WebActions::newType(1,'Page');
        \Session::flash('success', "تنبيه! تم الحفظ بنجاح");
        return redirect()->to('backend/pages/');
    }

    public function delete($id) {
        $id = (int) $id;
        $menuObj = Page::getOne($id);
        WebActions::newType(3,'Page');
        return \Helper::globalDelete($menuObj);
    }

    public function fastEdit() {
        $input = \Request::all();
        foreach ($input['data'] as $item) {
            $col = $item[1];
            $menuObj = Page::find($item[0]);
            $menuObj->$col = $item[2];
            $menuObj->updated_at = DATE_TIME;
            $menuObj->updated_by = USER_ID;
            $menuObj->save();
        }

        WebActions::newType(4,'Page');
        return \TraitsFunc::SuccessResponse('تم التعديل بنجاح');
    }

    public function arrange() {
        $data = Page::dataList();
        return view('Page.Views.arrange')->with('data', (Object) $data);;
    }

    public function sort(){
        $input = \Request::all();

        $ids = json_decode($input['ids']);
        $sorts = json_decode($input['sorts']);

        for ($i = 0; $i < count($ids) ; $i++) {
            Page::where('id',$ids[$i])->update(['sort'=>$sorts[$i]]);
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
        $lastID = Page::orderBy('id','DESC')->first();
        if($lastID && ! $nextID){
            $nextID = $lastID->id + 1;
        }        
        $fileName = \ImagesHelper::UploadImage('pages', $images, $nextID);
        if($fileName == false){
            return false;
        }
        
        $photoObj = new Photo;
        $photoObj->filename = $fileName;
        $photoObj->imageable_type = 'App\Models\Page';
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

        $menuObj = Page::find($id);

        if($menuObj == null) {
            return \TraitsFunc::ErrorMessage("هذه الصفحة غير موجودة");
        }

        Photo::where('imageable_id',$id)->where('imageable_type','App\Models\Page')->update(['updated_at'=> DATE_TIME,'updated_by' => USER_ID]);
        $menuObj->image = '';
        $menuObj->save();

        return \TraitsFunc::SuccessResponse('تم حذف الصورة بنجاح');
    }

    public function charts() {
        $input = \Request::all();
        $now = date('Y-m-d');
        $start = $now;
        $end = $now;
        $date = null;
        if(isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])){
            $start = $input['from'].' 00:00:00';
            $end = $input['to'].' 23:59:59';
            $date = 1;
        }

        $addCount = WebActions::getByDate($date,$start,$end,1,'Page')['count'];
        $editCount = WebActions::getByDate($date,$start,$end,2,'Page')['count'];
        $deleteCount = WebActions::getByDate($date,$start,$end,3,'Page')['count'];
        $fastEditCount = WebActions::getByDate($date,$start,$end,4,'Page')['count'];

        $data['chartData1'] = $this->getChartData($start,$end,1,'Page');
        $data['chartData2'] = $this->getChartData($start,$end,2,'Page');
        $data['chartData3'] = $this->getChartData($start,$end,4,'Page');
        $data['chartData4'] = $this->getChartData($start,$end,3,'Page');
        $data['counts'] = [$addCount , $editCount , $deleteCount , $fastEditCount];
        $data['title'] = 'الصفحات';
        $data['miniTitle'] = 'الصفحات';
        $data['url'] = 'pages';

        return view('TopMenu.Views.charts')->with('data',(object) $data);
    }

    public function getChartData($start=null,$end=null,$type,$moduleName){
        $input = \Request::all();
        
        if(isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])){
            $start = $input['from'];
            $end = $input['to'];
        }

        $datediff = strtotime($end) - strtotime($start);
        $daysCount = round($datediff / (60 * 60 * 24));
        $datesArray = [];
        $datesArray[0] = $start;

        if($daysCount > 2){
            for($i=0;$i<$daysCount;$i++){
                $datesArray[$i] = date('Y-m-d',strtotime($start.'+'.$i."day") );
            }
            $datesArray[$daysCount] = $end;  
        }else{
            for($i=1;$i<24;$i++){
                $datesArray[$i] = date('Y-m-d H:i:s',strtotime($start.'+'.$i." hour") );
            }
        }

        $chartData = [];
        $dataCount = count($datesArray);

        for($i=0;$i<$dataCount;$i++){
            if($dataCount == 1){
                $count = WebActions::where('type',$type)->where('module_name',$moduleName)->where('created_at','>=',$datesArray[0].' 00:00:00')->where('created_at','<=',$datesArray[0].' 23:59:59')->count();
            }else{
                if($i < count($datesArray)){
                    $count = WebActions::where('type',$type)->where('module_name',$moduleName)->where('created_at','>=',$datesArray[$i].' 00:00:00')->where('created_at','<=',$datesArray[$i].' 23:59:59')->count();
                }
            }
            $chartData[0][$i] = $datesArray[$i];
            $chartData[1][$i] = $count;
        }
        return $chartData;
    }


}
