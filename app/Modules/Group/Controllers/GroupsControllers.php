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

        $data['permissions'] = config('permissions.mainIndexes');
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
        $data['permissions'] = config('permissions.mainIndexes');
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
        return view('Group.Views.charts');
    }
}
