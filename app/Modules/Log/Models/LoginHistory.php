<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LoginHistory extends Model{

    use \TraitsFunc;

    protected $table = 'login_history';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $fillable = ['username'];

    static function getOne($id) {
        return self::NotDeleted()
            ->find($id);
    }

    static function checkForUsername($username){
        return self::NotDeleted()->where('username',$username)->where('ended',0)->first();
    }

    static function dataList() {        
        $input = \Request::all();
        $source = self::NotDeleted()->where('ended',1)->where(function ($query) use ($input) {
                if (isset($input['username']) && !empty($input['username'])) {
                    $query->where('username', 'LIKE', '%' . $input['username'] . '%');
                }
                if (isset($input['id']) && !empty($input['id'])) {
                    $query->where('id', $input['id']);
                }
                if (isset($input['from1']) && !empty($input['from1']) && isset($input['to1']) && !empty($input['to1'])) {
                    $query->where('from_date','>=', $input['from1'].' 00:00:00')->where('from_date','<=',$input['to1']. ' 23:59:59');
                }
                if (isset($input['from2']) && !empty($input['from2']) && isset($input['to2']) && !empty($input['to2'])) {
                    $query->where('to_date','>=', $input['from2'].' 00:00:00')->where('to_date','<=',$input['to2']. ' 23:59:59');
                }
            });
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
        $faqObj = new \stdClass();
        $faqObj->id = $source->id;
        $faqObj->username = $source->username;
        $faqObj->from_date = $source->from_date;
        $faqObj->to_date = $source->to_date;
        $faqObj->ended = $source->ended;
        $faqObj->diff = self::getDiff($source->from_date,$source->to_date);
        $faqObj->sort = $source->sort;
        $faqObj->created_at = \Helper::formatDate($source->created_at,'Y-m-d H:i:s');

        return $faqObj;
    }

    static function newSortIndex(){
        return self::count() + 1;
    }

    static function getDiff($start,$end){
        $to_time = strtotime($end);
        $from_time = strtotime($start);
        return round(abs($to_time - $from_time) / 60);
    }
}
