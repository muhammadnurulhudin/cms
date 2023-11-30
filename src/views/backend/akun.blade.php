@extends('admin.layout.app',['title'=>'Dashboard'])
@section('content')
<form class="" action="{{URL::full()}}" method="post" enctype="multipart/form-data">
  @csrf
<div class="row">
<div class="col-lg-12"><h3 style="font-weight:normal">Akun <button name="save" value="true" class="btn btn-outline-primary btn-sm pull-right"> <i class="fa fa-save" aria-hidden></i> Simpan</button></h3>
  <br>
  <div class="form-group">
         <center><img class="img-responsive"  onclick="window.open(this.src)" style="border:none;width:100px" id="thumb" src="{{url('profile/'.$data->photo)}}" /></center><br>
         <input type="hidden" class="oldphoto" name="oldphoto" value="">
    <label for="">Foto Pengguna</label>
    <input onchange="readURL(this);" required type="file" class="form-control photo" name="photo" value="{{$data->photo}}">
  </div>

    <div class="form-group">
      <label for="">Nama</label>
      <input required type="text" class="form-control name" name="name" placeholder="Masukkan Nama" value="{{$data->name}}">
    </div>
    <div class="form-group">
      <label for="">Email</label>
      <input required type="email" class="form-control email" name="email" placeholder="Masukkan Email" value="{{$data->email}}">
    </div>

    <div class="form-group">
      <label  for="">Username</label>
      <input required type="text" class="form-control username" name="username" placeholder="Masukkan Username" value="{{$data->username}}">
    </div>
    <div class="form-group">
      <label for="">Password</label>
      <input type="password" class="form-control password" name="password" placeholder="Masukkan Password" value="">
      <input type="hidden" class="oldpass" name="oldpass" value="{{$data->password}}">
    </div>
</div>
</div>
</form>
<script>
    function readURL(input) {
      const allow = ['gif','png','jpeg','jpg'];
      var ext = input.value.replace(/^.*\./, '');
      if(!allow.includes(ext)){
        alert('Pilih hanya gambar');
        input.value='';
      }else {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                $('#thumb')
                    .attr('src', e.target.result)
                    .width('100px')
            };

            reader.readAsDataURL(input.files[0]);
        }
    }
  }
  </script>
@endsection
