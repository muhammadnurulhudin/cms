<?php
namespace Udiko\Cms\Models;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Collection;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Post extends Model
{
  use HasFactory,HasUuids;
protected $fillable=[
  'title','slug','content','url','thumbnail','thumbnail_description','keyword','description','parent','user_id','pinned','type','redirect_to','status','visited','allow_comment','data_field','data_loop','created_at'
];


  public function user()
  {
  return $this->belongsTo(User::class);
  }

  public function comments()
  {
      return $this->hasMany(Comment::class);
  }
  public function post_parent()
  {
      return $this->belongsTo(Post::class,'parent','id');
  }
  public function group()
  {
  return $this->belongsToMany(Group::class);
  }

  public function child()
  {
      return $this->hasMany(Post::class,'parent','id');
  }
  function cachedpost(){
    return Cache::get('post');
  }
  function count($type){
    return $this->cachedpost()->where('type',$type)->count();
  }
  public function groups($type)
  {
      return collect(Cache::get('group'))->where('type',$type)->sortBy('sort');
  }

  function comments_list($type,$post_name,$limit=false){
    $res = $this->cachedpost()->where('post_name',$post_name)->where('type',$type)->first();
    return $limit ? $res->comments->where('status',1)->take($limit) : $res->comments->where('status',1);
  }

  function comments_form($detail){
    if($detail->allow_comment==1){
    //  $com = paginate($detail->comments->where('status',1));
//    return  View::make('comments_form',compact('com'));
    }
    else{
     return null;
    }
   }

  function index_limit($type,$limit){
    return $this->cachedpost()->where('type',$type)->take($limit);
  }

  function index_skip($type,$skip,$limit){
    return $this->posts->where('type',$type)->skip($skip)->take($limit);

  }

  public function visitsCounter()
  {
    //   return visits($this);
  }

  public function index($type,$paginate=false){
return $paginate ? paginate($this->cachedpost()->where('type',$type)) : $this->cachedpost()->where('type',$type);
  }

  public function index_popular($type){
    return $this->cachedpost()->where('type',$type)->sortByDesc('visited')->take(5);
  }

  function index_pinned($limit,$type=false){
    return $type ? $this->posts->where('post_pin',1)->where('type',$type)->take($limit) : $this->posts->where('post_pin',1)->take($limit);
  }

  function index_by_group($type,$group,$limit=false){
    $cek = collect(Cache::get('listgroup'))->where('type',$type)->where('slug',$group)->first();
   return $cek && count(collect($cek->posts)) > 0 ? ($limit ? collect($cek->posts)->take($limit) : collect($cek->posts)) : array();
  }
  function index_recent($type,$except=false){
    return $except ? $this->cachedpost()->whereNotIn('id',$except)->where('type',$type)->take(5) : $this->cachedpost()->where('type',$type)->take(5);
  }
  function index_child($id,$type=false){
    return $type ? $this->posts->where('post_parent',$id)->where('type',$type) : $this->posts->where('post_parent',$id);
  }

  function detail($type,$name=false){
    if($name)
    return Post::with('user','group','comments')->whereType($type)->whereStatus('publish')->where('slug','like','%'.$name)->first() ?? Post::with('user','group','comments')->whereType($type)->whereStatus('publish')->where('slug','like',$name.'%')->first();
    return $this->posts->where('type',$type)->first();
 }

public function history($post_id,$currenttime){

  $cekpre = Cache::get('post')->where('post_id','!=',$post_id)->where('type',get_post_type())->where('created_at','<',$currenttime)->first();
  $ceknex = Cache::get('post')->where('post_id','!=',$post_id)->where('type',get_post_type())->where('created_at','>',$currenttime)->sortBy('created_at')->first();
  //add new change post_thumbnail to thumbnail
  return json_decode(json_encode([
    'next'=> $ceknex ? ['url'=> url($ceknex->post_url),'title'=>$ceknex->post_title,'thumbnail'=>$ceknex->post_thumbnail] : null,
    'previous'=>$cekpre ? ['url'=> url($cekpre->post_url),'title'=>$cekpre->post_title,'thumbnail'=>$cekpre->post_thumbnail] : null,

  ]));
 }
}
