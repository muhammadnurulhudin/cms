<?php
    $info = config('modules.domain');
    $admin_path = $info ? (_field($info,'login_path') ?? 'admin' ) : (admin_path()??'admin');
    // Route::get('setup', [Udiko\Cms\Http\Controllers\SetupController::class, 'index']);
    Route::get($admin_path.'/login' , [Udiko\Cms\Http\Controllers\Auth\LoginController::class, 'loginForm'])->middleware('web')->name('login');
    Route::get($admin_path.'/captcha', [Udiko\Cms\Http\Controllers\Auth\LoginController::class, 'generateCaptcha'])->middleware('web')->name('captcha');
    Route::post($admin_path.'/login' , [Udiko\Cms\Http\Controllers\Auth\LoginController::class, 'loginSubmit'])->middleware('web')->name('login.submit');
    Route::match(['post', 'get'], $admin_path.'/logout', [Udiko\Cms\Http\Controllers\Auth\LoginController::class, 'logout'])->middleware('web')->name('logout');
    Route::get($admin_path.'/dashboard', [Udiko\Cms\Http\Controllers\Auth\NoSession::class, 'index'])->name('dashboard');
    Route::get($admin_path , [Udiko\Cms\Http\Controllers\Auth\LoginController::class, 'loginForm'])->middleware('auth');
    Route::get('home', [Udiko\Cms\Http\Controllers\Auth\NoSession::class, 'index'])->middleware('web');

if(!config('modules.domain')):
if (request()->segment(1) == $admin_path && !in_array(request()->segment(2), ['login', 'logout'])) {

    Route::controller(\Udiko\Cms\Http\Controllers\BackendController::class)
        ->prefix($admin_path)
        ->middleware('web')
        ->group(function () use ($admin_path) {

            foreach (get_module() as $value) {
                if (request()->segment(2) == $value->name) {


                    if ($value->crud) {
                        if (in_array('read', $value->crud)) {
                            Route::get($value->name, 'index')->name($value->name . '.index');
                            Route::match(['get', 'post'], $value->name . '/dataindex', 'dataindex');
                        }
                        if (in_array('create', $value->crud)) {
                            Route::get($value->name . '/create', 'form');
                        }
                        if (in_array('update', $value->crud)) {
                            Route::match(['get', 'post'], $value->name . '/edit/{id}', 'form');
                        }
                        if (in_array('delete', $value->crud)) {
                            Route::get($value->name . '/delete/{id}', 'delete');
                        }
                    }

                    if ($value->group) {
                        Route::match(['get', 'post'], $value->name . '/group', 'group');
                        Route::get($value->name . '/group/delete/{ids}', 'group');
                    }
                    if ($value->editor) {
                        Route::match(['get', 'post'], $value->name . '/upload_image/{id}', 'summer_image_upload');
                        Route::match(['get', 'post'], $value->name . '/upload_file/{id}', 'summer_file_upload');
                    }
                    $title = match (true) {
                        Request::is($admin_path . '/' . $value->name . '/edit/*') => 'Edit ' . $value->title,
                        Request::is($admin_path . '/' . $value->name) => $value->title,
                        default => 'Kategori ' . $value->title,
                    };
                    config([
                        'modules.current' => [
                            'post_type' => $value->name,
                            'title_crud' => $title,
                        ]
                    ]);
                }
            }
            Route::match(['get', 'post'], 'dashboard', 'dashboard');
            Route::match(['get', 'post'], 'comments', 'comments');
            Route::match(['get', 'post'], 'unlink', 'delfile');
            Route::match(['get', 'post'], 'visitor', 'visitor');
            Route::match(['get', 'post'], 'setting', 'setting');
            Route::match(['get', 'post'], 'users', 'users');
            Route::match(['get', 'post'], 'account', 'account');
            Route::match(['get', 'post'], 'template', 'template');
        });
} elseif (request()->segment(1) && $modul = collect(get_module())->where('public', true)->where('name', request()->segment(1))->first()) {
    if ($modul->name != 'halaman') {
        Route::controller(\Udiko\Cms\Http\Controllers\FrontendController::class)
            ->prefix($modul->name)
            ->middleware('web')
            ->group(function () use ($modul) {
                $attr['post_type'] = $modul->name;

                if ($modul->index && request()->is($modul->name)) {
                    $attr['view_type'] = 'index';
                    $attr['view_path'] = $modul->name . '.index';
                    Route::get('/', 'index');
                }
                if ($modul->archive && (request()->is($modul->name . '/archive') || request()->is($modul->name . '/archive/*') || request()->is($modul->name . '/archive/*/*') || request()->is($modul->name . '/archive/*/*/*'))) {
                    $attr['view_type'] = 'archive';
                    $attr['view_path'] = $modul->name . '.archive';
                    Route::get('archive/{year?}/{month?}/{date?}', 'archive');
                }
                if ($modul->post_parent && (request()->is($modul->name . '/' . $modul->post_parent[1]) || request()->is($modul->name . '/' . $modul->post_parent[1] . '/*'))) {
                    $attr['view_type'] = 'post_parent';
                    $attr['view_path'] = $modul->name . '.post_parent';
                    Route::get('/' . $modul->post_parent[1] . '/{slug?}', 'post_parent');
                }
                if ($modul->api) {
                    Route::get('api_data/{id?}', 'api');
                }
                if ($modul->detail && request()->is($modul->name . '/*')) {
                    $attr['view_type'] = 'detail';
                    $attr['view_path'] = $modul->name . '.detail';
                    Route::match(['get','post'],'/{slug}', 'detail');

                }
                if ($modul->group && request()->is($modul->name . '/category/*')) {
                    $attr['view_type'] = 'group';
                    $attr['view_path'] = $modul->name . '.group';
                    Route::get('/category/{slug}', 'group');
                }

                config([
                    'modules.current' => $attr
                ]);
            });
    }

} elseif (request()->segment(1) && request()->segment(1) == 'search') {
    $attr['post_type'] = null;
    $attr['view_type'] = 'search';
    $attr['view_path'] = 'search';
    config([
        'modules.current' => $attr
    ]);
    Route::match(['get', 'post'], 'search/{slug?}', [\Udiko\Cms\Http\Controllers\FrontendController::class, 'search'])->middleware('web');


} elseif (request()->segment(1)) {

    $attr['post_type'] = 'halaman';
    $attr['view_type'] = 'detail';
    $attr['view_path'] = 'halaman.detail';
    Route::match(['get','post'],'/{slug}', [\Udiko\Cms\Http\Controllers\FrontendController::class, 'detail'])->middleware('web');
    config([
        'modules.current' => $attr
    ]);
} else {
    $attr['post_type'] = null;
    $attr['view_type'] = 'home';
    $attr['view_path'] = 'home';
    config([
        'modules.current' => $attr
    ]);
    Route::get('/', [\Udiko\Cms\Http\Controllers\FrontendController::class, 'home'])->middleware('web');

}

endif;

Route::get($admin_path,function(){
    return to_route('login');
});
