<?php
namespace Udiko\Cms\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class Group extends Model
{
    public $timestamps = false;
    use HasUuids;


    protected $fillable=[
        'type','url','status','name','description','slug','sort'
      ];

    public function post()
    {
    return $this->belongsToMany(Post::class);
    }

    public function getRouteKeyName()
    {
        return 'slug';
    }

}
