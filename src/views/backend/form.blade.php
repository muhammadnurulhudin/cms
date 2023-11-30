@if(get_post_type()=='media')
@include('admin.form-media')
@elseif(get_post_type()=='menu')
@include('admin.form-menu')

@else
@include('admin.form-default')
@endif
