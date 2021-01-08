<?php namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Group;
use App\Models\Photo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Models\WebActions;
use DataTables;


class UsersControllers extends Controller {

    use \TraitsFunc;

    protected function validateGroup($input){
        $rules = [
            'group_id' => 'required',
            'username' => 'required',
            'password' => 'required|min:6',
            'email' => 'required',
            'lang' => 'required',
        ];

        $message = [
            'group_id.required' => "يرجي اختيار المجموعة",
            'username.required' => "يرجي ادخال اسم المستخدم",
            'password.required' => "يرجي ادخال كلمة المرور",
            'email.required' => "يرجي ادخال الترتيب",
            'lang.required' => "يرجي اختيار اللغة",
        ];

        $validate = \Validator::make($input, $rules, $message);

        return $validate;
    }

    public function index(Request $request) {
        if($request->ajax()){
            $data = User::dataList();
            return Datatables::of($data['data'])->make(true);
        }
        $data['groups'] = Group::dataList()['data'];
        return view('User.Views.index')->with('data', (object) $data);
    }

    public function edit($id) {
        $id = (int) $id;

        $userObj = User::NotDeleted()->find($id);

        if($userObj == null) {
            return Redirect('404');
        }
        $data['groups'] = Group::dataList()['data'];
        $data['data'] = User::getData($userObj);
        return view('User.Views.edit')->with('data', (object) $data);      
    }

    public function update($id) {
        $id = (int) $id;

        $input = \Request::all();

        $groupObj = User::NotDeleted()->find($id);

        if($groupObj == null) {
            return Redirect('404');
        }

        $validate = $this->validateGroup($input);
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back();
        }

        $groupObj->username = $input['username'];
        $groupObj->group_id = $input['group_id'];
        $groupObj->email = $input['email'];
        $groupObj->lang = $input['lang'];
        $groupObj->session_time = $input['session_time'];
        $groupObj->password = \Hash::make($input['password']);
        $groupObj->sort = User::newSortIndex();
        $groupObj->status = $input['status'];
        $groupObj->is_active = $input['status'];
        $groupObj->created_at = DATE_TIME;
        $groupObj->created_by = USER_ID;
        $groupObj->save();

        $photos = \Session::get('photos');

        if($photos){
            $imagesData = Photo::where('imageable_type','App\Models\User')->whereIn('id',$photos);
            $imagesData->update(['imageable_id'=>$groupObj->id]);
            foreach ($imagesData->get() as $image) {
                if($image->link == $image->filename){
                    $image->link = asset('/uploads').'/users/'.$groupObj->id.'/'.$image->filename;
                    $image->save();

                    $groupObj->image = $image->filename;
                    $groupObj->save();
                }
            }
        }

        \Session::forget('photos');
        WebActions::newType(2,'User');
        \Session::flash('success', "تنبيه! تم التعديل بنجاح");
        return \Redirect::back()->withInput();
    }

    public function add() {
        $data['groups'] = Group::dataList()['data'];
        return view('User.Views.add')->with('data', (object) $data);
    }

    public function create() {
        $input = \Request::all();
        
        $validate = $this->validateGroup($input);
        if($validate->fails()){
            \Session::flash('error', $validate->messages()->first());
            return redirect()->back()->withInput();
        }
        
        $userObj = User::checkUserByEmail($input['email']);
        if($userObj){
            \Session::flash('error', 'هذا البريد الالكتروني مستخدم من قبل');
            return redirect()->back()->withInput();
        }

        $userObj = User::checkUserByUserName($input['username']);
        if($userObj){
            \Session::flash('error', 'هذا اسم المستخدم مستخدم من قبل');
            return redirect()->back()->withInput();
        }

        $groupObj = new User;
        $groupObj->username = $input['username'];
        $groupObj->group_id = $input['group_id'];
        $groupObj->email = $input['email'];
        $groupObj->lang = $input['lang'];
        $groupObj->session_time = $input['session_time'];
        $groupObj->password = \Hash::make($input['password']);
        $groupObj->sort = User::newSortIndex();
        $groupObj->status = $input['status'];
        $groupObj->is_active = $input['status'];
        $groupObj->created_at = DATE_TIME;
        $groupObj->created_by = USER_ID;
        $groupObj->save();

        $photos = \Session::get('photos');
        if($photos){
            $imagesData = Photo::where('imageable_type','App\Models\User')->whereIn('id',$photos);
            $imagesData->update(['imageable_id'=>$groupObj->id]);

            foreach ($imagesData->get() as $image) {
                if($image->link == $image->filename){
                    $image->link = \URL::to('/uploads').'/users/'.$groupObj->id.'/'.$image->filename;
                    $image->save();

                    $groupObj->image = $image->filename;
                    $groupObj->save();
                }
            }

        }
        \Session::forget('photos');
        WebActions::newType(1,'User');
        \Session::flash('success', "تنبيه! تم الحفظ بنجاح");
        return redirect()->to('backend/users/');
    }

    public function delete($id) {
        $id = (int) $id;
        $groupObj = User::getOne($id);
        WebActions::newType(3,'User');
        return \Helper::globalDelete($groupObj);
    }

    public function fastEdit() {
        $input = \Request::all();
        foreach ($input['data'] as $item) {
            $col = $item[1];
            $menuObj = User::find($item[0]);
            $menuObj->$col = $item[2];
            $menuObj->updated_at = DATE_TIME;
            $menuObj->updated_by = USER_ID;
            $menuObj->save();
        }

        WebActions::newType(4,'User');
        return \TraitsFunc::SuccessResponse('تم التعديل بنجاح');
    }

    public function arrange() {
        $data = User::dataList();
        return view('User.Views.arrange')->with('data', (Object) $data);;
    }

    public function sort(){
        $input = \Request::all();

        $ids = json_decode($input['ids']);
        $sorts = json_decode($input['sorts']);

        for ($i = 0; $i < count($ids) ; $i++) {
            User::where('id',$ids[$i])->update(['sort'=>$sorts[$i]]);
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

        $addCount = WebActions::getByDate($date,$start,$end,1,'User')['count'];
        $editCount = WebActions::getByDate($date,$start,$end,2,'User')['count'];
        $deleteCount = WebActions::getByDate($date,$start,$end,3,'User')['count'];
        $fastEditCount = WebActions::getByDate($date,$start,$end,4,'User')['count'];

        $data['chartData1'] = $this->getChartData($start,$end,1,'User');
        $data['chartData2'] = $this->getChartData($start,$end,2,'User');
        $data['chartData3'] = $this->getChartData($start,$end,4,'User');
        $data['chartData4'] = $this->getChartData($start,$end,3,'User');
        $data['counts'] = [$addCount , $editCount , $deleteCount , $fastEditCount];
        $data['title'] = 'المشرفين والاداريين';
        $data['miniTitle'] = 'المشرفين والاداريين';
        $data['url'] = 'users';

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
        $lastID = User::orderBy('id','DESC')->first();
        if($lastID && ! $nextID){
            $nextID = $lastID->id + 1;
        }        
        $fileName = \ImagesHelper::UploadImage('users', $images, $nextID);
        if($fileName == false){
            return false;
        }
        
        $photoObj = new Photo;
        $photoObj->filename = $fileName;
        $photoObj->imageable_type = 'App\Models\User';
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

        $menuObj = User::find($id);

        if($menuObj == null) {
            return \TraitsFunc::ErrorMessage("هذا المستخدم غير موجود");
        }

        Photo::where('imageable_id',$id)->where('imageable_type','App\Models\User')->update(['updated_at'=> DATE_TIME,'updated_by' => USER_ID]);
        $menuObj->image = '';
        $menuObj->save();
        return \TraitsFunc::SuccessResponse('تم حذف الصورة بنجاح');
    }

}
