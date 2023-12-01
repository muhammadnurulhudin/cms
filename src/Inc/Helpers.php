<?php

if (!function_exists('tanggal_indo')) {
    function tanggal_indo($date)
    {
        return $date . 'ok';
    }
}
if (!function_exists('size_as_kb')) {
    function size_as_kb($size = 0)
    {
        if ($size < 1024) {
            return "$size bytes";
        } elseif ($size < 1048576) {
            $size_kb = round($size / 1024, 2);
            return "$size_kb KB";
        } else {
            $size_mb = round($size / 1048576, 2);
            return "$size_mb MB";
        }
    }
}
if (!function_exists('size')) {
    function size($file)
    {
        return file_exists(public_path($file)) ? size_as_kb(File::size(public_path($file))) : 0;
    }
}
if (!function_exists('media_store')) {

    function media_store($parent, $mime, $path, $name, $title)
    {
        $ext = explode('.', $name)[1];
        if (Str::of($mime)->contains('image')) {
            $data = getimagesize(public_path($path));
            $width = $data[0];
            $height = $data[1];
        }
        request()->user()->post()->create([
            'parent' => $parent,
            'title' => $name,
            'type' => $mime,
            'url' => $path,
            'slug' => $name,
            'type' => 'media',
            'status' => 'publish',
            'mime' => $ext,
            'data_field' => json_encode(['ukuran' => size($path), 'width' => $width ?? null, 'height' => $height ?? null, 'extension' => $ext])
        ]);
    }
}
if (!function_exists('help')) {
    function help($val)
    {
        return '<i class="fa fa-question-circle pointer" data-toggle="tooltip" title="' . $val . '" aria-hidden></i>';
    }
}

if (!function_exists('thumb')) {
    function thumb($src = false)
    {
        if ($src && !is_dir(public_path($src))):
            if (file_exists(public_path($src))) {
                return url($src);
            } else {
                return url('backend/images/noimage.png');
            }
        else:
            return url('backend/images/noimage.png');
        endif;
    }
}
if (!function_exists('allowed_ext')) {
    function allowed_ext($ext = false)
    {
        $allowed = array('gif', 'png', 'jpeg', 'jpg', 'zip', 'docx', 'doc', 'rar', 'pdf', 'xlsx', 'xls');
        if ($ext) {
            if (in_array($ext, $allowed)) {
                if (in_array($ext, ['gif', 'png', 'jpg', 'jpeg'])) {
                    return 'image';
                } else {
                    return 'file';
                }
            } else {
                return false;
            }
        } else {
            return implode(',', $allowed);
        }
    }
}
if (!function_exists('cache_content_initial')) {
    function cache_content_initial()
    {
        if (!Cache::has('post')) {
            regenerate_cache();
        }
        if (!Cache::has('option')) {
            recache_option();
        }
    }
}
if (!function_exists('underscore')) {
    function underscore($val)
    {
        return strtolower(preg_replace('/[^A-Za-z0-9\-]/', '_', trim($val)));
    }
}
if (!function_exists('get_module_info')) {
    function get_module_info($val)
    {
        return $val ? (get_module(get_post_type())->$val ?? '') : '';
    }
}
if (!function_exists('active_item')) {
    function active_item($val)
    {
        if (Request::is(admin_path() . '/' . $val) || Request::is(admin_path() . '/' . $val . '/*') || Request::is(admin_path() . '/' . $val . '/*/*'))
            return 'active';
    }
}
if (!function_exists('admin_url')) {
    function admin_url($path = false)
    {
        return $path ? url(admin_path() . '/' . $path) : url(admin_path());
    }
}
if (!function_exists('regenerate_cache')) {
    function regenerate_cache()
    {
        //forget cache post
        $post_type = collect(config('modules.used'))->where('auto_load', true)->pluck('name');
        $cache_post = Udiko\Cms\Models\Post::with(['user', 'comments', 'group'])->whereIn('type', $post_type)->whereStatus('publish')->latest('created_at')->get();
        Cache::put('post', $cache_post, now()->addMinutes(30));
        Cache::put(
            'group',
            \Udiko\Cms\Models\Group::withwherehas('post', function ($q) {
                $q->whereStatus('publish');
            })->whereStatus(1)->get()
            ,
            now()->addMinutes(30));

    }
}
if (!function_exists('recache_option')) {
    function recache_option()
    {
        Cache::put('option', \Udiko\Cms\Models\Option::get());
    }
}

if (!function_exists('fcm_send_notification')) {
    function fcm_send_notification($r)
    {

        $serverKey = "AAAAEJeRaPA:APA91bG3edN8yeAioMRp-4LIAM6yYzNmL9VgJY_dpXm2Xsp1ekdj9NwIYsQkYStrVyYbyglaNPl2CJ6ZqnDeBhlos8WH47_sjLqWG6GirDZmVPhTwJ9ZgyJdxbbdAtwQo9ZIscYaAxGZ";

        $headers = [
            'Authorization: key=' . $serverKey,
            'Content-Type: application/json'
        ];


        $notification = [
            "title" => "Notif",
            "body" => "Sekret",
            "sound" => "default",
        ];

        $data = [

            "msg" => $r['msg'],
            "number" => $r['nohp'],
            "type" => "msg",
        ];

        $fcmNotification = [
            "to" => "/topics/freesms",
            "priority" => "high",
            "notification" => $notification,
            "data" => $data,
            "priority" => 10
        ];
        //  dd($fcmNotification);
        // sendPushnotification($headers, $fcmNotification);
    }
}

if (!function_exists('rewrite_env')) {
    function rewrite_env(array $keyPairs)
    {
        $envFile = app()->environmentFilePath();
        $newEnv = file_get_contents($envFile);

        $newlyInserted = false;

        foreach ($keyPairs as $key => $value) {
            // Make sure key is uppercase (can be left out)
            $key = Str::upper($key);

            if (str_contains($newEnv, "$key=")) {
                // If key exists, replace value
                $newEnv = preg_replace("/$key=(.*)\n/", "$key=$value\n", $newEnv);
            } else {
                // Check if spacing is correct
                if (!str_ends_with($newEnv, "\n\n") && !$newlyInserted) {
                    $newEnv .= str_ends_with($newEnv, "\n") ? "\n" : "\n\n";
                    $newlyInserted = true;
                }
                // Append new
                $newEnv .= "$key=$value\n";
            }
        }

        $fp = fopen($envFile, 'w');
        fwrite($fp, $newEnv);
        fclose($fp);
    }
}

if (!function_exists('get_option')) {
    function get_option($val = false)
    {
        if ($val) {
            $c = Cache::get('option') ? collect(Cache::get('option'))->where('name', $val)->first() : null;
            return $c ? ($c['autoload'] == 1 ? $c['value'] : (Udiko\Cms\Models\Option::whereName($val)->first()?->value ?? null)) : null;
        }
    }
}

if (!function_exists('admin_path')) {
    function admin_path()
    {
        return get_option('admin_path') ?? 'admin';
    }
}

if (!function_exists('add_module')) {
    function add_module($array)
    {
        $data = config('modules.used');
        if (!empty(collect($data)->where('name', $array['name'])->first())) {
            foreach (collect($data)->where('name', $array['name']) as $key => $row):
                $data[$key] = $array;
            endforeach;
        } else {
            array_push($data, $array);

        }
        config(['modules.used' => $data]);
    }
}
if (!function_exists('is_admin')) {
    function is_admin()
    {
        return Auth::user()->level == 'admin' ? true : false;
    }
}
if (!function_exists('use_module')) {
    function use_module($module_selected)
    {
        foreach ($module_selected as $module => $attr) {
            if (config('modules.menu.' . $module)) {
                if ($attr) {
                    if (is_array($attr)) {
                        foreach ($attr as $attr_key => $attr_value) {
                            if (in_array($attr_key, array_keys(config('modules.menu.' . $module)))) {
                                config(['modules.menu.' . $module . '.' . $attr_key => $attr_value]);
                            }
                        }
                    }
                    add_module(config('modules.menu.' . $module));

                }
            }
        }
    }
}
if (!function_exists('get_module')) {
    function get_module($name = false)
    {
        $module = config('modules.used');
        if ($name) {
            return json_decode(json_encode(collect($module)->where('name', $name)->where('active', true)->first()));
        } else {
            return json_decode(json_encode(collect($module)->where('active', true)->sort()));
        }
    }
}
if (!function_exists('get_view')) {

    function get_view($blade = false)
    {
        if ($blade) {
            return 'template.' . template() . '.' . $blade;
        } else {
            return get_post_type('view_path');
        }
    }
}
if (!function_exists('blade_path')) {
    function blade_path($blade)
    {
        $blades = 'template.' . template() . '.' . $blade;
        if (View::exists($blades)) {
            return $blades;
        } else {
            $path = resource_path('views\template\\' . template() . '\\' . $blade . '.blade.php') . ' Not Found<br> ';
            View::share('blade', $path);
            return 'views::layouts.warning';

        }
    }
}
if (!function_exists('template')) {
    function template()
    {
        return get_option('template');
    }
}
if (!function_exists('strip_to_underscore')) {

    function strip_to_underscore($val)
    {
        return str_replace('-', '_', $val);
    }
}
if (!function_exists('get_post_type')) {
    function get_post_type($attr = false)
    {
        $modul = config('modules.current');
        return $attr ? ($modul[$attr] ?? null) : ($modul['post_type'] ?? null);
    }
}
if (!function_exists('is_month')) {

    function is_month($month)
    {
        $months = (substr($month, 0, 1) == 0) ? substr($month, 1, 2) : $month;
        if (strlen($month) == 2 && is_numeric($month) && $months > 0 && $months <= 12)
            return true;
    }
}
if (!function_exists('is_year')) {
    function is_year($year)
    {
        if (strlen($year) == 4 && is_numeric($year) && $year > 2000 && $year < 2050)
            return true;
    }
}
if (!function_exists('delete_post_url')) {
    function delete_post_url($id)
    {
        return admin_url(get_post_type() . '/delete/' . $id);
    }
}
if (!function_exists('edit_post_url')) {
    function edit_post_url($id)
    {
        return admin_url(get_post_type() . '/edit/' . $id);
    }
}
if (!function_exists('is_day')) {
    function is_day($day)
    {
        $days = (substr($day, 0, 1) == 0) ? substr($day, 1, 2) : $day;
        if (strlen($day) == 2 && is_numeric($day) && $days > 0 && $days <= 31)
            return true;
    }
}
if (!function_exists('set_header_seo')) {
    function set_header_seo($data)
    {
        return array(
            'description' => (!empty($data->description)) ? $data->description : ($data->post_type == 'halaman' || strlen(strip_tags($data->content)) == 0 ? 'Baca informasi tentang ' . $data->title : Str::limit($data->content, 350)),
            'keywords' => (!empty($data->keyword)) ? $data->keyword : $data->keyword,
            'title' => $data->title,
            'thumbnail' => (!empty($data->thumbnail) && !is_dir(public_path($data->thumbnail))) ? asset($data->thumbnail) : url(get_option('logo')),
            'url' => (!empty($data->url)) ? url($data->url) : url('/'),
        );
    }
}
if (!function_exists('init_header')) {
    function init_header()
    {
        $get_page_name = config('modules.page_name');
        $data = config('modules.data') ?? false;
        $site_title = get_option('site_title');
        $site_desc = get_option('site_description');
        $site_meta_keyword = get_option('site_meta_keyword');
        $site_meta_description = get_option('site_meta_description');
        if ($data) {
            $data['site_meta_keyword'] = $site_meta_keyword;
            return View::make('views::layouts.seo', set_header_seo($data));
        } else {
            $page = request()->page ? ' Halaman ' . request()->page : '';

            if (get_post_type() && !request()->is('search/*') && !request()->is('/')) {

                if (request()->segment(2) == 'archive') {
                    $pn = $get_page_name . $page;

                } elseif (request()->segment(2) == 'category') {
                    $pn = $get_page_name . $page;
                } elseif (get_module(get_post_type())->post_parent) {
                    $pn = $get_page_name . $page;
                } else {
                    $pn = $get_page_name . $page;
                }

            } elseif (request()->is('search/*')) {
                $pn = 'Hasil Pencarian  "' . ucwords(str_replace('-', ' ', request()->q)) . '"' . $page;
            } elseif (request()->is('author') || request()->is('author/*')) {
                $pn = $get_page_name . $page;
            } else {
                $pn = null;
            }
            $data = [
                'description' => $pn ? 'Lihat ' . $pn . ' di ' . $site_title : $site_meta_description,
                'title' => $pn ? $pn : (!request()->is('/') ? '404 Halaman Tidak Ditemukan' : $site_title . ($site_desc ? ' - ' . $site_desc : '')),
                'keywords' => $site_meta_keyword,
                'thumbnail' => url(get_option('logo')),
                'url' => URL::full(),
            ];

            return View::make('views::layouts.seo', $data ?? [null]);
        }
    }
}
if (!function_exists('kaedah')) {
    function kaedah()
    {
        return array(
            [
                'position' => 1,
                'name' => 'berita',
                'title' => 'Berita',
                'description' => 'Menu Untuk Mengelola Berita',
                'parent' => false,
                'icon' => 'fa-newspaper-o',
                'data_title' => 'Judul Berita',
                'custom_column' => false,
                'post_parent' => false,
                'custom_field' => array(
                    ['Reporter', 'text'],
                    ['Tanggal Entry', 'text']
                ),
                'looping' => false,
                'looping_data' => false,
                'looping_for' => 'Di Isi Hanya Untuk Kecamatan',
                'thumbnail' => true,
                'editor' => true,
                'group' => true,
                'api' => true,
                'archive' => true,
                'index' => true,
                'detail' => true,
                'operator' => true,
                'public' => true,
                'history' => true,
                'auto_query' => false,
                'auto_load' => true,
                'active' => true,

            ],
            [
                'position' => 2,
                'name' => 'agenda',
                'title' => 'Agenda',
                'description' => 'Menu Untuk Mengelola Agenda',
                'parent' => false,
                'icon' => 'fa-calendar',
                'data_title' => 'Nama Agenda',
                'custom_column' => false,
                'post_parent' => false,
                'custom_field' => array(
                    ['Tanggal', 'date', 'required'],
                    ['Tempat', 'text', 'required'],
                    ['Alamat', 'text']
                ),
                'looping' => false,
                'looping_data' => false,
                'looping_for' => 'Silahkan Isi Lampiran',
                'thumbnail' => false,
                'editor' => false,
                'group' => false,
                'api' => false,
                'archive' => false,
                'index' => true,
                'detail' => true,
                'operator' => false,
                'public' => true,
                'history' => false,
                'auto_query' => false,
                'auto_load' => true,
                'active' => true,

            ]
        );
    }
}
if (!function_exists('load_default_module')) {
    function load_default_module()
    {
        use_module(['berita' => ['position' => 1], 'halaman' => ['custom_field' => false], 'agenda' => ['position' => 2], 'sambutan' => ['position' => 6], 'download' => ['position' => 3], 'menu' => true, 'banner' => ['auto_load' => true], 'foto' => ['position' => 4], 'video' => ['position' => 5], 'media' => ['position' => 7, 'icon' => 'fa-link']]);

    }
}
