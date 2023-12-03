<?php
namespace Udiko\Cms\Http\Controllers;
use App\Http\Controllers\Controller;
use Udiko\Cms\Models\Visitor;
use Illuminate\Support\Facades\Http;
use Session;
class VisitorController extends Controller
{
    static function visitor_counter(){

        Visitor::create([
            'last_activity' => time(),
            'ip' => request()->ip(),
            'country' => 'sd',
            'browser' => self::browser(),
            'session' => Session::getId(),
            'device' => self::device(),
            'os' => self::os(),
            'page' => url()->current(),
            'reference' => request()->headers->get('referer') ?? 'Uknown',
        ]);

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
static function ipCountry($ip)
{
    // Buat permintaan ke layanan ipapi
    $response = Http::get("http://ipapi.co/".$ip."/json/");

    // Ambil data dari respons JSON
    $data = $response->json();

    // Dapatkan kode negara dari data
   return isset($data['country']) ? $data['country'] : 'Unknown';


}
}
