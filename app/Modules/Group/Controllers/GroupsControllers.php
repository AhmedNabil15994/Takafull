<?php namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Models\WebActions;
use DataTables;


class GroupsControllers extends Controller {

    use \TraitsFunc;

    protected function validateGroup($input){
        $rules = [
            'title' => 'required',
        ];

        $message = [
            'title.required' => "يرجي ادخال العنوان",
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index(Request $request) {
        if($request->ajax()){
            $data = Group::dataList();
            return Datatables::of($data['data'])->make(true);
        }
        return view('Group.Views.index');
    }

    public function edit($id) {
        $id = (int) $id;

        $groupObj = Group::NotDeleted()->find($id);

        if($groupObj == null) {
            return Redirect('404');
        }

        $data['permissions'] = config('main-pers.mainIndexes');
        $data['data'] = Group::getData($groupObj);
        return view('Group.Views.edit')->with('data', (object) $data);      
    }

    public function update($id) {
        $id = (int) $id;

        $input = \Request::all();

        $groupObj = Group::NotDeleted()->find($id);

        if($groupObj == null) {
            return Redirect('404');
        }

        $validate = $this->validateGroup($input);
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        $permissionsArr = [];
        foreach ($input as $key => $oneItem) {
            if(strpos($key, 'list-') !== false){
                $permissionsArr[] = $key;
                $morePermission = explode(',', $oneItem);
                foreach($morePermission as $onePer){
                    $permissionsArr[] = $onePer;
                }
            }
        }

        $groupObj->title = $input['title'];
        $groupObj->permissions = serialize($permissionsArr);
        $groupObj->admin_privs = $input['admin_privs'];
        $groupObj->status = $input['status'];
        $groupObj->updated_at = DATE_TIME;
        $groupObj->updated_by = USER_ID;
        $groupObj->save();

        WebActions::newType(2,'Group');
        \Session::flash('success', "تنبيه! تم التعديل بنجاح");
        return \Redirect::back()->withInput();
    }

    public function add() {
        $data['permissions'] = config('main-pers.mainIndexes');
        return view('Group.Views.add')->with('data', (object) $data);
    }

    public function create() {
        $input = \Request::all();
        
        $validate = $this->validateGroup($input);
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }

        $permissionsArr = [];
        foreach ($input as $key => $oneItem) {
            if(strpos($key, 'list-') !== false){
                $permissionsArr[] = $key;
                $morePermission = explode(',', $oneItem);
                foreach($morePermission as $onePer){
                    $permissionsArr[] = $onePer;
                }
            }
        }

        $groupObj = new Group;
        $groupObj->title = $input['title'];
        $groupObj->permissions = serialize($permissionsArr);
        $groupObj->admin_privs = $input['admin_privs'];
        $groupObj->sort = Group::newSortIndex();
        $groupObj->status = $input['status'];
        $groupObj->created_at = DATE_TIME;
        $groupObj->created_by = USER_ID;
        $groupObj->save();

        WebActions::newType(1,'Group');
        \Session::flash('success', "تنبيه! تم الحفظ بنجاح");
        return redirect()->to('backend/groups/');
    }

    public function delete($id) {
        $id = (int) $id;
        $groupObj = Group::getOne($id);
        WebActions::newType(3,'Group');
        return \Helper::globalDelete($groupObj);
    }

    public function fastEdit() {
        $input = \Request::all();
        foreach ($input['data'] as $item) {
            $col = $item[1];
            $menuObj = Group::find($item[0]);
            $menuObj->$col = $item[2];
            $menuObj->updated_at = DATE_TIME;
            $menuObj->updated_by = USER_ID;
            $menuObj->save();
        }

        WebActions::newType(4,'Group');
        return \TraitsFunc::SuccessResponse('تم التعديل بنجاح');
    }

    public function arrange() {
        $data = Group::dataList();
        return view('Group.Views.arrange')->with('data', (Object) $data);;
    }

    public function sort(){
        $input = \Request::all();

        $ids = json_decode($input['ids']);
        $sorts = json_decode($input['sorts']);

        for ($i = 0; $i < count($ids) ; $i++) {
            Group::where('id',$ids[$i])->update(['sort'=>$sorts[$i]]);
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

        $addCount = WebActions::getByDate($date,$start,$end,1,'Group')['count'];
        $editCount = WebActions::getByDate($date,$start,$end,2,'Group')['count'];
        $deleteCount = WebActions::getByDate($date,$start,$end,3,'Group')['count'];
        $fastEditCount = WebActions::getByDate($date,$start,$end,4,'Group')['count'];

        $data['chartData1'] = $this->getChartData($start,$end,1,'Group');
        $data['chartData2'] = $this->getChartData($start,$end,2,'Group');
        $data['chartData3'] = $this->getChartData($start,$end,4,'Group');
        $data['chartData4'] = $this->getChartData($start,$end,3,'Group');
        $data['counts'] = [$addCount , $editCount , $deleteCount , $fastEditCount];
        $data['title'] = 'مجموعات المشرفين';
        $data['miniTitle'] = 'مجموعات المشرفين';
        $data['url'] = 'groups';

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
