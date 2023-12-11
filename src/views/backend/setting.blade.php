@extends('views::backend.layout.app', ['title' => 'Pengaturan'])
@section('content')
    <form class="" action="{{ URL::full() }}" method="post" enctype="multipart/form-data">
        @csrf
        <div class="row">
            <div class="col-lg-12">
                <h3 style="font-weight:normal;margin-bottom:20px"> <i class="fa fa-gears"></i> Pengaturan <button
                        name="save_setting" value="true" class="btn btn-outline-primary btn-sm pull-right"> <i
                            class="fa fa-save" aria-hidden></i> Simpan</button></h3>

                <ul class="nav nav-tabs">
                    <li class="nav-item"><a class="nav-link active" data-toggle="tab" href="#home">{{ $web_type }}</a>
                    </li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#profile">Situs Web</a></li>
                    <li class="nav-item"><a class="nav-link" data-toggle="tab" href="#keamanan">Keamanan</a></li>

                </ul>
                <div class="tab-content pt-2" id="myTabContent">
                    <div class="tab-pane fade active show" id="home">
                        @foreach ($optionable as $r)
                            <small for="" class="text-muted">{{ $r }}</small>
                            <input required type="text" class="form-control form-control-sm"
                                placeholder="Masukkan {{ $r }}" name="{{ _us($r) }}"
                                value="{{ get_option(_us($r)) }}">
                        @endforeach

                    </div>
                    <div class="tab-pane fade" id="profile">
                        <small>Konten Halaman Utama</small>
                        <select class="form-control form-control-sm" name="home_page">
                            <option value="default">Default</option>
                            @foreach($home_page as $r)
                            <option value="{{$r->id}}" {{$r->id == get_option('home_page') ? 'selected':''}}>{{$r->title}}</option>

                            @endforeach
                        </select>
                        @foreach ($site_attribute as $r)
                            @if ($r[2] == 'file')
                                <small for="" class="text-muted">{{ $r[0] }}</small>
                                @if(file_exists(public_path(get_option($r[1]))))<br><br>
                                <img src="{{secure_asset(get_option($r[1]))}}" height="70" alt="">
                                <br>
                                <br>
                                @endif
                                <input type="file" class="form-control form-control-file" name="{{ $r[1] }}">
                            @else
                                <small for="" class="text-muted">{{ $r[0] }}</small>
                                <input type="text"
                                    @if ($r[2] == 'number') oninput="this.value = this.value.replace(/[^0-9.]/g, '').replace(/(\..*?)\..*/g, '$1');" @endif
                                    class="form-control form-control-sm" placeholder="Masukkan {{ $r[0] }}"
                                    name="{{ $r[1] }}" value="{{ get_option($r[1]) }}">
                            @endif
                        @endforeach
                    </div>
                    <div class="tab-pane fade" id="keamanan">
                        <small for="" class="text-muted">Status Maintenance</small><br>
                        <input type="radio" name="site_maintenance" value="Y"
                            {{ get_option('site_maintenance') == 'Y' ? 'checked' : '' }}> <small>Aktif</small>
                        <input type="radio" name="site_maintenance" value="N"
                            {{ get_option('site_maintenance') == 'N' ? 'checked' : '' }}> <small>Tidak Aktif</small><br>
                        <small for="" class="text-muted">Path URL Login Admin</small>

                        <input type="text" value="{{ get_option('admin_path') }}" class="form-control form-control-sm"
                            name="admin_path" placeholder="contoh : adminpanel ,  siadmin , cpanel, weblogin dst"/><br>
                        <div class="alert alert-warning">Hati-hati dalam melakukan perubahan Path URL Login Admin. Path Url
                            login admin saat ini adalah <br> <b>{{ url(get_option('admin_path')) }}</b><br>Silahkan dicatat
                            agar ingat.</div>
                            @foreach($security as $r)
            <small for="" class="text-muted">{{$r[0]}}</small><br>
            <input type="text" class="form-control form-control-sm" placeholder="Enter {{ $r[1] }}"
            name="{{ _us($r[0]) }}" value="{{ get_option(_us($r[0])) }}">
            @endforeach

                    </div>


                </div>
            </div>
        </div>
    </form>
@endsection
