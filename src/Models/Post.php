<?php
namespace Udiko\Cms\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use View;
class Post extends Model
{
    use HasFactory, HasUuids;
    protected $fillable = [
        'title', 'slug', 'content', 'url', 'thumbnail', 'thumbnail_description', 'keyword', 'description', 'parent', 'user_id', 'pinned', 'type', 'redirect_to', 'status', 'visited', 'allow_comment', 'mime','data_field', 'data_loop', 'created_at'
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
        return $this->belongsTo(Post::class, 'parent', 'id');
    }
    public function group()
    {
        return $this->belongsToMany(Group::class);
    }

    public function child()
    {
        return $this->hasMany(Post::class, 'parent', 'id');
    }
    function cachedpost()
    {
        return Cache::get('post');
    }
    function count($type)
    {
        return $this->cachedpost()->where('type', $type)->count();
    }
    public function groups($type)
    {
        return collect(Cache::get('group'))->where('type', $type)->sortBy('sort');
    }

    function comments_list($type, $post_name, $limit = false)
    {
        $res = $this->cachedpost()->where('post_name', $post_name)->where('type', $type)->first();
        return $limit ? $res->comments->where('status', 1)->take($limit) : $res->comments->where('status', 1);
    }

    function index_limit($type, $limit)
    {
        return $this->cachedpost()->where('type', $type)->take($limit);
    }

    function index_skip($type, $skip, $limit)
    {
        return $this->cachedpost()->where('type', $type)->skip($skip)->take($limit);

    }

    public function index($type, $paginate = false)
    {
        return $paginate ? paginate($this->cachedpost()->where('type', $type)) : $this->cachedpost()->where('type', $type);
    }

    public function index_popular($type)
    {
        return $this->cachedpost()->where('type', $type)->sortByDesc('visited')->take(5);
    }

    function index_pinned($limit, $type = false)
    {
        return $type ? $this->cachedpost()->where('post_pin', 1)->where('type', $type)->take($limit) : $this->cachedpost()->where('post_pin', 1)->take($limit);
    }

    function index_by_group($type, $group, $limit = false)
    {
        $cek = collect(Cache::get('group'))->where('type', $type)->where('slug', $group)->first();
        return $cek && count(collect($cek->post)) > 0 ? ($limit ? collect($cek->post)->take($limit) : collect($cek->post)) : array();
    }
    function index_recent($type, $except = false)
    {
        return $except ? $this->cachedpost()->whereNotIn('id', $except)->where('type', $type)->take(5) : $this->cachedpost()->where('type', $type)->take(5);
    }
    function comments_box(){
        if($data = config('modules.data')){
            if($data->allow_comment==1)
            return View::make('views::layouts.comments',['data'=>$data->comments->where('status',1)->sortByDesc('created_at')]);

        }
    }
    function index_child($id, $type = false)
    {
        return $type ? $this->cachedpost()->where('parent', $id)->where('type', $type) : $this->cachedpost()->where('parent', $id);
    }

    function detail($type, $name = false)
    {
        if ($name) {
            return Post::with('user', 'group', 'comments')->whereType($type)->whereStatus('publish')->where('slug', 'like', '%' . $name)->first() ?? Post::with('user', 'group', 'comments')->whereType($type)->whereStatus('publish')->where('slug', 'like', $name . '%')->first();
        }else{
        return $this->cachedpost()->where('type', $type)->first();
    }
    }

    public function history($post_id, $currenttime)
    {
        if (get_module(get_post_type())->history) {
            $cekpre = $this->cachedpost()->where('id', '!=', $post_id)->where('type', get_post_type())->where('created_at', '<', $currenttime)->first();
            $ceknex = $this->cachedpost()->where('id', '!=', $post_id)->where('type', get_post_type())->where('created_at', '>', $currenttime)->sortBy('created_at')->first();
            //add new change post_thumbnail to thumbnail
            return json_decode(json_encode([
                'next' => $ceknex ? ['url' => url($ceknex->url), 'title' => $ceknex->title, 'thumbnail' => $ceknex->thumbnail] : array(),
                'previous' => $cekpre ? ['url' => url(path: $cekpre->url), 'title' => $cekpre->title, 'thumbnail' => $cekpre->thumbnail] : array(),

            ]));
        }
        else{
            return false;
        }
    }
}
