<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BlockedUser extends Model{

    use \TraitsFunc;

    protected $table = 'blocked_users';
    protected $primaryKey = 'id';
    public $timestamps = false;
    public $fillable = ['username'];

    static function getOne($id) {
        return self::NotDeleted()
            ->find($id);
    }

    static function checkForUsername($username,$ip_address){
        return self::NotDeleted()->where('username',$username)->where('ended_at','>=',now()->format('Y-m-d H:i:s'))->where('ip_address',$ip_address)->first();
    }

    static function dataList() {        
        $input = \Request::all();
        $source = self::NotDeleted()->where(function ($query) use ($input) {
                if (isset($input['username']) && !empty($input['username'])) {
                    $query->where('username', 'LIKE', '%' . $input['username'] . '%');
                }
                if (isset($input['id']) && !empty($input['id'])) {
                    $query->where('id', $input['id']);
                }
                if (isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])) {
                    $query->where('created_at','>=', $input['from'].' 00:00:00')->where('created_at','<=',$input['to']. ' 23:59:59');
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
        $faqObj->ip_address = $source->ip_address;
        $faqObj->ended_at = $source->ended_at;
        $faqObj->sort = $source->sort;
        $faqObj->created_at = \Helper::formatDate($source->created_at,'Y-m-d H:i:s');

        return $faqObj;
    }

    static function newSortIndex(){
        return self::count() + 1;
    }

}
