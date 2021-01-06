<?php namespace App\Http\Controllers;


use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Http\Request;
use App\Models\WebActions;
use App\Models\ContactUs;

class DashboardControllers extends Controller {

    use \TraitsFunc;

    public function Dashboard()
    {   
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
        $actions = WebActions::getByDate($date,$start,$end);
        $contactUs = ContactUs::getByDate($date,$start,$end);

        $data['contactUs'] = $contactUs['data'];
        $data['contactUsCount'] = $contactUs['count'];
        $data['webActions'] =  $actions['data'];
        $data['webActionsCount'] =  $actions['count'];
        $data['chartData1'] = $this->getChartData($start,$end,'\ContactUs');
        $data['chartData2'] = $this->getChartData($start,$end,'\WebActions');
        $data['addCount'] = WebActions::getByDate($date,$start,$end,1)['count'];
        $data['editCount'] = WebActions::getByDate($date,$start,$end,2)['count'];
        $data['deleteCount'] = WebActions::getByDate($date,$start,$end,3)['count'];
        $data['fastEditCount'] = WebActions::getByDate($date,$start,$end,4)['count'];
        return view('Dashboard.Views.dashboard')->with('data',(object) $data);
    }

    public function getChartData($start=null,$end=null,$moduleName){
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
        $module = "\App\Models".$moduleName;
        for($i=0;$i<$dataCount;$i++){
            if($dataCount == 1){
                if($moduleName == '\ContactUs'){
                    $count = $module::NotDeleted()->where('status',1)->where('created_at','>=',$datesArray[0].' 00:00:00')->where('created_at','<=',$datesArray[0].' 23:59:59')->count();
                }else{
                    $count = $module::where('created_at','>=',$datesArray[0].' 00:00:00')->where('created_at','<=',$datesArray[0].' 23:59:59')->count();
                }
            }else{
                if($i < count($datesArray)){
                    if($moduleName == '\ContactUs'){
                        $count = $module::NotDeleted()->where('status',1)->where('created_at','>=',$datesArray[$i].' 00:00:00')->where('created_at','<=',$datesArray[$i].' 23:59:59')->count();
                    }else{
                        $count = $module::where('created_at','>=',$datesArray[$i].' 00:00:00')->where('created_at','<=',$datesArray[$i].' 23:59:59')->count();
                    }
                }
            }
            $chartData[0][$i] = $datesArray[$i];
            $chartData[1][$i] = $count;
        }
        return $chartData;
    }

}
