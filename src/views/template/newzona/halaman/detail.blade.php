<div class="page-content">
<div class="breadcrumb-row">
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
                                    <li class="post-date"> Diperbarui {{tanggal_indo($detail->created_at)}} </li>
                                    <li class="post-author"><i class="fa fa-user"></i><a href="javascript:void(0);">{{$detail->user->name}}</a> </li>

                                </ul>
                            </div>


                            <div class="dez-post-text">
                                {!!$detail->content!!}
                            </div>
                            <div class="dez-post-tags clear">
                                <div class="post-tags">
                                {!!keyword_search($detail->keyword)!!}

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
