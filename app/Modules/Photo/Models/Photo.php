<?php namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Photo extends Model{

    use \TraitsFunc;

    protected $table = 'photos';
    protected $primaryKey = 'id';
    public $timestamps = false;

    public function imageable(){
        return $this->morphTo(__FUNCTION__, 'imageable_type', 'imageable_id');
    }

    static function getOne($id){
        return self::NotDeleted()->where('id', $id)
            ->first();
    }

    static function dataList($type='') {
        $input = \Request::all();

        $source = self::NotDeleted()->where(function ($query) use ($input) {
                if (isset($input['id']) && !empty($input['id'])) {
                    $query->where('id', $input['id']);
                }

                if (isset($input['from']) && !empty($input['from']) && isset($input['to']) && !empty($input['to'])) {
                    $query->where('created_at','>=', $input['from'].' 00:00:00')->where('created_at','<=',$input['to']. ' 23:59:59');
                }
            });;
        if($type == 'library'){
            $source->where('imageable_id',0);
        }
        $source->orderBy('sort','ASC');

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
        $data->filename = $source->filename;
        $data->imageable_type = $source->imageable_type;
        $data->imageable_id = $source->imageable_id;
        $data->link = $source->link;
        $data->status = $source->status;
        $data->photo_size = $source->link != '' ? self::getPhotoSize($source->link) : '';
        $data->url = $source->status;
        $data->sort = $source->sort;
        $data->created_at = \Helper::formatDate($source->created_at,'Y-m-d h:i A');
        return $data;
    }

    static function getPhotoSize($url){
        $image = get_headers($url, 1);
        $bytes = $image["Content-Length"];
        $mb = $bytes/(1024 * 1024);
        return number_format($mb,2) . " MB ";
    }

    static function newSortIndex(){
        return self::count() + 1;
    }

}
