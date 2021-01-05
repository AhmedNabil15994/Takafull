<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class WebActions extends Model{

    use \TraitsFunc;

    protected $table = 'web_actions';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function User(){
        return $this->belongsTo('App\Models\User','created_by');
    }

    static function getOne($id){
        return self::where('id', $id)
            ->first();
    }

    static function dataList() {
        $input = \Request::all();

        $source = self::orderBy('id','ASC');

        return self::generateObj($source);
    }

    static function generateObj($source){
        $sourceArr = $source->get();

        $list = [];
        foreach($sourceArr as $key => $value) {
            $list[$key] = new \stdClass();
            $list[$key] = self::getData($value);
        }

        // $data['pagination'] = \Helper::GeneratePagination($sourceArr);
        $data['data'] = $list;

        return $data;
    }

    static function getData($source) {
        $data = new  \stdClass();
        $data->id = $source->id;
        $data->type = $source->type;
        $data->typeText = self::getType($source->type);
        $data->username = $source->User->username;
        $data->module_name = $source->module_name;
        $data->created_at = \Helper::formatDateForDisplay($source->created_at,true);
        return $data;
    }

    static function getType($type){
        $text = '';
        if($type == 1){
            $text = 'اضافة';
        }elseif($type == 2){
            $text = 'تعديل';
        }elseif($type == 3){
            $text = 'حذف';
        }elseif($type == 4){
            $text = 'تعديل سريع';
        }
        return $text;
    }

    static function getCountByType($type){
        return self::where('type',$type)->count();
    }

    static function newType($type,$name){
        $myObj = new self;
        $myObj->type = $type;
        $myObj->module_name = $name;
        $myObj->created_at = DATE_TIME;
        $myObj->created_by = USER_ID;
        $myObj->save();
    }

}
