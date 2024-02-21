<?php
namespace Udiko\Cms\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Auth;

class NoSession extends Controller
{

    function index()
    {
        if (Auth::check()) {
        return to_route('dashboard');
        } else {
            abort('404');
        }
    }
}
