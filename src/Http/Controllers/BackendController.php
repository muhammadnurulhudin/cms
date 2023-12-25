<?php
namespace Udiko\Cms\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Udiko\Cms\Models\Post;
use Udiko\Cms\Models\Group;
use Udiko\Cms\Models\User;
use Udiko\Cms\Models\Comment;
use Udiko\Cms\Models\Option;
use Udiko\Cms\Models\Visitor;
use Illuminate\Http\Request;
use Str;
use File;
use Image;
use Illuminate\Support\Facades\Http;
use Yajra\DataTables\Facades\DataTables;
use ZipArchive;

class BackendController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        return view('views::backend.index');
    }

    function delfile(Request $req)
    {
        if (request()->link) {
            if (file_exists(public_path(request()->link))) {
                if (unlink(public_path(request()->link))) {
                    $respons = ['msg' => 'success'];
                } else {
                    $respons = ['msg' => 'failed'];

                }
            }
            return response()->json($respons);
        }
    }
    public function visitor()
    {
        $data = request('timevisit') ? Visitor::whereDate('created_at', request('timevisit'))->latest('created_at') : Visitor::whereDate('created_at', date('Y-m-d'))->latest('created_at');
        return Datatables::of($data)
            ->addIndexColumn()
            ->addColumn('time', function ($row) {
                return '<code>' . time_ago($row->created_at) . '</code>';
            })
            ->addColumn('ip_location', function ($row) {
                $city = json_decode($row->ip_location)->city ?? null;
                $country = json_decode($row->ip_location)->country ?? null;
                $region = json_decode($row->ip_location)->region ?? null;
                $code = json_decode($row->ip_location)->countryCode ?? null;
                $ipinfo = $row->ip_location ? $region . ', ' . $city . '<br><img style="display:inline" height="10" src="' . thumb('backend/images/flags/' . Str::upper($code) . '.svg') . '"> ' . $country : 'N/A';
                return '<span class="badge badge-info">' . $row->ip . '</span><br><small>' . $ipinfo . '</small>';
            })
            ->addColumn('reference', function ($row) {
                return Str::limit($row->reference, 70);
            })
            ->addColumn('page', function ($row) {
                return '<a href="'.$row->page.'">'.Str::limit($row->page, 70).'</a>';
            })
            ->rawColumns(['time', 'ip_location', 'reference', 'page'])
            ->toJson();
    }

    public function dashboard()
    {

        $da = array();
        for ($i = 0; $i <= 6; $i++) {
            array_push($da, date("Y-m-d", strtotime("-" . $i . " days")));
        }
        $data['weekago'] = json_decode(json_encode(collect($da)->sort()), true);
        $data['type'] = request()->user()->level == 'admin' ? collect(get_module())->where('detail', true) : collect(get_module())->where('detail', true)->where('operator', true);
        $data['post'] =  Post::where('status','publish')->whereIn('type',$data['type']->pluck('name'))->select('type')->get();
        $data['lastpost'] = Post::with('user')->latest('created_at')->wherein('type', $data['type']->pluck('name'))->whereStatus('publish')->limit(5)->get();
        $data['visitor'] = Visitor::select('date')->get();
        return view('views::backend.dashboard', $data);
    }
    public function comments(Request $req)
    {
        if ($req->status) {
            $cek = Comment::where('id', $req->status)->first();
            if ($cek->status == 1) {
                Comment::where('id', $req->status)->update(['status' => 0]);
            } else {
                Comment::where('id', $req->status)->update(['status' => 1]);
            }
            return back()->with('success', 'Success');

        }
        $data['comments'] = Comment::withwherehas('post')->orderBy('created_at', 'desc')->get();

        return view('views::backend.comments', $data);
    }
    public function summer_file_upload(Request $req)
    {
        if ($files = $req->file('file')) {
            $id = $req->id;
            if (!is_dir(public_path('upload/' . get_post_type()))) {
                mkdir(public_path('upload/' . get_post_type()));
            }
            $date = Post::wherePostId($id)->first()->created_at;
            $per = array($this->dirpost($date)->y, $this->dirpost($date)->y . '/' . $id);
            foreach ($per as $value) {
                if (!is_dir(public_path('upload/' . get_post_type() . '/' . $value))) {
                    mkdir(public_path('upload/' . get_post_type() . '/' . $value));
                }
            }
            $dir = 'upload/' . get_post_type() . '/' . $this->dirpost($date)->y . '/' . $id . '/';
            $path = public_path($dir);
            $type = allowed_ext($req->file->getClientOriginalExtension());
            $mime = $req->file->getClientMimeType();
            abort_if(!allow_mime($mime), '403');
            $namewithextension = $req->file->getClientOriginalName(); //Name with extension 'filename.jpg'
            $fname = explode('.', $namewithextension)[0];
            $name = Str::slug(now() . ' ' . $fname) . '.' . $req->file->getClientOriginalExtension();
            if ($type):
                if ($type == 'image'):
                    $img = Image::make($files);
                    $img->resize(null, 1200, function ($constraint) {
                        $constraint->aspectRatio();
                        $constraint->upsize();
                    });
                    $img = $img->save($path . $name);
                    $filename = url($dir . $name);
                    $namepath = $dir . $name;

                else:
                    $req->file->move($path, $name);
                    $filename = url($dir . $name);
                    $namepath = $dir . $name;
                endif;
                $this->media_store($id, $mime, $namepath, $name, $fname);
                return response()->json(['status' => true, 'msg' => 'Berhasil diupload', 'filename' => $filename]);
            else:
                return response()->json(['status' => false, 'msg' => 'Format file tidak didukung', 'filename' => null]);

            endif;
        }
    }



    public function form(Request $req, Post $p, $id = null)
    {
        $looping_name = underscore(get_module_info('looping'));

        $find = Post::with('group')->find($id);
        // return $find->post_parent->id;
        // return $find;
        if ($id && empty($find) || (!empty($find) && $find->type != get_post_type())) {
            return redirect(admin_url(get_post_type()))->with('warning', get_module_info('title') . ' Tidak Ditemukan');
        }
        $field = (!empty($find->data_field)) ? json_decode($find->data_field, true) : NULL;
        $looping_data = (!empty($find->data_loop)) ? (collect(get_module_info('looping_data'))->where([0], 'Sort')->first() ? json_encode(collect(json_decode($find->data_loop, true))->sortBy('sort')) : $find->data_loop) : NULL;



        if (empty($id)) {
            if (in_array(get_post_type(), ['media']))
                abort(404);
            $newpost = $req->user()->post()->create([
                'type' => get_post_type(),
                'url' => url(get_post_type() . '/' . rand()),
                'status' => 'draft'
            ]);
            // if (get_module_info('parent') == 'e-surat'):
            //     $newpost->update(['url' => url(get_post_type() . '/' . $newpost->id . date('dmY')), 'status' => 'draft', 'title' => date('YmdHis')]);
            // endif;
            return redirect(admin_url(get_post_type() . '/edit/' . $newpost->id));
        }

        if ($req->save) {
            $data = array(
                'thumbnail_description' => strip_tags($req->thumbnail_description) ?? null,
                'thumbnail' => $req->thumbnail ? $this->upload_thumb($req, $find) : (($req->save != 'add') ? $find->thumbnail : null),
                'type' => get_post_type() ?? null,
                'title' => $title = strip_tags($req->title) ?? null,
                'content' => ($req->mime != 'html' && $find->mime != 'html') ? ($req->content ?? null) : $find->content,
                'redirect_to' => strip_tags($req->redirect_to) ?? null,
                'parent' => get_post_type() != 'menu' ? ($req->parent ?? null) : null,
                'mime' => $req->mime ?? null,
                'status' => strip_tags($req->status) ?? null,
                'description' => get_post_type() != 'menu' ? (strip_tags($req->description) ?? null) : null,
                'keyword' => strip_tags($req->keyword) ?? null,
                'allow_comment' => $req->allow_comment ?? 0,
                'slug' => $slug = !system_keyword(Str::slug($title)) ? Str::slug($title) : Str::slug($title.' '.str()->random(6)) ,
                'pinned' => $req->pinned ?? 0,
                'url' => get_post_type() != 'halaman' ? get_post_type() . '/' . $slug : $slug,
            );

            if ($req->save != 'save') {
                //EDIT ACTION
                if ($req->mime == 'html') {
                    make_custom_view($id, $req->content);
                }
                if (Post::where('title', $title)->whereNotIn('id', [$id])->where('type', get_post_type())->count() > 0)
                    return back()->with('danger', 'Upss...' . get_module_info('data_title') . ' Sudah digunakan !');
                $find->update($data);

                if ($req->post_group) {
                    $find->group()->sync($req->post_group);
                }
                //start custom field handler
                if (get_module_info('custom_field')) {
                    foreach (collect(get_module_info('custom_field'))->where([1], '!=', 'break') as $key => $value) {
                        $fieldname = underscore($value[0]);
                        if ($value[1] == 'file') {
                            if ($req->file($fieldname)) {
                                $custom_field[$fieldname] = $this->upload_file($req->file($fieldname), get_post_type(), $id, $find->created_at);

                            } else {
                                $old = 'old_' . $fieldname;
                                $custom_field[$fieldname] = $req->$old;
                            }
                        } elseif ($value[1] == 'array') {
                            $custom_field[$fieldname] = json_decode($req->$fieldname, true);

                        } else {
                            $custom_field[$fieldname] = strip_tags($req->$fieldname) ?? null;
                        }
                    }

                    if (get_module_info('post_parent')) {
                        $po = Post::where('id', $req->parent)->select('title')->first();
                        $custom_field[underscore(get_module_info('post_parent')[0])] = !empty($po) ? $po->post_title : '_';
                    }
                    $find->update(['data_field' => json_encode($custom_field)]);
                }
                //end custom field handler

                //start looping data handler
                if (get_module_info('looping')) {
                    $looping = underscore(get_module_info('looping'));
                    $datanya = array();
                    $jmlh = 0;
                    foreach (get_module_info('looping_data') as $y) {
                        if ($y['1'] != 'file'):
                            $r = underscore($y[0]);
                            $jmlh = ($req->$r) ? count($req->$r) : 0;
                        endif;
                    }
                    if ($jmlh > 0) {
                        for ($i = 0; $i < $jmlh; $i++) {
                            foreach (get_module_info('looping_data') as $y) {
                                $r = underscore($y[0]);
                                $as = $req->$r;
                                if (isset($as[$i])) {
                                    if ($y[1] == 'file') {
                                        $cf = $as[$i];
                                        $h[$r] = (!is_string($cf)) ? $this->upload_file($cf, get_post_type(), $id, $find->created_at) : $cf;
                                    } else {
                                        $h[$r] = strip_tags($as[$i]);
                                    }
                                } else {
                                    $h[$r] = null;
                                }

                            }
                            array_push($datanya, $h);
                        }
                    }
                    if ($req->menu_json) {
                        $mnews = array();
                        $fixd = json_decode($req->menu_json, true);
                        foreach ($fixd as $value) {

                            if (isset($value['children'])) {
                                $b = collect($datanya)->where('id', $value['id'])->first();
                                array_push($mnews, ['id' => $b['id'], 'parent' => 0, 'name' => $b['name'], 'description' => $b['description'], 'link' => $b['link'], 'icon' => $b['icon']]);

                                foreach ($value['children'] as $v1) {
                                    $b = collect($datanya)->where('id', $v1['id'])->first();
                                    array_push($mnews, ['id' => $b['id'], 'parent' => $value['id'], 'name' => $b['name'], 'description' => $b['description'], 'link' => $b['link'], 'icon' => $b['icon']]);
                                    if (isset($v1['children'])) {
                                        foreach ($v1['children'] as $v2) {
                                            $b = collect($datanya)->where('id', $v2['id'])->first();
                                            array_push($mnews, ['id' => $b['id'], 'parent' => $v1['id'], 'name' => $b['name'], 'description' => $b['description'], 'link' => $b['link'], 'icon' => $b['icon']]);
                                            if (isset($v2['children'])) {
                                                foreach ($v2['children'] as $v3) {
                                                    $b = collect($datanya)->where('id', $v3['id'])->first();
                                                    array_push($mnews, ['id' => $b['id'], 'parent' => $v2['id'], 'name' => $b['name'], 'description' => $b['description'], 'link' => $b['link'], 'icon' => $b['icon']]);
                                                    if (isset($v3['children'])) {
                                                        foreach ($v3['children'] as $v4) {
                                                            $b = collect($datanya)->where('id', $v4['id'])->first();
                                                            array_push($mnews, ['id' => $b['id'], 'parent' => $v3['id'], 'name' => $b['name'], 'description' => $b['description'], 'link' => $b['link'], 'icon' => $b['icon']]);
                                                        }
                                                    }
                                                }
                                            }
                                        }
                                    }
                                }
                            } else {
                                $b = collect($datanya)->where('id', $value['id'])->first();
                                array_push($mnews, ['id' => $b['id'], 'parent' => 0, 'name' => $b['name'], 'description' => $b['description'], 'link' => $b['link'], 'icon' => $b['icon']]);
                            }
                        }
                        // return response()->json($mnews);
                        $find->update(['data_loop' => json_encode($mnews)]);

                    }

                    if (!$req->menu_json):
                        $find->update(['data_loop' => json_encode($datanya)]);
                    endif;
                }
                //end looping data handler

                if ($req->tanggal_entry) {
                    $find->update(['created_at' => $req->tanggal_entry]);
                }
                regenerate_cache();
                $req->session()->regenerateToken();
                return back()->with('success', get_module_info('title_crud') . ' Berhasil');
            }

        }
        // if(get_module_info('parent')=='e-surat'){
        //   if($req->cetak_surat){
        //     $es = new \App\Http\Controllers\ESuratController;
        //     return $es->cetak_surat(dec64($id));
        //   }

        //   if($req->kirim_pemberitahuan){
        //     $this->kirimnotif(['msg'=>'Permohonan dgn kode REG  '.$req->post_title.' telah siap. Segera ambil dikantor kelurahan. Terima kasih','nohp'=>$field['nomor_telp_atau_wa_pemohon']]);
        //     return back()->with('success','Pemberitahuan Ke Pemohon berhasil dikirim');
        //   }
        // }
        return view('views::backend.form', ['edit' => $find, 'field' => $field, 'looping_data' => $looping_data]);
    }

    public function delete(Post $post, $id)
    {
        $cek = Post::findOrFail($id);
        if (empty($cek)) {
            return redirect(admin_path() . '/' . get_post_type())->with('danger', 'Data Tidak Ditemukan');
        }
        $dir = public_path('upload/' . get_post_type() . '/' . $this->dirpost($cek->created_at)->y . '/' . $id);
        File::deleteDirectory($dir);
        $cek->group()->detach($id);
        $cek->delete();
        regenerate_cache();
    }
    public function group(Request $req, $ids = null)
    {
        if ($ids) {
            if (Group::whereHas('post')->whereId($ids)->count() > 0)
                return back()->with('danger', 'Kategori Sedang Digunakan');
            $find = Group::findOrFail($ids);
            $find->delete();
            regenerate_cache();
            return back()->with('success', 'Hapus Kategori Sukses');
        }
        if ($req->id) {
            $find = Group::findOrFail($req->id);
            $find->update(['status' => $req->status == '1' ? 0 : 1]);
            regenerate_cache();
            return back()->with('success', 'Kategori Berhasil Diupdate');

        }
        if ($req->save) {
            if ($req->save == 'add') {
                Group::create([
                    'type' => get_post_type(),
                    'description' => $req->description,
                    'name' => $req->name,
                    'sort' => $req->sort,
                    'url' => get_post_type() . '/category/' . Str::slug($req->name),
                    'slug' => Str::slug($req->name),
                ]);
                regenerate_cache();

                return back()->with('success', 'Kategori Berhasil Ditambahkan');
            } else {
                Group::where('id', $req->save)->update([
                    'description' => $req->description,
                    'name' => $req->name,
                    'sort' => $req->sort,
                    'slug' => Str::slug($req->name),
                    'url' => get_post_type() . '/category/' . Str::slug($req->name),

                ]);
                regenerate_cache();

                return back()->with('success', 'Kategori Berhasil Diupdate');
            }

        }
        $group = Group::with('post')->whereType(get_post_type())->orderBy('sort', 'asc')->get();
        // return $group;
        return view('views::backend.group', ['data' => $group]);


    }
    //start handler upload file
    function upload_file($req, $post_type, $id, $date)
    {
        if (!is_dir(public_path('upload')))
            mkdir(public_path('upload'));
        if (!is_dir(public_path('upload/' . $post_type))) {
            mkdir(public_path('upload/' . $post_type));
        }
        $per = array($this->dirpost($date)->y, $this->dirpost($date)->y . '/' . $id);
        foreach ($per as $value) {
            if (!is_dir(public_path('upload/' . $post_type . '/' . $value))) {
                mkdir(public_path('upload/' . $post_type . '/' . $value));
            }
        }
        $dir = 'upload/' . $post_type . '/' . $this->dirpost($date)->y . '/' . $id . '/';
        if (!allowed_ext(Str::lower($req->getClientOriginalExtension()))) {
            return false;
        } else {
            $path = public_path($dir);
            $namewithextension = Str::random(5) . '-' . $req->getClientOriginalName();
            $mime = $req->getClientMimeType();
            abort_if(!allow_mime($mime), '403');
            $fname = explode('.', $namewithextension)[0];
            $name = Str::slug(now() . ' ' . $fname) . '.' . $req->getClientOriginalExtension();
            if (allowed_ext($req->getClientOriginalExtension()) == 'image') {
                $img = Image::make($req);
                $img->resize(null, 1200, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $img->save($path . $name);
            } else {
                $req->move($path, $name);
            }
            media_store($id, $mime, $dir . $name, $name, $fname);
            return $dir . $name;
        }
    }
    //end handler upload file
    function dirpost($post_date)
    {
        $y = date('Y', strtotime($post_date));
        return json_decode(json_encode(['y' => $y]));
    }

    //start thumbnail handler
    function upload_thumb($req, $post)
    {
        if (!is_dir(public_path('upload')))
            mkdir(public_path('upload'));
        if (!is_dir(public_path('upload/' . $post->type)))
            mkdir(public_path('upload/' . $post->type));
        $per = array($this->dirpost($post->created_at)->y, $this->dirpost($post->created_at)->y . '/' . $post->id);
        foreach ($per as $value) {
            if (!is_dir(public_path('upload/' . $post->type . '/' . $value)))
                mkdir(public_path('upload/' . $post->type . '/' . $value));
        }
        $dir = 'upload/' . $post->type . '/' . $this->dirpost($post->created_at)->y . '/' . $post->id . '/';
        if ($files = $req->file('thumbnail')) {
            $mime = $files->getClientMimeType();
            abort_if(!allow_mime($mime), '403');
            if (allowed_ext($files->getClientOriginalExtension())) {

                if ($req->save != 'add' && get_module_info('thumbnail') && !empty($post->thumbnail) && file_exists(public_path($post->thumbnail))) {
                    unlink(public_path($post->thumbnail));
                }
                $img = Image::make($files);
                $path = public_path($dir);
                $namewithextension = $files->getClientOriginalName(); //Name with extension 'filename.jpg'

                $fname = explode('.', $namewithextension)[0];
                $name = Str::slug(now() . ' ' . $fname) . '.' . $files->getClientOriginalExtension();
                $img->resize(null, 1200, function ($constraint) {
                    $constraint->aspectRatio();
                    $constraint->upsize();
                });
                $img = $img->save($path . $name);
                media_store($post->id, $mime, $dir . $name, $name, $fname);

                return $dir . $name;
            } else {
                return $post->thumbnail;

            }
        }

    }
    //end thumbnail handler

    public function dataindex(Request $req)
    {
        $data = Post::with('user', 'group', 'comments', 'child')->whereType(get_post_type());
        // return $data;
// if($req->segment(4)){
//   $data = $data->whereYear('created_at',$req->segment(4));
//   if($req->segment(5)){
//   $data = $data->whereMonth('created_at',strlen($req->segment(5))==1 ? '0'.$req->segment(5) : $req->segment(5));
//   if($req->segment(6))
//   $data = $data->whereDay('created_at',strlen($req->segment(6))==1 ? '0'.$req->segment(6) : $req->segment(6));
//   }
// }
        return DataTables::of($data)
            ->addIndexColumn()
            ->addColumn('title', function ($row) {
                if (get_post_type() == 'media') {
                    if (!file_exists(public_path($row->url)) || !$row->post_parent()->exists()) {
                        $row->delete();
                    }
                }
                $group = $this->get_group($row->group);
                //  get_module_info('group') ? ' &nbsp;'.get_public_group($row->post_group).'&nbsp;' : '';
                $label = ($row->allow_comment == 1) ? "<i class='fa fa-comments'></i> " . $row->comments->count() : '';
                $custom = ($row->mime == 'html') ? '<i class="text-muted">_HTML</i>' : '';
                $tit = (get_module_info('detail') || get_module_info('post_type') == 'media') ? ((!empty($row->title)) ? '<a title="Klik untuk melihat di tampilan web" href="' . url($row->url) . '" target="_blank">' . $row->title . '</a> ' . $custom : '<i class="text-muted">__Tanpa Judul__</i>') : ((!empty($row->title)) ? $row->title : '<i class="text-muted">__Tanpa Judul__</i>');
                $draft = ($row->status != 'publish') ? "<i class='badge badge-warning'>Draft</i> " : '';
                $pin = $row->type == 'permohonan' ? (!is_null($row->pinned) ? ($row->pinned == 1 ? '<span class="badge badge-success"> <i class="fa fa-check"></i> Valid</span>&nbsp;' : '<span class="badge badge-danger"> <i class="fa fa-close"></i> Tidak Valid</span>&nbsp;') : '<span class="badge badge-warning"> <i class="fa fa-question-circle "></i> Belum Divalidasi</span>&nbsp;') : ($row->pinned == 1 ? '<span class="badge badge-danger"> <i class="fa fa-star"></i> Disematkan</span>&nbsp;' : '');

                $b = '<b class="text-primary">' . $tit . '</b><br>';
                $b .= '<small class="text-muted"> ' . $pin . ' <i class="fa fa-user-o"></i> ' . $row->user->name . ' ' . $group . ' ' . $label . ' ' . $draft . '</small>';
                return $b;
            })
            ->addColumn('created_at', function ($row) {
                return '<small class="badge text-muted">' . date('d-m-Y H:i', strtotime($row->created_at)) . '</small>';
            })
            //     ->addColumn('checkbox', function($row){
//       $child = $row->child->where('post_type','!=','media')->count();
//       return '
//         <input  '.($child < 1 ? 'type="checkbox" value="'.$row->id.'"' :'type="hidden"').' class="post_id"/>
//      ';
//   })
            ->addColumn('visited', function ($row) {
                return '<center><small class="badge text-muted"> <i class="fa fa-line-chart"></i> <b>' . $row->visited . '</b></small></center>';
            })
            ->addColumn('updated_at', function ($row) {
                return ($row->updated_at) ? '<small class="badge text-muted">' . date('d-m-Y H:i', strtotime($row->updated_at)) . '</small>' : '<small class="badge text-muted">NULL</small>';
            })
            ->addColumn('thumbnail', function ($row) {
                return '<img class="rounded" height="50" width="70" src="' . thumb($row->thumbnail) . '"/>';
            })
            ->addColumn('data_field', function ($row) {
                $custom = underscore(get_module_info('custom_column'));
                return (get_module_info('custom_column') && !empty($row->data_field) && !empty(json_decode($row->data_field)->$custom)) ? '<span class="text-muted">' . json_decode($row->data_field)->$custom . '</span>' : '<span class="text-muted">__</span>';
            })

            ->addColumn('parents', function ($row) {
                if (get_module_info('post_parent')):
                    $custom = underscore(get_module_info('post_parent')[0]);
                    return (!empty($row->data_field) && !empty(json_decode($row->data_field)->$custom)) ? '<span class="text-muted">' . json_decode($row->data_field)->$custom . '</span>' : '<span class="text-muted">__</span>';
                else:
                    return '-';
                endif;
            })
            ->addColumn('aksi', function ($row) {
                $child = $row->child->where('type', '!=', 'media')->count();
                $alert = $child > 0 ? 'Tidak dapat dihapus, Data Digunakan Pada Modul Lain' : 'Tidak dapat dihapus, Anda bukan pemilik Konten.';
                $del = (($row->user == Auth::user() || Auth::user()->level == 'admin') && $child < 1) ? '<a  title="Hapus" onclick="deleteAlert(\'' . delete_post_url($row->id) . '\')" href="javascript:void(0)" class="text-danger" ><i class="fa fa-trash"></i></a>' : '<a  title="Hapus" onclick="notif(\'' . $alert . '\',\'danger\')" href="javascript:void(0)" class="text-muted" ><i class="fa fa-trash"></i></a>';

                $dis = ($row->type == 'html' || $row->type == 'media') ? ($row->type == 'media' ? '<a href="' . edit_post_url($row->id) . '" title="Lihat"><i class="fa fa-eye"></i></a>' : '<a href="' . edit_post_url($row->id) . '" title="Edit"><i class="fa fa-edit"></i></a>') :
                    '<a href="' . edit_post_url($row->id) . '" title="Edit"><i class="fa fa-edit"></i></a> &nbsp;' . $del;
                return $dis;
            })
            ->rawColumns(['created_at', 'updated_at', 'visited', 'aksi', 'title', 'data_field', 'parents', 'thumbnail'])
            ->orderColumn('visited', '-visited $1')
            ->orderColumn('updated_at', '-updated_at $1')
            ->orderColumn('created_at', '-created_at $1')
            ->only(['visited', 'aksi', 'title', 'created_at', 'updated_at', 'data_field', 'parents', 'thumbnail'])
            ->filterColumn('title', function ($query, $keyword) {
                $query->whereRaw("CONCAT(posts.title,'-',posts.title) like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('data_field', function ($query, $keyword) {
                $query->whereRaw("CONCAT(posts.data_field,'-',posts.data_field) like ?", ["%{$keyword}%"]);
            })
            ->filterColumn('parents', function ($query, $keyword) {
                $query->whereRaw("CONCAT(posts.data_field,'-',posts.data_field) like ?", ["%{$keyword}%"]);
            })
            ->toJson();
    }

    function get_group($array)
    {
        $res = '';
        foreach ($array as $r) {
            $res .= '' . $r->name . ', ';
        }
        if (count($array) > 0)
            return '<i class="fa fa-tags"></i> ' . rtrim($res, ', ');
        return $res;
    }

    function setting(Request $request, Option $option)
    {
        admin_only();
        $data['web_type'] = config('modules.config.web_type');
        $data['optionable'] = array_merge(config('modules.config.optionable') ?? [], [
            'Telepon',
            'Whatsapp',
            'Fax',
            'Email',
            'Latitude',
            'Longitude',
            'Facebook',
            'Youtube',
            'Instagram']);
        $data['site_attribute'] = array(
            ['Alamat Situs Web', 'site_url', 'text'],
            ['Nama Situs Web', 'site_title', 'text'],
            ['Deskripsi Situs Web', 'site_description', 'text'],
            ['SEO Meta Keyword', 'site_meta_keyword', 'text'],
            ['SEO Meta Description', 'site_meta_description', 'text'],
            ['Postingan Perhalaman', 'post_perpage', 'number'],
            ['Logo', 'logo', 'file'],
            ['Favicon', 'favicon', 'file'],
        );

        $data['security'] = array(

            ['Block IP', '0.0.0.0,0.0.1.0,..,..'],
            ['Allow API Request', '0.0.0.0,0.0.1.0,..,..'],
            ['Forbidden Keyword', 'Judi Online, Gacor, xxx, other'],
            ['Forbidden Redirect', 'Eg: https://yourpage.url or other'],
            ['Time Limit Login', '1,2,3,4'],
            ['Time Limit Reload', '1,2,3,4,5'],
            ['Limit Duration', 'in miliscond eg: 10000 for 10 seconds']);

        $data['home_page'] = Post::whereType('halaman')->whereMime('html')->select('id', 'title')->get();

        if ($request->all()) {
            if ($value = $request->home_page) {
                Option::updateOrCreate(['name' => 'home_page'], ['value' => $value]);
            }
            foreach ($data['optionable'] as $row) {
                $key = _us($row);
                if ($value = $request->$key) {
                    $find = $option->where('name', _us($row))->first();
                    $find ? $find->update(['value' => strip_tags($value)]) : $option->create(['name' => _us($row), 'value' => strip_tags($value), 'autoload' => 1]);
                }
            }
            foreach (array_merge($data['security'], [['Admin Path', ''], ['Site Maintenance', '']]) as $row) {
                $key = _us($row[0]);
                if ($value = $request->$key) {
                    $find = $option->where('name', _us($row[0]))->first();
                    $find ? $find->update(['value' => strip_tags($value)]) : $option->create(['name' => _us($row[0]), 'value' => strip_tags($value), 'autoload' => 1]);
                }
            }
            foreach ($data['site_attribute'] as $row) {
                $key = $row[1];
                if ($row[2] == 'file') {
                    if ($value = $request->file($key)) {
                        if (allow_mime($value->getClientMimeType()) && in_array($value->getClientOriginalExtension(), ['jpg', 'png'])) {
                            $value->move(public_path('/'), $row[1] . '.' . $value->getClientOriginalExtension());
                            $find = $option->where('name', $key)->first();
                            $find ? $find->update(['value' => $row[1] . '.' . $value->getClientOriginalExtension()]) : $option->create(['name' => $key, 'value' => $row[1] . '.' . $value->getClientOriginalExtension(), 'autoload' => 1]);
                        }
                    }
                } else {
                    if ($value = $request->$key) {
                        $find = $option->where('name', $key)->first();
                        $find ? $find->update(['value' => strip_tags($value)]) : $option->create(['name' => $key, 'value' => strip_tags($value), 'autoload' => 1]);
                    }
                }
            }
            recache_option();
            return back()->with('success', 'Berhasil disimpan');
        }
        // return back()->with('success', 'Pengaturan Berhasil Disimpan');
        return view('views::backend.setting', $data);
    }
    function users(Request $request, User $user)
    {
        admin_only();

        if ($request->delete) {
            Post::where('user_id', $request->delete)->update(['user_id' => 1]);
            $id = $user->findOrFail($request->delete);
            $id->delete();
            return back()->with('success', 'Hapus Pengguna Sukses');
        }
        if ($request->save) {
            if ($request->save == 'add') {
                $data = array(
                    'name' => $request->name ?? '',
                    'email' => $request->email ?? '',
                    'slug' => Str::slug($request->name),
                    'level' => $request->level,
                    'url' => 'author/' . Str::slug($request->name),
                    'username' => $request->username ?? '',
                    'password' => bcrypt($request->password) ?? '',
                    'status' => $request->status ?? 'Nonaktif'
                );
                $id = User::create($data);
                if ($files = $request->file('photo')) {
                    if (allow_mime($files->getClientMimeType()) && in_array($files->getClientOriginalExtension(), ['jpg', 'png'])) {
                        $fname = $id->id . '.' . $files->getClientOriginalExtension();
                        $files->move(public_path('users'), $fname);
                    }
                    ;
                }
                $id->update(['photo' => isset($fname) ? 'users/' . $fname : 'user.png']);
                return back()->with('success', 'Tambah Pengguna Sukses');
            } else {
                $find = User::findOrFail($request->save);
                $find->update([
                    'level' => $request->level,
                    'status' => $request->status ?? 'Nonaktif',
                    'name' => $request->name, 'profile_url' => 'author/' . Str::slug($request->name),
                    'slug' => Str::slug($request->name),
                    'email' => $request->email,
                    'username' => $request->username,
                    'password' => bcrypt($request->password) ?? $find->password]);
                if ($files = $request->file('photo')) {
                    if (allow_mime($files->getClientMimeType()) && in_array($files->getClientOriginalExtension(), ['jpg', 'png'])) {
                        $fname = $find->id . '.' . $files->getClientOriginalExtension();
                        $files->move(public_path('users'), $fname);
                    }
                    ;
                }
                $find->update(['photo' => isset($fname) ? 'users/' . $fname : $find->photo]);
                return back()->with('success', 'Edit Pengguna Sukses');

            }
        }
        $data['users'] = $user->where('id', '<>', $request->user()->id)->get();
        return view('views::backend.users', $data);
    }
    function template(Request $request)
    {
        admin_only();

        $apitemplate = Http::get('http://larawebcms.test/apitemplate')->json();
        $data['templates'] = $request->type && $request->type != 'semua' ? collect(json_decode(json_encode($apitemplate)))->where('type', $request->type) : collect(json_decode(json_encode($apitemplate)));

        if ($request->select) {
            $selected_template = collect($data['templates'])->where('id', $request->select)->first();
            if (!empty($selected_template)) {
                if ($request->apply) {
                    $filename = 'template.zip';
                    $tempImage = tempnam(sys_get_temp_dir(), $filename);
                    copy($selected_template->download, $tempImage);

                    $zip = new ZipArchive;
                    if ($zip->open($tempImage) === TRUE) {
                        // dd('berhasil');
                        // if($zip->setPassword(get_option('passbackup'))):
                        // if($zip->getFromName($resfile['type'].".lw"))
                        $zip->extractTo(resource_path('views/template/' . $selected_template->path));
                        $zip->close();
                        if (File::moveDirectory(resource_path('views/template/' . $selected_template->path . '/assets'), public_path('template/' . $selected_template->path))) {
                            Option::whereName('template')->update(['value' => $selected_template->path]);
                            recache_option();
                            return back()->with('success', 'Template Berhasil Diterapkan');
                        } else {
                            return back()->with('danger', 'Template Gagal Diterapkan');

                        }

                    }

                }
                $data['detail'] = $selected_template;
            } else {
                return redirect(url()->current())->with('warning', 'Tempate Tidak Ditemukan');
            }
        }
        return view('views::backend.template', $data);
    }
    function account(Request $request, User $user)
    {
        $data = $user->findOrFail(request()->user()->id);
        if ($request->save) {
            $validate = $request->validate([
                'username' => 'required',
                'email' => 'required',
                'name' => 'required',
            ]);
            if($file=$request->file('photo')){
                if(allow_mime($file->getClientMimeType()) && in_array($file->getClientOriginalExtension(),['jpg','png'])){
                    $fname = $data->id.'.'.$file->getClientOriginalExtension();
                    $file->move(public_path('users'),$fname);
                    $validate['photo'] = 'users/'.$fname;
                }
            }

            if(!$user->whereUsername($request->username)->where('id','<>',$data->id)->exists()){
                if (!$user->whereEmail($request->email)->where('id', '<>', $data->id)->exists()) {
                    if ($request->password2 && $request->password){
                        if($request->password2 == $request->password && strlen($request->password2) > 7) {
                            $validate['password'] = bcrypt($request->password);
                        } else {
                            return back()->with('danger', 'Konfirmasi password harus sama | minimal 8 karakter');
                        }
                    }

                    $data->update($validate);
                    return back()->with('success', 'Akun Berhasil Diupdate');
                }
                else{
                return back()->with('danger','Email telah digunakan');

                }
            }else{
                return back()->with('danger','Username telah digunakan');
            }
        }
        return view('views::backend.account', compact('data'));
    }
}
