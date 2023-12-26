<?php
namespace Udiko\Cms\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
class Comment extends Model
{
    use HasUuids;
    protected $fillable = ['name','link','email','post_id','content'];


public function post()
  {
  return $this->belongsTo(Post::class);
  }
}
