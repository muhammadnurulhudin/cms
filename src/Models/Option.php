<?php
namespace Udiko\Cms\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class Option extends Model
{
    use HasUuids;

    protected $fillable=['name','value','autoload'];

}
