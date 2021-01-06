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

        $addCount = WebActions::getByDate($date,$start,$end,1,'Photo')['count'];
        $editCount = WebActions::getByDate($date,$start,$end,2,'Photo')['count'];
        $deleteCount = WebActions::getByDate($date,$start,$end,3,'Photo')['count'];
        $fastEditCount = WebActions::getByDate($date,$start,$end,4,'Photo')['count'];

        $data['chartData1'] = $this->getChartData($start,$end,1,'Photo');
        $data['chartData2'] = $this->getChartData($start,$end,2,'Photo');
        $data['chartData3'] = $this->getChartData($start,$end,4,'Photo');
        $data['chartData4'] = $this->getChartData($start,$end,3,'Photo');
        $data['counts'] = [$addCount , $editCount , $deleteCount , $fastEditCount];
        $data['title'] = 'مكتبية الصور';
        $data['miniTitle'] = 'مكتبية الصور';
        $data['url'] = 'photos';

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
