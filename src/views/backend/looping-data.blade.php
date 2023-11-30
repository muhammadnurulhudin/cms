<div class="table-responsive" style="height:75vh;">

   <table class="table table-bordered table-hover table-striped" style="background:#fff;font-size:small;">
      <thead style="background:#f7f7f7">
         <tr>
            @foreach(get_module_info('looping_data') as $r)
            <th class="text-center" @if($r[0] == 'Sort')style="width:10px"@endif>{{$r[0]}}</th>
            @endforeach
            <th class="text-center">#</th>
         </tr>
      </thead>
      <tbody class="coldata">
        <!--  -->
         @if($looping_data)

         <!--  -->
         @foreach(json_decode($looping_data) as $y=> $l)
         <tr id='data-{{$y}}'>
         @foreach(get_module_info('looping_data') as $ky=>$r)
         <?php $k = underscore($r[0]);?>
         <td align="center" @if('file'==$r[1])  onmouseover="$('.edit-{{underscore($r[0])}}-{{$y}}').show()" onmouseout="$('.edit-{{underscore($r[0])}}-{{$y}}').hide()" @endif>
            @if('file'==$r[1])
            <?php
            if(!empty($l->$k) && file_exists(public_path($l->$k))){
              $f[$y] = true;
              // echo $f[$ky].'df';
            }
            ?>
            <span @if(!empty($l->$k) && file_exists(public_path($l->$k)) && allowed_ext(get_ext($l->$k)))style="display:none"@endif class="input-{{underscore($r[0])}}-{{$y}}">

              <input title="Format: {{allowed_ext()}}" data-toggle="tooltip" onchange="readFile(this);"  placeholder="Masukkan {{$r[0]}}" type="file" style="width:74px;" class="form-control-sm" name="{{underscore($r[0])}}[]"/>


            </span>
               <input type="hidden" class="oldfile-{{underscore($r[0])}}-{{$y}}"  name="{{underscore($r[0])}}[]" value="{{$l->$k ?? 'nofile'}}">
            @if(!empty($l->$k) && file_exists(public_path($l->$k)) && allowed_ext(get_ext($l->$k)))<a target="_blank" href="{{asset($l->$k)}}" class="file-{{underscore($r[0])}}-{{$y}} btn btn-sm btn-outline-info"> {{strtoupper(get_ext($l->$k))}} </a>
          <i  style="display:none" class="fa fa-trash pointer text-danger edit-{{underscore($r[0])}}-{{$y}}" onclick="if(confirm('Hapus file ?')) { exeurl('{{$l->$k}}'); this.remove();$('.input-{{underscore($r[0])}}-{{$y}} input[type=file]').removeAttr('disabled','');$('.file-{{underscore($r[0])}}-{{$y}}').remove();$('.input-{{underscore($r[0])}}-{{$y}}').show();}" aria-hidden></i>

          @endif
            @elseif(is_array($r[1]))
            <select class="form-control form-control-sm" name="{{underscore($r[0])}}[]">
               <option value="">-pilih-</option>
               @foreach($r[1] as $r)
               <option {{isset($l->$k) && $l->$k==$r ? 'selected':''}} value="{{$r}}">{{$r}}</option>
               @endforeach
            </select>
            @else
            <input  placeholder="Entri Data {{ucwords(mb_strtolower($r[0]))}}" type="{{$r[1]}}"  class="form-control form-control-sm" style="min-width:80px" name="{{underscore($r[0])}}[]" value="{{$l->$k ?? null}}">
            @endif
         </td>

         @endforeach
         <td class="text-center" >  <i @if(isset($f[$y])) onclick="alert('Hapus file terlebih dahulu')" @else onclick="if(confirm('Hapus Data Baris?')){$('#data-{{$y}}').remove()}" @endif class="fa fa-trash pointer text-danger" style="display:inline" aria-hidden></i>  </td>
         </tr>
         @endforeach

         @endif
      </tbody>
      <tfoot style="background:#f7f7f7">
         <tr>
            <td colspan="{{count(get_module_info('looping_data'))+1}}" align="right">
           <button style="display:none" type="button" class="btn btn-sm btn-outline-danger delbut" onclick="$('.coldata tr.nw:last').remove();$('.btnadd').show();$('.delbut').hide();">&nbsp;&nbsp;<i class="fa fa-times" aria-hidden></i></button> <button onclick="$('.coldata').append('<tr class=\'nw\'>'+ $('.addcol').html()+'</tr>');$('.coldata tr.nw select').removeAttr('disabled');$('.coldata tr.nw input').removeAttr('disabled');$('.delbut').show();$('.btnadd').hide()" type="button" class="btn btn-sm btn-outline-info btnadd" name="button"> &nbsp; <i class="fa fa-plus"></i></button></td>
         </tr>
         <tr style="display:none" class="addcol">
            @foreach(get_module_info('looping_data') as $r)
            <td class="text-center">
               @if($r[1]=='file')
               <input  disabled onchange="readFile(this)" type="{{$r[1]}}"  class="form-control-sm" style="width:74px;"   name="{{underscore($r[0])}}[]" >
               @elseif(is_array($r[1]))
            <select disabled  class="form-control form-control-sm" name="{{underscore($r[0])}}[]">
               <option value="">-pilih {{ucwords(mb_strtolower($r[0]))}}-</option>
               @foreach($r[1] as $r)
               <option value="{{$r}}">{{$r}}</option>
               @endforeach
            </select>
            @else
               
               <input style="min-width:80px" disabled placeholder="Entri Data {{ucwords(mb_strtolower($r[0]))}}" type="{{$r[1]}}"  class="form-control form-control-sm"  name="{{underscore($r[0])}}[]" >
               @endif
            </td>
            @endforeach
            <td></td>
         </tr>
      </tfoot>
   </table>
</div>
