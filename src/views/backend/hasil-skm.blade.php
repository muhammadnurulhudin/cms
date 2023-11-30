@if($looping_data)
@foreach(json_decode($looping_data) as $y=> $l)
<tr id='data-{{$y}}'>
@foreach(get_module_info('looping_data') as $ky=>$r)
<?php $k = underscore($r[0]);?>
    <input type="hidden"  class="form-control form-control-sm" style="min-width:80px" name="{{underscore($r[0])}}[]" value="{{$l->$k ?? null}}">
@endforeach
@endforeach

Total {{count(json_decode($looping_data))}}
<?php
$respon = count(json_decode($looping_data));
$u1=0;
$u2=0;
$u3=0;
$u4=0;
$u5=0;
$u6=0;
$u7=0;
$u8=0;
$u9=0;?>
<table border="1" style="width:100%">
  <thead>
    <tr>
      <th rowspan="2">No</th>
      <th colspan="9">Nilai Unsur Pelayanan</th>
    </tr>
    <tr>
      <th>U1</th>
      <th>U2</th>
      <th>U3</th>
      <th>U4</th>
      <th>U5</th>
      <th>U6</th>
      <th>U7</th>
      <th>U8</th>
      <th>U9</th>
    </tr>
  </thead>
<?php
foreach(json_decode($looping_data) as $k=>$r):
?>
<tr>
  <td>{{$k+1}}</td>
<?php
$u1 +=$r->u1;
$u2 +=$r->u2;
$u3 +=$r->u3;
$u4 +=$r->u4;
$u5 +=$r->u5;
$u6 +=$r->u6;
$u7 +=$r->u7;
$u8 +=$r->u8;
$u9 +=$r->u9;
?>
<td>{{$r->u1}}</td>
<td>{{$r->u2}}</td>
<td>{{$r->u3}}</td>
<td>{{$r->u4}}</td>
<td>{{$r->u5}}</td>
<td>{{$r->u6}}</td>
<td>{{$r->u7}}</td>
<td>{{$r->u8}}</td>
<td>{{$r->u9}}</td>
</tr>
<?php
endforeach;
?>
<tr>
  <td>Nilai/Unsur</td>
  <td>{{$u1}}</td>
  <td>{{$u2}}</td>
  <td>{{$u3}}</td>
  <td>{{$u4}}</td>
  <td>{{$u5}}</td>
  <td>{{$u6}}</td>
  <td>{{$u7}}</td>
  <td>{{$u8}}</td>
  <td>{{$u9}}</td>
    </tr>
    <tr>
      <td>NRR/Pertanyaan</td>
      <td>{{round($u1 / $respon,2)}}</td>
      <td>{{round($u2 / $respon,2) }}</td>
      <td>{{round($u3 / $respon,2) }}</td>
      <td>{{round($u4 / $respon,2) }}</td>
      <td>{{round($u5 / $respon,2) }}</td>
      <td>{{round($u6 / $respon,2) }}</td>
      <td>{{round($u7 / $respon,2) }}</td>
      <td>{{round($u8 / $respon,2) }}</td>
      <td>{{round($u9  / $respon,2) }}</td>
    </tr>
    <tr>
      <td>NRR Tertimbang/pertanyaan</td>
      <td>{{round(($u1 / $respon)*1/9,2)}}</td>
      <td>{{round(($u2 / $respon)*1/9,2) }}</td>
      <td>{{round(($u3 / $respon)*1/9,2) }}</td>
      <td>{{round(($u4 / $respon)*1/9,2) }}</td>
      <td>{{round(($u5 / $respon)*1/9,2) }}</td>
      <td>{{round(($u6 / $respon)*1/9,2) }}</td>
      <td>{{round(($u7 / $respon)*1/9,2) }}</td>
      <td>{{round(($u8 / $respon)*1/9,2) }}</td>
      <td>{{round(($u9  / $respon)*1/9,2) }}</td>
    </tr>
    <tr>
      <td>NRR Tertimbang</td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td></td>
      <td>{{round((($u1 / $respon)*1/9)+(($u9 / $respon)*1/9)+(($u2 / $respon)*1/9)+(($u3 / $respon)*1/9)+(($u4 / $respon)*1/9)+(($u5 / $respon)*1/9)+(($u6 / $respon)*1/9)+(($u7 / $respon)*1/9)+(($u8 / $respon)*1/9),2)}}</td>
      <td>{{round(((($u1 / $respon)*1/9)+(($u9 / $respon)*1/9)+(($u2 / $respon)*1/9)+(($u3 / $respon)*1/9)+(($u4 / $respon)*1/9)+(($u5 / $respon)*1/9)+(($u6 / $respon)*1/9)+(($u7 / $respon)*1/9)+(($u8 / $respon)*1/9))*25,2)}}</td>
    </tr>
    <tr>
      <td colspan="10" align="right">{{prediket(((($u1 / $respon)*1/9)+(($u9 / $respon)*1/9)+(($u2 / $respon)*1/9)+(($u3 / $respon)*1/9)+(($u4 / $respon)*1/9)+(($u5 / $respon)*1/9)+(($u6 / $respon)*1/9)+(($u7 / $respon)*1/9)+(($u8 / $respon)*1/9))*25)}}</td>
      @php App\Models\Posts::where('post_id',$edit->post_id)->update(['data_field'=>json_encode(['nilai'=>prediket(((($u1 / $respon)*1/9)+(($u9 / $respon)*1/9)+(($u2 / $respon)*1/9)+(($u3 / $respon)*1/9)+(($u4 / $respon)*1/9)+(($u5 / $respon)*1/9)+(($u6 / $respon)*1/9)+(($u7 / $respon)*1/9)+(($u8 / $respon)*1/9))*25)])]); @endphp
    </tr>
</table>

@endif
