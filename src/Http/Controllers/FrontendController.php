<?php
namespace Udiko\Cms\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Udiko\Cms\Http\Controllers\VisitorController;
use Illuminate\Session\Events\SessionStarted;
class FrontendController extends Controller
{
    function __construct(){
        $this->middleware(function ($request, $next){
        VisitorController::visitor_counter();
        return $next($request);
      });
    }
    public function home(Request $req)
    {
        return view('views::layouts.master');
    }
    public function index()
    {
        config(['modules.page_name' => 'Daftar ' . get_module(get_post_type())->title]);
        return view('views::layouts.master');
    }
    public function detail(Request $request, \Udiko\Cms\Models\Post $post, $slug = false)
    {
        $detail = $post->detail(get_post_type(), $slug);
        if (empty($detail))
            abort('404');
        if ($detail->slug != $slug)
            return redirect($detail->url);


        config(['modules.data' => $detail]);
        return view('views::layouts.master');


    }
    public function group()
    {

        return get_post_type('view_path');

    }
    public function search(Request $request, $slug = null)
    {

        if ($slug) {
            return $slug;
        }
        abort('404');

    }

    public function post_parent(Request $request, $slug = null)
    {

        return $slug ?? 'Pilih .';

    }
    public function archive(Request $request, \Udiko\Cms\Models\Post $post, $year = null, $month = null, $date = null)
    {
        $valid = 1;
        if (!$year && !$month && !$date)
            return 'data archive 0';

        if ($year && !$month && !$date)
            if (is_year($year)) {
                $data = $post->whereStatus('publish')->whereYear('created_at', $year)->paginate(10);
            } else {
                return redirect(get_post_type());

            }
        if ($year && $month && !$date)
            if (is_year($year) && is_month($month)) {
                $data = $post->whereStatus('publish')
                    ->whereYear('created_at', $year)
                    ->whereMonth('created_at', $month)
                    ->paginate(10);

            } else {
                return redirect(get_post_type());

            }

        if ($year && $month && $date)
            if (is_year($year) && is_month($month) && is_day($date)) {
                $data = $post->whereStatus('publish')
                    ->whereDate('created_at', $year . '-' . $month . '-' . $date)
                    ->paginate(10);
            } else {
                return redirect(get_post_type());
            }

        return $data;

    }
}
