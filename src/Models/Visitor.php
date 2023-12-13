<?php
namespace Udiko\Cms\Models;
use Illuminate\Database\Eloquent\Model;
class Visitor extends Model
{
    protected $fillable = ['ip','ip_location','os','browser','session','device','page','reference','date'];

}
