<?php

namespace app\admin\model;

use think\Model;

class Article extends Model
{
    public function category(){
    	return $this->belongsTo('Column','cid','html_value');
    }
}
