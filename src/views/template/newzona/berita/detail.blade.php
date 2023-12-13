<div class="page-content" style="">
<div class="breadcrumb-row" style="border-top:3px #D1580C solid;background:url({{template_asset('bg/blackbg.png')}})">
            <div class="container">
                <ul class="list-inline">
                    <li><a href="{{url('/')}}">Beranda</a></li>
                    <li>{{$title}}</li>
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
                                    @if(get_module_info('group') && $detail->group->count()>0)
                                    <li class="post-tags"><i class="fa fa-tags"></i>
                                    {!!get_group($detail->group)!!}
                                </li>
                                    @endif
                                </ul>
                            </div>
                            <div class="dez-post-media dez-img-effect zoom-slow"> <a href="javascript:void(0);"><img src="{{thumb($detail->thumbnail)}}" alt=""></a> </div>


                            <div class="dez-post-text">
                                {!!$detail->content!!}
                                <br>
                                <br>
                                {!!share_button()!!}

                            </div>
                            <div class="dez-post-tags clear">
                                <div class="post-tags">
                                {!!keyword_search($detail->_keyword)!!}

                            </div>
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
