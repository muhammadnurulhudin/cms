<div class="page-content">
        <!-- inner page banner -->
        <div class="breadcrumb-row">
            <div class="container">
                <ul class="list-inline">
                    <li><a href="javascript:void(0);">Beranda</a></li>
                    <li>{{ucwords($post_type)}} </li>
                    <li>{{$title}}</li>
                </ul>
            </div>
        </div>

        <!-- Breadcrumb row END -->
        <div class="content-area">
            <div class="container">
                <div class="row">
                    <!-- Left part start -->
                    <div class="col-lg-9 col-md-8 col-sm-6">
                    <div class="section-head text-center">
                        <h2 class="text-uppercase">Berita</h2>
                        <div class="dez-separator-outer ">
                            <div class="dez-separator bg-primary style-skew"></div>
                        </div>
                    </div>
                        @foreach($index as $row)
                        <div class="blog-post blog-md clearfix date-style-2">
                            <div class="dez-post-media dez-img-effect zoom-slow"  style="background:none"> <a href="{{secure_url($row->url)}}"><img data-original="{{thumb($row->thumbnail)}}" alt=""></a> </div>
                            <div class="dez-post-info">
                                <div class="dez-post-title ">
                                    <h3 class="post-title"><a href="{{secure_url($row->url)}}">{{$row->title}}</a></h3>
                                </div>
                                <div class="dez-post-meta ">
                                    <ul>
                                        <li class="post-date"> <i class="fa fa-calendar"></i><strong>{{gettgl($row->created_at,'tglbulan')}}</strong> <span> {{gettgl($row->created_at,'tahun')}}</span> </li>
                                        <li class="post-author"><i class="fa fa-user"></i>By <a href="javascript:void(0);">{{$row->user->name}}</a> </li>
                                        @if($row->allow_comment==1)
                                                    <li class="post-comment"><i class="fa fa-comments"></i> {{$row->comments->count()}}</li>
                                                    @endif
                                    </ul>
                                </div>
                                <div class="dez-post-text">
                                    <p>{!!Str::limit(strip_tags($row->content),250)!!}</p>
                                </div>


                            </div>
                        </div>
                    @endforeach
                        <!-- Pagination start -->
                        <div class="pagination-bx clearfix m-b30">
                        {{ $index->render('pagination::bootstrap-5')}}
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
