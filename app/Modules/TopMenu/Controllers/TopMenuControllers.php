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
        return view('TopMenu.Views.charts');
    }


}
