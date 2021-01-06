<?php namespace App\Http\Controllers;


use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Models\TopMenu;
use App\Models\Page;
use App\Models\WebActions;
use Illuminate\Http\Request;
use DataTables;


class TopMenuControllers extends Controller {

    use \TraitsFunc;

    protected function validateObject($input){
        $rules = [
            'title' => 'required',
            'page_id' => 'required'
        ];

        $message = [
            'title.required' => "يرجي ادخال العنوان",
            'page_id.required' => 'يرجي اختيار اسم الصفحة'
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index(Request $request)
    {   
        if($request->ajax()){
            $data = TopMenu::dataList();
            return Datatables::of($data['data'])->make(true);
        }
        return view('TopMenu.Views.index');
    }

    public function add() {
        $data['pages'] = Page::dataList()['data'];
        return view('TopMenu.Views.add')->with('data',(object) $data);
    }

    public function edit($id) {
        $id = (int) $id;

        $menuObj = TopMenu::find($id);
        if($menuObj == null) {
            return Redirect('404');
        }

        $data['data'] = TopMenu::getData($menuObj);
        $data['pages'] = Page::dataList()['data'];
        return view('TopMenu.Views.edit')->with('data', (object) $data);      
    }

    public function update($id) {
        $id = (int) $id;
        $input = \Request::all();

        $menuObj = TopMenu::find($id);

        if($menuObj == null) {
            return Redirect('404');
        }

        $validate = $this->validateObject($input);
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        if($input['page_id'] != 0){
            $pageObj = Page::getOne($input['page_id']);
            if($pageObj == null) {
                return Redirect('404');
            }
        }

        $menuObj->title = $input['title'];
        $menuObj->page_id = $input['page_id'];
        $menuObj->link = $input['link'];
        $menuObj->status = $input['status'];
        $menuObj->updated_at = DATE_TIME;
        $menuObj->updated_by = USER_ID;
        $menuObj->save();

        WebActions::newType(2,'TopMenu');
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
        
        if($input['page_id'] != 0){
            $pageObj = Page::getOne($input['page_id']);
            if($pageObj == null) {
                return Redirect('404');
            }
        }

        $menuObj = new TopMenu;
        $menuObj->title = $input['title'];
        $menuObj->page_id = $input['page_id'];
        $menuObj->link = $input['link'];
        $menuObj->status = $input['status'];
        $menuObj->sort = TopMenu::newSortIndex();
        $menuObj->created_at = DATE_TIME;
        $menuObj->created_by = USER_ID;
        $menuObj->save();

        WebActions::newType(1,'TopMenu');
        \Session::flash('success', "تنبيه! تم الحفظ بنجاح");
        return redirect()->to('backend/topMenu/');
    }

    public function delete($id) {
        $id = (int) $id;
        $menuObj = TopMenu::getOne($id);
        WebActions::newType(3,'TopMenu');
        return \Helper::globalDelete($menuObj);
    }

    public function fastEdit() {
        $input = \Request::all();
        foreach ($input['data'] as $item) {
            $col = $item[1];
            $menuObj = TopMenu::find($item[0]);
            $menuObj->$col = $item[2];
            $menuObj->updated_at = DATE_TIME;
            $menuObj->updated_by = USER_ID;
            $menuObj->save();
        }

        WebActions::newType(4,'TopMenu');
        return \TraitsFunc::SuccessResponse('تم التعديل بنجاح');
    }

    public function arrange() {
        $data = TopMenu::dataList();
        return view('TopMenu.Views.arrange')->with('data', (Object) $data);;
    }

    public function sort(){
        $input = \Request::all();

        $ids = json_decode($input['ids']);
        $sorts = json_decode($input['sorts']);

        for ($i = 0; $i < count($ids) ; $i++) {
            TopMenu::where('id',$ids[$i])->update(['sort'=>$sorts[$i]]);
        }
        return \TraitsFunc::SuccessResponse('تم الترتيب بنجاح');
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

        $addCount = WebActions::getByDate($date,$start,$end,1,'TopMenu')['count'];
        $editCount = WebActions::getByDate($date,$start,$end,2,'TopMenu')['count'];
        $deleteCount = WebActions::getByDate($date,$start,$end,3,'TopMenu')['count'];
        $fastEditCount = WebActions::getByDate($date,$start,$end,4,'TopMenu')['count'];

        $data['chartData1'] = $this->getChartData($start,$end,1,'TopMenu');
        $data['chartData2'] = $this->getChartData($start,$end,2,'TopMenu');
        $data['chartData3'] = $this->getChartData($start,$end,4,'TopMenu');
        $data['chartData4'] = $this->getChartData($start,$end,3,'TopMenu');
        $data['counts'] = [$addCount , $editCount , $deleteCount , $fastEditCount];
        $data['title'] = 'القوائم العلوية';
        $data['miniTitle'] = 'القوائم';
        $data['url'] = 'topMenu';

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
