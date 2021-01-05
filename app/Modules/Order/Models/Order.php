<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model{

    use \TraitsFunc;

    protected $table = 'orders';
    protected $primaryKey = 'id';
    public $timestamps = false;

    static function getOne($id) {
        return self::NotDeleted()
            ->find($id);
    }

    static function dataList($status=null) {        
        $input = \Request::all();
        $source = self::NotDeleted()->where(function ($query) use ($input) {
                if (isset($input['name']) && !empty($input['name'])) {
                    $query->where('name', 'LIKE', '%' . $input['name'] . '%');
                }
                if (isset($input['id']) && !empty($input['id'])) {
                    $query->where('id', $input['id']);
                }
                if (isset($input['status']) && !empty($input['status'])) {
                    $query->where('status', $input['status']);
                }
                if (isset($input['created_at']) && !empty($input['created_at'])) {
                    $query->where('created_at','>=', $input['created_at'].' 00:00:00')->where('created_at','<=',$input['created_at']. ' 23:59:59');
                }
            });
        if($status != null){
            $source->where('status',$status);
        }
        $source->orderBy('sort','ASC');
        return self::getObj($source);
    }

    static function getObj($source) {
        $sourceArr = $source->get();

        $list = [];
        foreach ($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value);
        }

        $data['data'] = $list;
        return $data;
    }

    static function getData($source) {
        $dataObj = new \stdClass();
        $dataObj->id = $source->id;
        $dataObj->name = $source->name;
        $dataObj->identity = $source->identity;
        $dataObj->phone = $source->phone;
        $dataObj->city = $source->city;
        $dataObj->address = $source->address;
        $dataObj->notes = $source->notes != null ? $source->notes : '';
        $dataObj->status = $source->status;
        $dataObj->statusText = self::getStatus($source->status);
        $dataObj->sort = $source->sort;
        $dataObj->created_at = \Helper::formatDate($source->created_at,'Y-m-d H:i:s');

        return $dataObj;
    }

    static function getStatus($status){
        $text = '';
        if($status == 1){
            $text = 'طلب جديد';
        }elseif($status == 2){
            $text = 'تم الارسال';
        }elseif($status == 3){
            $text = 'تأجيل';
        }elseif($status == 4){
            $text = 'تم التسليم';
        }elseif($status == 5){
            $text = 'تم الرد';
        }elseif($status == 6){
            $text = 'ملغية';
        }elseif($status == 7){
            $text = 'المهملات';
        }
        return $text;
    }

    static function newSortIndex(){
        return self::count() + 1;
    }

}
