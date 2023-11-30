<?php
namespace Udiko\Cms\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;

class PostData extends Model
{
    use HasUuids;
    protected $fillable = [
        'post_id',
        'name',
        'value'
    ];


    public function post(){
        return $this->belongsTo(Post::class);
    }
}
