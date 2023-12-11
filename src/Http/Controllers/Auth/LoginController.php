<?php
namespace Udiko\Cms\Http\Controllers\Auth;
use Udiko\Cms\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use Str;

class LoginController extends Controller
{

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
    public function captcha()
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $captchaText = substr(str_shuffle($characters), 0, 5);

    session()->forget('captcha');
    session()->put('captcha', $captchaText);

    $image = imagecreate(100, 30);
    $background_color = imagecolorallocate($image, 255, 255, 255);
    $text_color = imagecolorallocate($image, 0, 0, 0);

    imagestring($image, 5, 5,  5, $captchaText, $text_color,);
    header('Content-Type: image/x-png');
    imagepng($image);
    imagedestroy($image);
}
    public function loginForm(Request $request)
    {
        return view('views::auth.login');

    }
    public function loginSubmit(Request $request,User $user)
    {
        if($request->username && $request->password)
        {
            $credentials = $request->validate([
                'username' => 'required',
                'password' => 'required',
                // 'captcha' => 'required|in:' . session('captcha'),
            ]);
            // unset($credentials['g-recaptcha-response']);
        if(Auth::attempt($credentials))
        {
            $request->session()->regenerate();
            if(Auth::user()->status == 'Aktif'){
           Auth::user()->update(['last_login_at'=>now(),'last_login_ip'=>request()->ip()]);
            if(session()->has('urlback')){
                return redirect(session('urlback'));

            }else{

                return redirect(admin_path());

            }

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

        $back = url()->previous();
        $current = url()->current();
        if($back!=$current && Str::contains($back, admin_path()) ){
            session(['urlback'=>$back]);
        }
    }
    }


    public function logout(Request $request)
    {
        Auth::logout();
        session()->regenerateToken();
        return redirect('/');
    }

}
