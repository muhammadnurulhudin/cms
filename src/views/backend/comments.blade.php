@extends('admin.layout.app',['title'=>'Komentar'])
@section('content')
<div class="row">
<div class="col-lg-12"><h3 style="font-weight:normal"><i class="fa fa-comments" aria-hidden="true"></i> Komentar</h3>
  <br>
<table class="table table-hover table-bordered" style="font-size:small" id="sampleTable">
<thead>
  <tr style="background:#f7f7f7">
    <th width="2%">No</th>
    <th width="10%">Waktu</th>
    <th>Pengirim</th>
    <th>Isi Komentar</th>
    <th width="40px">Status</th>
  </tr>
</thead>
<tbody style="background:#fff">

@foreach($data as $k=>$row)
<tr>
  <td>{{$k+1}}</td>
  <td><small class="badge">{{$row->created_at}}</small></td>
  <td width="20%"> <i class="fa fa-user" aria-hidden></i> {{$row->name}}<br>
    <i class="fa fa-link" aria-hidden></i> {{$row->link ?? '-'}}<br>
      <i class="fa fa-at" aria-hidden></i> {{$row->email ?? '-'}}
  </td>
  <td>{!!$row->content!!}<br>

<b class="text-muted">Link :</b><br> <a target="_blank" href="{{url($row->post->post_url)}}">{{url($row->post->post_url)}}</a>
  </td>
  <td title="Klik Untuk Mengganti status Diterima atau Draft" class="pointer" onclick="$('.status').val('{{$row->id}}');$('.form').submit()">
 @if($row->status==1) <span class="badge badge-success"> Publish </span> @else <span class="badge badge-warning">Draft</span> @endif

              </td>
</tr>
@endforeach
</tbody>

</table>
</div>
</div>
<form action="{{URL::full()}}" class="form" method="post">
@csrf
<input type="hidden" class="status" name="status" value="">
</form>
@if(request()->post)
<script type="text/javascript">
$(function () {

$('input[type=search]').val('{{request()->post}}').keyup();
});
</script>
@endif
@endsection
