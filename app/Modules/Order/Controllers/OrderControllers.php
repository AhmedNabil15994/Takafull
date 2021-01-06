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

        $addCount = WebActions::getByDate($date,$start,$end,1,'Order')['count'];
        $editCount = WebActions::getByDate($date,$start,$end,2,'Order')['count'];
        $deleteCount = WebActions::getByDate($date,$start,$end,3,'Order')['count'];
        $fastEditCount = WebActions::getByDate($date,$start,$end,4,'Order')['count'];

        $data['chartData1'] = $this->getChartData($start,$end,1,'Order');
        $data['chartData2'] = $this->getChartData($start,$end,2,'Order');
        $data['chartData3'] = $this->getChartData($start,$end,4,'Order');
        $data['chartData4'] = $this->getChartData($start,$end,3,'Order');
        $data['counts'] = [$addCount , $editCount , $deleteCount , $fastEditCount];
        $data['title'] = 'الطلبات';
        $data['miniTitle'] = 'الطلبات';
        $data['url'] = 'orders';

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
