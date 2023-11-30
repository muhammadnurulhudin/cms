@extends('admin.layout.app',['title'=>'Pengaturan'])
@section('content')
<form class="" action="{{URL::full()}}" method="post" enctype="multipart/form-data">
@csrf
<div class="row">
<div class="col-lg-12"><h3 style="font-weight:normal;margin-bottom:20px"> <i class="fa fa-gears"></i> Pengaturan <button name="save_setting" value="true" class="btn btn-outline-primary btn-sm pull-right">  <i class="fa fa-save" aria-hidden></i> Simpan</button></h3>

  <ul class="nav nav-tabs">
                <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#home">Organisasi</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#profile">Situs Web</a></li>
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#keamanan">Keamanan</a></li>
                @if(config('module.setting'))

                @foreach(config('module.setting') as $r)
                <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#{{$r['id']}}">{{$r['name']}}</a></li>
                @endforeach
                @endif
              </ul>
              <div class="tab-content" id="myTabContent">
                <div class="tab-pane fade active show" id="home">
                  @foreach($org as $r)
                  <small for="" class="text-muted">{{$r[0]}}</small>
              <input required type="text" class="form-control form-control-sm" placeholder="Masukkan {{$r[0]}}" name="{{$r[1]}}" value="{{get_option($r[1])}}">
                  @endforeach

                </div>
                <div class="tab-pane fade" id="profile">
                  <small>Konten Halaman Utama</small>
                  <select class="form-control form-control-sm" name="home_page">
                  <option value="">Default</option>
                  @foreach(\App\Models\Post::where('post_type','halaman')->where('mime_type','html')->where('post_status','publish')->get() as $row)
                  <option {{get_option('home_page')==$row->post_id ? 'selected' : ''}} value="{{$row->post_id}}">Halaman - {{$row->post_title}}</option>
                  @endforeach
                  </select>
                  @foreach($site as $r)
                  <small for="" class="text-muted">{{$r[0]}}</small>
              <input type="text" @if($r[2]=='number') oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" @endif class="form-control form-control-sm" placeholder="Masukkan {{$r[0]}}" name="{{$r[1]}}" value="{{get_option($r[1])}}">
                  @endforeach
                  <small for="" class="text-muted">Favicon (Ukuran 1:1)</small>
                  @if(file_exists(public_path(get_option('favicon'))))
                  <br>
                  <img src="{{asset(get_option('favicon'))}}" alt="" style="width:100px;padding:10px">
                  <br>
                  @endif
              <input type="file" class="form-control-file" name="favicon" value="">
              <small for="" class="text-muted">Logo</small>
              @if(file_exists(public_path(get_option('logo'))))
              <br>
              <img src="{{asset(get_option('logo'))}}" alt="" style="width:100px;padding:10px">
              <br>
              <br>
              @endif
              <input type="file" class="form-control-file" name="logo" value="">
            </div>
                <div class="tab-pane fade" id="keamanan">
              <small for="" class="text-muted">Status Maintenance</small><br>
              <input type="radio" name="site_maintenance" value="Y" {{get_option('site_maintenance')=='Y'? 'checked':''}} > <small>Aktif</small>
              <input type="radio" name="site_maintenance" value="N" {{get_option('site_maintenance')=='N'? 'checked':''}} > <small>Tidak Aktif</small><br>
      <small for="" class="text-muted">Path URL Login Admin</small>

              <input type="text" value="{{get_option('admin_path')}}" class="form-control form-control-sm" name="admin_path" placeholder="contoh : adminpanel ,  siadmin , cpanel, weblogin dst"><br>
              <div class="alert alert-warning">Hati-hati dalam melakukan perubahan Path URL Login Admin. Path Url login admin saat ini adalah <br> <b>{{url(get_option('admin_path'))}}</b><br>Silahkan dicatat agar ingat.</div>
            </div>
            @if(config('module.setting'))
            @foreach(config('module.setting') as $r)
            <div class="tab-pane fade" id="{{$r['id']}}">
            @foreach($r['form'] as $row)
            @if('file'==$row['type'])
            <small for="" class="text-muted">{{$row['name']}}</small>
            <input type="hidden" name="{{$row['field']}}_old" value="{{get_option($row['field'])??null}}">
            <input type="file" class="form-file-sm form-control" name="{{$row['field']}}">
            @if(get_option($row['field']))<a href="{{asset(get_option($row['field']))}}" class="badge badge-success">{{get_option($row['field'])}}</a><br>@endif
            @else
            <small for="" class="text-muted">{{$row['name']}}</small>
            <input type="{{$row['type']}}" class="form-control form-control-sm" name="{{$row['field']}}" value="{{get_option($row['field'])??null}}">

            @endif
            @endforeach

            </div>
            @endforeach
            @endif

              </div>
</div>
</div>
</form>

@endsection
