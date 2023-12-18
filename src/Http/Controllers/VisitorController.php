<?php
namespace Udiko\Cms\Http\Controllers;

use App\Http\Controllers\Controller;
use Udiko\Cms\Models\Visitor;
use Illuminate\Support\Facades\Http;
use Session;
use Carbon\Carbon;


class VisitorController extends Controller
{
    static function visitor_counter()
    {
        if(db_connected()){

        if (!self::isDuplicateVisitor(Session::getId(), url()->current())) {
            // Simpan data pengunjung ke dalam database
            Visitor::create([
                'ip' => request()->ip(),
                'ip_location' => get_ip_info(),
                'browser' => self::browser(),
                'session' => Session::getId(),
                'device' => self::device(),
                'os' => self::os(),
                'page' => url()->current(),
                'date' => date('Y-m-d'),
                'reference' => request()->headers->get('referer') ?? '',
            ]);

            return true;
        }
    }

    }
    static function isDuplicateVisitor($session, $visitedPage)
    {
        // return true;
        // Cek apakah ada catatan pengunjung dengan IP dan halaman yang sama
        return Visitor::where('session', $session)
            ->where('page', $visitedPage)
            ->where('created_at', '>=', now()->subMinutes(5))
            ->exists();
    }
    static function browser()
    {
        $userAgent = request()->header('User-Agent');

        if (strpos($userAgent, 'MSIE') !== false) {
            $browser = 'Internet Explorer';
        } elseif (strpos($userAgent, 'Trident') !== false) {
            $browser = 'Internet Explorer';
        } elseif (strpos($userAgent, 'Firefox') !== false) {
            $browser = 'Mozilla Firefox';
        } elseif (strpos($userAgent, 'Chrome') !== false) {
            $browser = 'Google Chrome';
        } elseif (strpos($userAgent, 'Safari') !== false) {
            $browser = 'Apple Safari';
        } elseif (strpos($userAgent, 'Opera') !== false || strpos($userAgent, 'OPR') !== false) {
            $browser = 'Opera';
        } else {
            $browser = 'Unknown';
        }

        return $browser;
    }
    static function os()
    {
        $userAgent = request()->header('User-Agent');

        if (strpos($userAgent, 'Windows') !== false) {
            $os = 'Windows';
        } elseif (strpos($userAgent, 'Macintosh') !== false) {
            $os = 'Mac OS';
        } elseif (strpos($userAgent, 'Android') !== false) {
            $os = 'Android';
        } elseif (strpos($userAgent, 'iOS') !== false) {
            $os = 'iOS';
        } elseif (strpos($userAgent, 'Linux') !== false) {
            $os = 'Linux';
        } else {
            $os = 'Unknown OS';
        }
        return $os;
    }
    static function device()
    {
        $userAgent = request()->header('User-Agent');

        if (strpos($userAgent, 'Mobi') !== false) {
            $deviceType = 'Mobile';
        } else {
            $deviceType = 'Desktop';
        }

        return $deviceType;
    }

    static function hitStats(){

        $data = Visitor::select('created_at','session')->whereBetween('created_at', [Carbon::now()->subMonth()->startOfMonth(), Carbon::now()])->latest('created_at')->get();
        $uniqueSessions = $data->groupBy('session')->map(function ($group) {
            return $group->first();
        });
        $arra['online'] = $uniqueSessions->filter(function ($visitor) {
            return Carbon::parse($visitor->created_at)->isAfter(now()->subMinutes(2));
        })->count();

        //Menghitung pengunjung hari ini
        $arra['today']  = $data->filter(function ($visitor) {
            return Carbon::parse($visitor->created_at)->isToday();
        })->count();

        //Menghitung pengunjung kemarin
        $arra['yesterday']  = $data->filter(function ($visitor) {
            return Carbon::parse($visitor->created_at)->isYesterday();
        })->count();

        //Menghitung pengunjung bulan ini
        $arra['this_month']  = $data->filter(function ($visitor) {
            return Carbon::parse($visitor->created_at)->isCurrentMonth();
        })->count();

        //Menghitung pengunjung bulan kemarin
        $arra['last_month'] = $data->filter(function ($visitor) {
            return Carbon::parse($visitor->created_at)->subMonth()->isCurrentMonth();
        })->count();

        return $arra;
    }

}
