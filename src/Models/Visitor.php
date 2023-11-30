<?php
namespace Udiko\Cms\Models;
use Illuminate\Database\Eloquent\Model;
class Visitor extends Model
{
    protected $connection = "sqlite";
    protected $table = "tbl_visitor";
    protected $fillable = ['ip','date','time','os','browser','session','device','page','reference','last_activity','ip_location'];
    public $timestamps = false;

}
