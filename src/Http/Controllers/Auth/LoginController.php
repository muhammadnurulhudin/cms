<?php
namespace Udiko\Cms\Http\Controllers\Auth;
use Udiko\Cms\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Cache\RateLimiter;
use Auth;
use Str;
use Session;
class LoginController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function generateCaptcha()
    {
        $captcha = Str::random(6); // Ubah panjang sesuai kebutuhan Anda
        Session::put('captcha', $captcha);

        $image = imagecreatetruecolor(120, 40);
        $bgColor = imagecolorallocate($image, 255, 255, 255);
        $textColor = imagecolorallocate($image, 0, 0, 0);

        imagefilledrectangle($image, 0, 0, 120, 40, $bgColor);
        imagettftext($image, 20, 0, 10, 30, $textColor, public_path('backend/fonts/captcha.ttf'), $captcha);

        ob_start();
        imagepng($image);
        $captchaImage = ob_get_clean();
        imagedestroy($image);
        if(!request()->headers->get('referer') ){
        // request()->session()->regenerateToken();
        return redirect(admin_path());
        }
        return response($captchaImage)->header('Content-type', 'image/png');
    }
    public function loginForm(Request $request)
    {

        return view('views::auth.login');

    }
    public function loginSubmit(Request $request,RateLimiter $limiter,User $user)
    {

        if ($limiter->tooManyAttempts('loginpage', get_option('time_limit_login') ?? 3)) {
            return abort(429);
        }
        $limit = get_option('limit_duration') ??  60;
        $limiter->hit('loginpage', (int)$limit);
        if($request->username && $request->password)
        {
            $credentials = $request->validate([
                'username' => 'required',
                'password' => 'required'
            ]);
          if($request->captcha != session('captcha')){
        return back()->with('error','Captcha Tidak Valid!');

          }
            // unset($credentials['g-recaptcha-response']);
        if(Auth::attempt($credentials))
        {
            $request->session()->regenerate();
            if(Auth::user()->status == 'Aktif'){
           Auth::user()->update(['last_login_at'=>now(),'last_login_ip'=>request()->ip()]);
            }
            else{
                Auth::logout();
                $request->session()->invalidate();
                $request->session()->regenerateToken();
                return back()->with('error','Akun telah diblokir!');
            }
        }
        $request->session()->regenerateToken();
        return back()->with('error','Akun tidak ditemukan!');
    }
    }


    public function logout(Request $request)
    {
        Auth::logout();
        session()->regenerateToken();
        return redirect('/');
    }

}
