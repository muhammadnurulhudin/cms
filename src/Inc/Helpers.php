<?php

if (!function_exists('tanggal_indo')) {
    function tanggal_indo($val,$with0=false)
    {

  $waktu = date('Y-m-d', strtotime($val));
  $hari_array = array(
      'Minggu',
      'Senin',
      'Selasa',
      'Rabu',
      'Kamis',
      'Jumat',
      'Sabtu'
  );
  $hr = date('w', strtotime($waktu));
  $hari = $hari_array[$hr];
  if($with0==true){
  $tanggal = date('d', strtotime($waktu));
  }else{
  $tanggal = date('j', strtotime($waktu));
  }
  $bulan_array = array(
      1 => 'Januari',
      2 => 'Februari',
      3 => 'Maret',
      4 => 'April',
      5 => 'Mei',
      6 => 'Juni',
      7 => 'Juli',
      8 => 'Agustus',
      9 => 'September',
      10 => 'Oktober',
      11 => 'November',
      12 => 'Desember',
  );

  $bl = date('n', strtotime($waktu));
  $bulan = $bulan_array[$bl];
  $tahun = date('Y', strtotime($waktu));
  $jam = date('H:i T', strtotime($val));

  //untuk menampilkan hari, tanggal bulan tahun jam
  //return "$hari, $tanggal $bulan $tahun $jam";

  //untuk menampilkan hari, tanggal bulan tahun
  return $hari.", ".$tanggal." ".$bulan." ".$tahun;
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
if (!function_exists( 'clear_route')) {
    function clear_route(){
    $data = '';
    $path = base_path('routes');
    if(!is_dir($path)){
      mkdir($path);
    }
    $file = $path.'/web.php';
    $myfile = fopen($file, "w") or die("Unable to open file!");
    fwrite($myfile, $data);
    fclose($myfile);
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
    function get_module_info($val,$post_type = false)
    {
        return $val ? (get_module($post_type ?$post_type : get_post_type() )->$val ?? '') : '';
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
        $cache_post = \Udiko\Cms\Models\Post::with(['user', 'comments', 'group'])->whereIn('type', $post_type)->whereStatus('publish')->latest('created_at')->get();
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
if (!function_exists('_field')) {
    function _field($r, $k, $link = false)
    {
        $data = $r->data_field;
        return (isset(json_decode($data, true)[$k])) ? ($link ? (Str::contains(json_decode($data)->$k, 'http') ? '<a href="' . strip_tags(json_decode($data)->$k) . '">' . str_replace(['http://', 'https://'], '', json_decode($data)->$k) . '</a>' : json_decode($data)->$k) : json_decode($data)->$k) : NULL;
    }
}

if (!function_exists('getlistmenu')) {
    function getlistmenu($menu, $menulist)
    {
        $me = $menu;
        $m = '';
        foreach (json_decode(json_encode($menulist)) as $key => $value) {
            $m .= '
    <li class="dd-item dd3-item menu-id-' . $value->id . '" data-id="' . $value->id . '">
    <input type="hidden" name="id[]" value="' . $value->id . '">
    <input type="hidden" name="parent[]" value="' . $value->parent . '">
    <input type="hidden" class="name-' . $value->id . '" name="name[]" value="' . $value->name . '">
    <input type="hidden" class="desc-' . $value->id . '" name="description[]" value="' . $value->description . '">
    <input type="hidden" class="link-' . $value->id . '" name="link[]" value="' . $value->link . '">
    <input type="hidden" class="icon-' . $value->id . '" name="icon[]" value="' . $value->icon . '">
      <div style="cursor:move" class="dd-handle dd3-handle"></div><div class="dd3-content">' . $value->name . ' <i class="fa fa-angle-right" aria-hidden></i>  <code><i>' . $value->link . '</i></code><span style="float:right"><a href="javascript:void(0)" onclick="$(\'.link\').val(\'' . $value->link . '\');$(\'.description\').val(\'' . $value->description . '\');$(\'.name\').val(\'' . $value->name . '\');$(\'.iconx\').val(\'' . $value->icon . '\');$(\'#type\').val(\'' . $value->id . '\');$(\'.modal\').modal(\'show\')" class="text-warning"> <i class="fa fa-edit" aria-hidden=""></i> </a> &nbsp; <a href="javascript:void(0)" onclick="del_menu(\'' . $value->id . '\')" class="text-danger"> <i class="fa fa-trash" aria-hidden=""></i> </a></span></div>
      ' . ceksubmenu($me, $value->id) . '
    </li>
    ';
        }
        return $m;
    }
}
if (!function_exists('rnd')) {
    function rnd($length)
    {
        $str = "";
        $characters = '0123456789';
        $max = strlen($characters) - 1;
        for ($i = 0; $i < $length; $i++) {
            $rand = mt_rand(0, $max);
            $str .= $characters[$rand];
        }
        return $str;
    }
}
if (!function_exists('ceksubmenu')) {
    function ceksubmenu($menu, $id)
    {
        $cek = $menu->where('parent', $id);
        if (count($cek) > 0) {
            $m = '<ol class="dd-list">';
            $m .= getlistmenu($menu, $cek);
            $m .= '</ol>';
            return $m;
        } else {
            return null;
        }
    }
}
if (!function_exists('_loop')) {
    function _loop($r)
    {
        return (!empty($r->data_loop)) ? json_decode($r->data_loop) : array();
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
if (!function_exists( 'blnindo')) {
    function blnindo($month)
    {
        $months = (substr($month, 0, 1) == 0) ? substr($month, 1, 2) : $month;
        $bulan_array = array(
            1 => 'Januari',
            2 => 'Februari',
            3 => 'Maret',
            4 => 'April',
            5 => 'Mei',
            6 => 'Juni',
            7 => 'Juli',
            8 => 'Agustus',
            9 => 'September',
            10 => 'Oktober',
            11 => 'November',
            12 => 'Desember',
        );
        return $bulan_array[$months];

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

if (!function_exists( 'template_asset')) {
    function template_asset($path = false)
    {
        return $path ? secure_asset('template/' . get_option('template') . '/' . $path) : null;
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
            'description' => !empty($data->description) ? $data->description : (strlen(strip_tags($data->content)) == 0 ? 'Lihat ' . get_module($data->type)->title . ' ' . $data->title : Str::limit($data->content, 350)),
            'keywords' => !empty($data->keyword) ? $data->keyword : $data->site_keyword,
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
        $site_meta_keyword = get_option('site_keyword');
        $site_meta_description = get_option('site_description');
        if ($data) {
            $data['site_keyword'] = $site_meta_keyword;
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
                $pn = 'Hasil Pencarian  "' . ucwords(str_replace('-', ' ', request()->slug)) . '"' . $page;
            } elseif (request()->is('author') || request()->is('author/*')) {
                $pn = $get_page_name . $page;
            } else {
                $pn = null;
            }
            $data = [
                'description' => $pn ? 'Lihat ' . $pn . ' di ' . $site_title : $site_meta_description,
                'title' => $pn ? $pn : (!request()->is('/') ? 'Halaman Tidak Ditemukan' : $site_title . ($site_desc ? ' - ' . $site_desc : '')),
                'keywords' => $site_meta_keyword,
                'thumbnail' => url(get_option('logo')),
                'url' => URL::full(),
            ];

            return View::make('views::layouts.seo', $data ?? [null]);
        }
    }
}
if (!function_exists('get_menu')) {
    function get_menu($name, $id = false)
    {
        $menu = Cache::get('post')->where('type', 'menu')->where('slug', $name)->first();
        return $id ? collect(json_decode($menu->data_loop))->where('parent', $id) : collect(json_decode($menu->data_loop))->where('parent', 0);
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
        use_module(['berita' => ['position' => 1], 'halaman' => ['custom_field' => false], 'agenda' => ['position' => 2], 'sambutan' => ['position' => 6], 'download' => ['position' => 3], 'menu' => true, 'banner' => ['auto_load' => true], 'foto' => ['position' => 4], 'media' => ['position' => 7, 'icon' => 'fa-link']]);

    }
}


if (!function_exists('paginate')) {
    function paginate($items)
    {

        $perPage = get_option('post_perpage');
        $page = request()->page ?: (\Illuminate\Pagination\Paginator::resolveCurrentPage() ?: 1);
        $items = $items instanceof \Illuminate\Support\Collection ? $items : \Illuminate\Support\Collection::make($items);
        return new \Illuminate\Pagination\LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, ['path' => url()->current()]);
    }
}
if (!function_exists('get_post')) {
    function get_post()
    {

        return new \Udiko\Cms\Models\Post;
    }
}

if (!function_exists( '_field')) {
    function _field($r, $k, $link = false)
    {
        $data = $r->data_field;
        return (isset(json_decode($data, true)[$k])) ? ($link ? (Str::contains(json_decode($data)->$k, 'http') ? '<a href="' . strip_tags(json_decode($data)->$k) . '">' . str_replace(['http://', 'https://'], '', json_decode($data)->$k) . '</a>' : json_decode($data)->$k) : json_decode($data)->$k) : NULL;
    }
}
if (!function_exists('_loop')) {
    function _loop($r)
    {
        return (!empty($r->data_loop)) ? json_decode($r->data_loop) : array();
    }
}
if (!function_exists( '_us')) {
    function _us($val)
    {
        return strtolower(preg_replace('/[^A-Za-z0-9\-]/', '_', trim($val)));
    }
}
if (!function_exists('admin_only')) {
    function admin_only()
    {
        return request()->user()->level != 'admin' ? Redirect::to(admin_path().'/dashboard')->send()->with('danger', 'Akses Terbatas untuk administrator') : true;
    }
}
if (!function_exists( '_tohref')) {
    function _tohref($href, $val)
    {
        return '<a target="_blank" href="' . strip_tags($href) . '">' . $val . '</a>';
    }
}
if (!function_exists( 'get_banner')) {
    function get_banner($start_tag, $end_tag, $position)
    {
        $post = new \Udiko\Cms\Models\Post;
        $cek = $post->index_by_group('banner', $position);
        if (count($cek) > 0) {
            $banner = '';
            foreach ($cek as $r) {
                $img = json_decode($r->data_field)->link ? '<img title="Klik untuk selengkapnya" style="width:100%;" src="' . thumb($r->thumbnail) . '">' : '<img style="width:100%;" src="' . thumb($r->thumbnail) . '">';
                $val = json_decode($r->data_field)->link ? _tohref(json_decode($r->data_field)->link, $img) : $img;
                $banner .= $start_tag . $val . $end_tag;
            }
            return $banner;
        } else {
            return null;
        }

    }
}
if (!function_exists( 'allow_mime')) {
    function allow_mime($detectmime)
    {
        $mimelist = array('application/pdf','image/jpeg','image/gif','video/mp4','application/vnd.ms-excel','application/vnd.openxmlformats-officedocument.spreadsheetml.sheet','application/vnd.ms-powerpoint','application/vnd.openxmlformats-officedocument.presentationml.presentation','application/vnd.openxmlformats-officedocument.wordprocessingml.document','application/msword','text/csv','image/png','image/x-png','application/zip','application/x-rar-compressed','image/webp','audio/x-wav');
        return in_array($detectmime, $mimelist) ? true : false;
    }
}

if (!function_exists('time_ago')) {
    function time_ago($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'tahun',
        'm' => 'bulan',
        'w' => 'minggu',
        'd' => 'hari',
        'h' => 'jam',
        'i' => 'menit',
        's' => 'detik',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? '' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' yang lalu' : 'Baru saja';
  }
}
if (!function_exists( 'get_ip_info')) {
    function get_ip_info() {
        if (env('APP_ENV') == 'production') {
            $data = \Stevebauman\Location\Facades\Location::get(request()->ip());
            return $data ? json_encode(['countryCode' => Str::lower($data->countryCode), 'country' => $data->countryName, 'city' => $data->cityName, 'region' => $data->regionName]) : json_encode(array());
        }else{
            return NULL;
        }
  }
}
if (!function_exists( 'make_custom_view')) {
    function make_custom_view($id,$content){
    $data = $content;
    $path = resource_path('views/custom_view');
    if(!is_dir($path)){
      mkdir($path);
    }
    $file = $path.'/'.$id.'.blade.php';
    $myfile = fopen($file, "w") or die("Unable to open file!");
    fwrite($myfile, $data);
    fclose($myfile);
  }
}
if (!function_exists( 'get_custom_view')) {
    function get_custom_view($id){
    if(!file_exists(resource_path('views/custom_view/'.$id.'.blade.php'))){
      file_put_contents(resource_path('views/custom_view/'.$id.'.blade.php'),'<html></html>');
    }
    $file = resource_path('views/custom_view/'.$id.'.blade.php');
  $fn = fopen($file,"r");
  $l = '';
  while(! feof($fn))  {
  $result = fgets($fn);
  $l .= $result;
  }
  fclose($fn);
  return $l;
  }
}
if (!function_exists('db_connected')) {
    function db_connected(){
    try {
        \DB::connection()->getPDO();
        return true;
        } catch (\Exception $e) {
        return false;
    }
}
}
if (!function_exists( 'getTgl')) {
    function getTgl($tanggal,$type){
    $hari_array = array(
      'Minggu',
      'Senin',
      'Selasa',
      'Rabu',
      'Kamis',
      'Jumat',
      'Sabtu'
  );
      $bulan = array (
          1 =>   'Jan',
          'Feb',
          'Mar',
          'Apr',
          'Mei',
          'Jun',
          'Jul',
          'Agu',
          'Sep',
          'Okt',
          'Nov',
          'Des'
      );
      $pecahkan = explode('-', date('d-m-Y',strtotime($tanggal)));

      // variabel pecahkan 0 = tanggal
      // variabel pecahkan 1 = bulan
      // variabel pecahkan 2 = tahun
    return match(true){
      $type == 'hari' => $hari_array[date('w', strtotime($tanggal))],
      $type == 'tahun' => $pecahkan[2],
      $type == 'bulan' => $bulan[ (int)$pecahkan[1] ],
      $type == 'tanggal' => $pecahkan[0],
      $type == 'tglbulan' => $pecahkan[0].' '.$bulan[ (int)$pecahkan[1] ] ,
      default => NULL
    };
  }
}

if (!function_exists('get_group')) {
    function get_group($array,$class=false){
    $attr = $class ? 'class="'.$class.'"' : '';
    $res = '';
    foreach($array as $r){
    $res .= '<a '.$attr.' href="'.url($r->url).'">'.$r->name.'</a>, ';
  }
  return rtrim($res,', ');
  }
}
if (!function_exists('link_menu')) {
    function link_menu($menu=false){
    if($menu){
    if(Str::contains($menu,'http')){
      return $menu;
    }
    else {
    return url($menu);
    }
  }else{
    return null;
  }
  }
}

if (!function_exists('keyword_search')) {
    function keyword_search($keywords){

    $link = null;
    foreach(explode(',',trim($keywords??' ')) as $row){
      $link .= '<a href="'.url('search/'.Str::slug($row)).'">#'.$row.'</a>, ';
    }
    return rtrim(trim($link),',');
  }
}
if (!function_exists( 'share_button')) {
    function share_button(){
    return View::make('views::share.button');
  }
}
if (!function_exists( 'get_ext')) {
    function get_ext($file){
    // dd($file);
    if(!empty($file)):
    $file_name = $file;
  $temp= explode('.',$file_name);
  $extension = end($temp);
  return $extension;
  else:
    return false;
  endif;
  }
}

if (!function_exists( 'undermaintenance')) {
    function undermaintenance(){
    echo '<!doctype html>
    <html>
    <head>
    <title>Site Maintenance</title>
    <meta charset="utf-8"/>
    <meta name="robots" content="noindex"/>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
      body { text-align: center; padding: 150px; }
      h1 { font-size: 50px; }
      body { font: 20px Helvetica, sans-serif; color: #333; }
      article { display: block; text-align: left; width: 650px; margin: 0 auto; }
      a { color: #dc8100; text-decoration: none; }
      a:hover { color: #333; text-decoration: none; }
    </style>
    </head>
    <body>
    <article>
        <h1>We&rsquo;ll be back soon!</h1>
        <div>
            <p>Mohon maaf untuk saat ini '.url('/').' sedang dalam perbaikan. Silahkan akses dalam beberapa waktu kedepan!</p>
            <p>Terima kasih,  '.get_option('site_title').'</p>
        </div>
    </article>
    </body>
    </html>
    ';
  exit;
  }
}
