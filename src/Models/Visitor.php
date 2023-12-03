<?php
namespace Udiko\Cms\Models;
use Illuminate\Database\Eloquent\Model;
class Visitor extends Model
{
    protected $fillable = ['ip','country','os','browser','session','device','page','reference','last_activity'];

}
