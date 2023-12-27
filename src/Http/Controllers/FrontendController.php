<?php
namespace Udiko\Cms\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Udiko\Cms\Http\Controllers\VisitorController;
use Str;
use Udiko\Cms\Models\Post;
use Udiko\Cms\Models\Group;
use Cache;
use View;
use Illuminate\Cache\RateLimiter;

class FrontendController extends Controller
{
    function __construct(RateLimiter $limiter)
    {
        $this->middleware(function ($request, $next) use ($limiter) {
            if (get_option('site_maintenance') == 'Y')
                if (!$request->user()) {
                    return undermaintenance();
                }
            $visitor = VisitorController::lastvisit();
            $this->counted = VisitorController::visitor_counter($visitor);
            View::share('visitor', VisitorController::hitStats($visitor));

            if ($limiter->tooManyAttempts('page'.getRateLimiterKey($request), get_option('time_limit_reload') ?? 3)) {
                abort(429);
            }
            $limit = get_option('limit_duration') ?? 60;
            $limiter->hit('page'.getRateLimiterKey($request), (int) $limit);

            if (str()->contains(str()->lower(url()->full()), explode(",", str_replace(" ","",get_option('forbidden_keyword')??'')))) {
                if ($redirect = get_option('forbidden_redirect'))
                    return redirect($redirect);
                abort(403);
            }

            if(get_option('block_ip') && in_array($request->ip(),explode(",",get_option('block_ip')))){
                abort(403);
            }
            return $next($request);
        });
    }
    public function home(Request $req)
    {
        return get_option('home_page') == 'default' ? view('views::layouts.master') : view('custom_view.' . get_option('home_page'));
    }

    public function api(Request $req, Post $post, $id=null){
        abort_if(get_option('allow_api_request') && !in_array($req->ip(), explode(",", get_option('allow_api_request'))),403);
        if($id){
            return response([
                'code'=>200,
                'status'=>"success",
                'data'=>$post->with('user')->whereStatus('publish')->findOrFail($id)
            ],200);
        }
        return response([
            'code'=>200,
            'status'=>"success",
            'data'=>$post->index(get_post_type(), true)
        ],200);

    }
    public function index(Post $post)
    {
        $modul = get_module(get_post_type());
        config(['modules.page_name' => 'Daftar ' . $modul->title]);
        $data = array(
            'index' => $post->index(get_post_type(), true),
            'title' => $modul->title,
            'icon' => $modul->icon,
            'post_type' => get_post_type()
        );
        return view('views::layouts.master', $data);
    }
    public function detail(Request $request, Post $post, $slug = false)
    {
        $modul = get_module(get_post_type());
        $detail = $post->detail(get_post_type(), $slug);
        abort_if(empty($detail), '404');
        if($request->comment_sender){
            $detail->comments()->create([
                'name' => strip_tags($request->name),
                'email' => strip_tags($request->email),
                'content' => nl2br(strip_tags($request->content)),
                'link' => strip_tags($request->link),
            ]);
            $request->session()->regenerateToken();
            return back()->with('success', 'Tanggapan Berhasil Dikirim');
        }
        if ($detail->slug != $slug)
            return redirect($detail->url);
        if ($this->counted) {
            $detail->increment('visited');
        }
        if ($detail->redirect_to)
            return redirect($detail->redirect_to);
        config(['modules.data' => $detail]);
        if ($detail->mime == 'html') {
            return view('custom_view.' . $detail->id, compact('detail'));
        }
        $data = array('icon' => $modul->icon, 'title' => $modul->title, 'post_type' => $modul->name, 'detail' => $detail, 'history' => $post->history($detail->id, $detail->created_at));
        return view('views::layouts.master', $data);


    }
    public function group(Group $group, Post $post, $slug = null)
    {
        $modul = get_module(get_post_type());

        $group = $group->whereType(get_post_type())->whereStatus(1)->where('slug', 'like', $slug . '%')->first();
        abort_if(empty($group), '404');
        if ($group->slug != $slug)
            return redirect($group->url);
        config(['modules.page_name' => 'Daftar ' . $modul->title . ' dikategori ' . $group->name]);
        $data = array(
            'index' => paginate($post->index_by_group(get_post_type(), $slug)), 'title' => $group->name,
            'icon' => $modul->icon,
            'post_type' => $modul->name);
        return view('views::layouts.master', $data);
    }
    public function search(Request $request, Post $post, $slug = null)
    {
        if ($request->keyword)
            return redirect('search/' . Str::slug($request->keyword));
        abort_if(empty($slug), '404');
        $query = str_replace('-', ' ', Str::slug($slug));
        $type = collect(get_module())->where('public', true)->where('detail', true)->where('index', true)->pluck('name');
        $index = $post->with('user', 'group')
            ->wherein('type', $type)
            ->where('title', 'like', '%' . $query . '%')
            ->orwhere('keyword', 'like', '%' . $query . '%')
            ->orwhere('description', 'like', '%' . $query . '%')
            ->where('status', 'publish')
            ->whereNotIn('type', ['halaman'])
            ->latest('created_at')
            ->paginate(get_option('post_perpage'));
        $data = array(
            'title' => ucwords($query),
            'icon' => 'fa-search',
            'index' => $index
        );
        return view('views::layouts.master', $data);
    }

    public function post_parent(Post $post, $slug = null)
    {
        $modul = get_module(get_post_type());
        abort_if(empty($slug), '404');
        $post_parent = $post->where('type', $modul->post_parent[1])
            ->where('slug', 'like', $slug . '%')->select('id', 'title', 'slug')->first();
        abort_if(empty($post_parent), '404');
        if ($post_parent->slug != $slug)
            return redirect(get_post_type() . '/' . request()->segment(2) . '/' . $post_parent->slug);
        $title = $post_parent->title;
        $post_name = $modul->title;
        config(['modules.page_name' => 'Daftar ' . $post_name . ' ' . $title]);
        $index = $post->index_child($post_parent->id, get_post_type());
        $data = array('index' => $index, 'title' => $post_name . ' ' . $title, 'icon' => $modul->icon, 'post_type' => get_post_type());
        return view('views::layouts.master', $data);

    }
    public function archive(Request $request, Post $post, $year = null, $month = null, $date = null)
    {
        if ($year && !$month && !$date) {
            if (is_year($year)) {
                $periode = $year;
                $data = $post->whereType(get_post_type())->whereStatus('publish')->whereYear('created_at', $year)->paginate(get_option('post_perpage'));

            } else {
                return redirect(get_post_type());

            }
        } elseif ($year && $month && !$date) {
            if (is_year($year) && is_month($month)) {
                $periode = blnindo($month) . ' ' . $year;
                $data = $post->whereType(get_post_type())
                    ->whereStatus('publish')
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->paginate(get_option('post_perpage'));

            } else {
                return redirect(get_post_type());

            }

        } elseif ($year && $month && $date) {
            if (is_year($year) && is_month($month) && is_day($date)) {
                $periode = ((substr($date, 0, 1) == '0') ? substr($date, 1, 2) : $date) . ' ' . blnindo($month) . ' ' . $year;
                $data = $post->whereType(get_post_type())->whereStatus('publish')
                    ->whereDate('created_at', $year . '-' . $month . '-' . $date)
                    ->paginate(get_option('post_perpage'));
            } else {
                return redirect(get_post_type());
            }
        } else {
            abort('404');
        }

        $data = array(
            'title' => 'Arsip ' . get_module(get_post_type())->title . ' ' . $periode,
            'icon' => 'fa-archive',
            'index' => $data
        );
        config(['modules.page_name' => $data['title']]);
        return view('views::layouts.master', $data);

    }
}
