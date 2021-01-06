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

        $addCount = WebActions::getByDate($date,$start,$end,1,'File')['count'];
        $editCount = WebActions::getByDate($date,$start,$end,2,'File')['count'];
        $deleteCount = WebActions::getByDate($date,$start,$end,3,'File')['count'];
        $fastEditCount = WebActions::getByDate($date,$start,$end,4,'File')['count'];

        $data['chartData1'] = $this->getChartData($start,$end,1,'File');
        $data['chartData2'] = $this->getChartData($start,$end,2,'File');
        $data['chartData3'] = $this->getChartData($start,$end,4,'File');
        $data['chartData4'] = $this->getChartData($start,$end,3,'File');
        $data['counts'] = [$addCount , $editCount , $deleteCount , $fastEditCount];
        $data['title'] = 'مكتبة الملفات';
        $data['miniTitle'] = 'مكتبة الملفات';
        $data['url'] = 'files';

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
