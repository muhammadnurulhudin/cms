<div class="page-content">
<div class="breadcrumb-row">
            <div class="container">
                <ul class="list-inline">
                    <li><a href="{{url('/')}}">Beranda</a></li>
                    <li>{{get_post_type($post_type)}}</li>
                </ul>
            </div>
        </div>
        <div class="content-area">

            <div class="container">
                <div class="row">
                    <!-- Left part start -->
                    <div class="col-lg-9 col-md-8 col-sm-6">

                    <div class="blog-post blog-single">
                            <div class="dez-post-title p-0">
                            <div class="section-head text-left mb-0">
                        <h2  style="line-height:normal">{{$detail->title}}</h2>
                        <div class="dez-separator-outer ">
                            <div class="dez-separator bg-primary style-skew"></div>
                        </div>
                    </div>
                            </div>
                            <div class="dez-post-meta m-b20">
                                <ul>
                                    <li class="post-date"> <i class="fa fa-calendar"></i> {{tanggal_indo($detail->created_at)}} </li>
                                    <li class="post-author"><i class="fa fa-user"></i><a href="javascript:void(0);">{{$detail->user->name}}</a> </li>

                                </ul>
                            </div>
                            @if(get_module_info('thumbnail') && file_exists(public_path($detail->thumbnail)))
                            <div class="dez-post-media dez-img-effect zoom-slow"> <a href="javascript:void(0);"><img src="{{thumb($detail->thumbnail)}}" alt=""></a> </div>
                            @endif

                            <div class="dez-post-text">
                                {!!$detail->content!!}
                                @if(get_post_type()=='jenis-pelayanan' && file_exists(public_path(_field($detail,'file'))))
          @if(isMobile())
          <a class="btn btn-md btn-info w-100" href="{{secure_asset(_field($detail,'file'))}}"><i class="fa fa-link" aria-hidden="true"></i> Lihat Lampiran 1</a>
          @else
          <object data="{{secure_asset(_field($detail,'file'))}}" type="application/pdf" width="100%" style="min-height:80vh"></object>
            @endif
          @endif
                                @if(_loop($detail))

          @foreach(_loop($detail) as $k=>$row)
          @if(get_post_type()=='jenis-pelayanan' && file_exists(public_path($row->berkas)))
          @if(isMobile())
          <a class="btn btn-md btn-info w-100" href="{{secure_asset($row->berkas)}}"><i class="fa fa-link" aria-hidden="true"></i> Lihat Lampiran {{$k+1+1}}</a>
          @else
          <object class="mt-4" data="{{secure_asset($row->berkas)}}" type="application/pdf" width="100%" style="min-height:80vh"></object>
            @endif
          @endif
          @endforeach
          @endif
          <br>
          {!!share_button()!!}
                            </div>
                            <div class="dez-post-tags clear">
                                <div class="post-tags"> <a href="javascript:void(0);">Child </a> <a href="javascript:void(0);">Eduction </a> <a href="javascript:void(0);">Money </a> <a href="javascript:void(0);">Resturent </a> </div>
                            </div>
                        </div>
                        <!-- Pagination END -->
                    </div>
                    <!-- Left part END -->
                    <!-- Side bar start -->
                  @include(blade_path('sidebar'))
                    <!-- Side bar END -->
                </div>
            </div>
        </div>

    </div>
