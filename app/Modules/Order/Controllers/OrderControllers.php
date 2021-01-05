<?php namespace App\Http\Controllers;


use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use App\Models\Order;
use App\Models\WebActions;
use Illuminate\Http\Request;
use DataTables;


class OrderControllers extends Controller {

    use \TraitsFunc;

    public function index(Request $request)
    {   
        if($request->ajax()){
            $data = Order::dataList();
            return Datatables::of($data['data'])->make(true);
        }
        $data['title'] = 'الطلبات';
        $data['name'] = 'order';
        $data['url'] = 'orders';
        return view('Order.Views.index')->with('data',(object) $data);
    }


    public function softDelete(Request $request) {
        $menuObj = Order::whereIn('id',$request->data)->update(['deleted_at'=>DATE_TIME,'deleted_by'=>USER_ID]);
        WebActions::newType(3,'Order');
        $data['status'] = \TraitsFunc::SuccessResponse("تم الحذف بنجاح");
        return response()->json($data);
    }

    public function changeStatus($status,Request $request) {
        $status = (int) $status;
        if(!in_array($status, [1,2,3,4,5,6,7])){
            $data['status'] = \TraitsFunc::ErrorMessage("يرجي مراجعة البيانات");
            return response()->json($data);
        }
        $menuObj = Order::whereIn('id',$request->data)->update(['status'=>$status,'updated_at'=>DATE_TIME,'updated_by'=>USER_ID]);
        WebActions::newType(2,'Order');
        $data['status'] = \TraitsFunc::SuccessResponse("تم التعديل بنجاح");
        return response()->json($data);
    }

    public function delete($id) {
        $id = (int) $id;
        $menuObj = Order::getOne($id);
        WebActions::newType(3,'Order');
        return \Helper::globalDelete($menuObj);
    }

    public function fastEdit() {
        $input = \Request::all();
        foreach ($input['data'] as $item) {
            $col = $item[1];
            $menuObj = Order::NotDeleted()->find($item[0]);
            $menuObj->$col = $item[2];
            $menuObj->updated_at = DATE_TIME;
            $menuObj->updated_by = USER_ID;
            $menuObj->save();
        }

        WebActions::newType(4,'Order');
        return \TraitsFunc::SuccessResponse('تم التعديل بنجاح');
    }

    public function charts() {
        $data['title'] = 'الطلبات';
        $data['url'] = 'orders';
        return view('Order.Views.charts')->with('data',(object) $data);
    }

    public function trashes(Request $request){   
        if($request->ajax()){
            $data = Order::dataList(7);
            return Datatables::of($data['data'])->make(true);
        }
        $data['title'] = 'المهملات';
        $data['url'] = 'trashes';
        $data['name'] = 'trash';
        return view('Order.Views.index')->with('data',(object) $data);
    }

    public function newOrders(Request $request){   
        if($request->ajax()){
            $data = Order::dataList(1);
            return Datatables::of($data['data'])->make(true);
        }
        $data['title'] = 'الطلبات الجديدة';
        $data['url'] = 'newOrders';
        $data['name'] = 'newOrder';
        return view('Order.Views.index')->with('data',(object) $data);
    }

    public function sentOrders(Request $request){   
        if($request->ajax()){
            $data = Order::dataList(2);
            return Datatables::of($data['data'])->make(true);
        }
        $data['title'] = 'الطلبات تم الارسال';
        $data['url'] = 'sentOrders';
        $data['name'] = 'sentOrder';
        return view('Order.Views.index')->with('data',(object) $data);
    }

    public function delayedOrders(Request $request){   
        if($request->ajax()){
            $data = Order::dataList(3);
            return Datatables::of($data['data'])->make(true);
        }
        $data['title'] = 'الطلبات تأجلت';
        $data['url'] = 'delayedOrders';
        $data['name'] = 'delayedOrder';
        return view('Order.Views.index')->with('data',(object) $data);
    }

    public function receivedOrders(Request $request){   
        if($request->ajax()){
            $data = Order::dataList(4);
            return Datatables::of($data['data'])->make(true);
        }
        $data['title'] = 'الطلبات تم التسليم';
        $data['url'] = 'receivedOrders';
        $data['name'] = 'receivedOrder';
        return view('Order.Views.index')->with('data',(object) $data);
    }

    public function unRepliedOrders(Request $request){   
        if($request->ajax()){
            $data = Order::dataList(5);
            return Datatables::of($data['data'])->make(true);
        }
        $data['title'] = 'الطلبات عدم الرد';
        $data['url'] = 'unRepliedOrders';
        $data['name'] = 'unRepliedOrder';
        return view('Order.Views.index')->with('data',(object) $data);
    }

    public function cancelledOrders(Request $request){   
        if($request->ajax()){
            $data = Order::dataList(6);
            return Datatables::of($data['data'])->make(true);
        }
        $data['title'] = 'الطلبات ملغية';
        $data['url'] = 'cancelledOrders';
        $data['name'] = 'cancelledOrder';
        return view('Order.Views.index')->with('data',(object) $data);
    }

}
