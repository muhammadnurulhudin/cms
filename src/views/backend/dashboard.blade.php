@extends('views::backend.layout.app',['title'=>'Dashboard'])
@section('content')
<!-- <link href="https://coderthemes.com/adminox/layouts/vertical/assets/css/icons.min.css" rel="stylesheet" type="text/css" /> -->
<div class="row">
<div class="col-lg-12"><h3 style="font-weight:normal"> <i class="fa fa-tachometer"></i> Dashboard </h3>
  <br>
  <div class="row">
    @foreach($type as $row)
          <div title="Klik untuk selengkapnya" class="pointer col-md-6 col-lg-4 " onclick="location.href='{{admin_url($row->name)}}'">
            <div class="widget-small danger coloured-icon"><i class="icon fa {{$row->icon}} fa-3x"></i>
              <div class="info">
                <h4>{{$row->title}}</h4>
                <p><b>{{collect($post)->where('type',$row->name)->count()}}</b></p>
              </div>
            </div>
          </div>
          @endforeach
        </div>
</div>
<div class="col-lg-8 mb-3">
  <div class="card" style="padding:15px">
  <h4 for="" style="margin-bottom:20px"><i class="fa fa-globe" aria-hidden="true"></i> Terakhir Dipublikasi</h4>
  @php $type = collect(get_module(true))->where('detail',true)->where('name','!=','media')->pluck('name');
    @endphp
  <div class="table-responsive"> <table class="table" style="font-size:small">
  <thead><tr>
    <th>Konten</th>
    <th>Waktu</th>
    <th>Judul</th>
  </tr></thead>
  <tbody>
  @foreach($lastpost as $r )
   <tr>
    <td>{{Str::title($r->type)}}</td>
    <td><code>{{time_ago($r->created_at)}}</code></td>
    <td><a href="{{url(admin_path().'/'.$r->type.'/edit/'.$r->id)}}">{{$r->title}}</a>  <i class="text-muted">oleh {{$r->user->name}}</i></td>
   </tr>
  @endforeach
  </tbody>
  </table>
  </div>
</div>
</div>
<div class="col-lg-4">
  <div class="card" style="padding:15px">
  <h4 for="" style="margin-bottom:20px"> <i class="fa fa-bar-chart" aria-hidden="true"></i> Grafik Pengunjung Mingguan</h4>
  @include('views::backend.visitor-chart')

</div>
</div>
<div class="col-lg-12 mt-3">
  <div class="card" style="padding:15px">
  <h4 for=""  style="margin-bottom:20px"> <i class="fa fa-info" aria-hidden="true"></i> Rincian Trafik {{request('timevisit') ? tanggal_indo(request('timevisit').' '.date('H:i:s')) : 'Hari ini'}}<span class="pull-right"><small>Pilih </small> <input max="{{date('Y-m-d')}}" value="{{request('timevisit') ?? date('Y-m-d')}}" onchange="if(this.value) location.href='{{url()->current().'?timevisit='}}'+this.value" style="width:120px" type="date" class="form-control-sm " ></span></h4>

  <div class="table-responsive"> <table class="table datat" style="font-size:small;width:100%">
  <thead><tr>
    <th width="18%">Time</th>
    <th width="15%">Page</th>
    <th width="15%">Reference</th>
    <th width="20%">IP</th>
    <th width="10%">Browser</th>
    <th width="10%">Device</th>
    <th width="10%">OS</th>
  </tr></thead>
  <tbody>

  </tbody>
  </table>
  </div>

</div>
</div>
<script type="text/javascript">
          window.addEventListener('DOMContentLoaded', function() {
          // var post_type = "{{get_post_type()}}";
          var table = $('.datat').DataTable({
        processing: true,
        serverSide: true,
        ajax: "{{ url(admin_path().'/visitor'.str_replace('/'.admin_path().'/dashboard','',request()->getRequestUri())) }}",
        columns: [
            {data: 'time', name: 'time'},
            {data: 'page', name: 'page'},
            {data: 'reference', name: 'reference'},
            {data: 'ip_location', name: 'ip_location'},
            {data: 'browser', name: 'browser'},
            {data: 'device', name: 'device'},
            {data: 'os', name: 'os'},
        ]
    });

          });
    </script>

</div>
@push('styles')

<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/rowreorder/1.4.1/css/rowReorder.dataTables.min.css">
<link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/responsive/2.5.0/css/responsive.dataTables.min.css">

@endpush
@push('scripts')
<script type="text/javascript" src="{{secure_asset('backend/js/plugins/jquery.dataTables.min.js')}}"></script>
     <script type="text/javascript" src="{{secure_asset('backend/js/plugins/dataTables.bootstrap.min.js')}}"></script>
     <script type="text/javascript" src="https://cdn.datatables.net/rowreorder/1.4.1/js/dataTables.rowReorder.min.js"></script>
     <script type="text/javascript" src="https://cdn.datatables.net/responsive/2.5.0/js/dataTables.responsive.min.js"></script>
     <script type="text/javascript">$('#sampleTable').DataTable();</script>
@endpush
@endsection
