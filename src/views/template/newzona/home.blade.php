
@if(request()->is('/'))
<div id="carouselExampleControls" class="carousel slide" data-bs-ride="carousel" style="border-top:3px #D1580C solid">
    <div class="carousel-inner">
        @foreach(get_post()->index_by_group('banner','slider') as $row)
      <div class="carousel-item {{$loop->first ? 'active' :''}}">
        <img src="{{thumb($row->thumbnail)}}" class="d-block w-100">
      </div>
      @endforeach

    </div>
    <button class="carousel-control-prev" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="prev">
      <span class="carousel-control-prev-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Previous</span>
    </button>
    <button class="carousel-control-next" type="button" data-bs-target="#carouselExampleControls" data-bs-slide="next">
      <span class="carousel-control-next-icon" aria-hidden="true"></span>
      <span class="visually-hidden">Next</span>
    </button>
  </div>
@endif
<div class="section-full " style="border-top:3px #D1580C solid;background:url('template/newzona/bg/blackbg.png')">
            <div class="container">
                <div class="row p-tb30">
<div class="col-lg-12 ">
<div class="row">
    @foreach(get_post()->index('link') as $row)
    <div class="col-lg-3 col-md-4 col-6 m-b10">
        <div class="icon-bx-wraper center">
            <div class="icon-bx-lg  m-b1"> <a href="{{url($row->url)}}" class=""><img src="{{thumb($row->thumbnail)}}" alt=""></a> </div>
            <div class="icon-content">
                <h5 class="dez-tilte text-uppercase" style="color:#E56713">{{$row->title}}</h5>
            </div>
        </div>
    </div>
    @endforeach


</div>
                    </div>

                </div>
            </div>
        </div>


<div class="page-content" >

    <?php $sambutan = get_post()->detail('sambutan');?>
<div style="background:#11112E;" >
            <div class="container pt-4">


            <center><h4 class="text-uppercase" style="padding:10px;color:#E56713"> Sambutan Pimpinan</h4></center>

                <div class="row">

        <div class="col-lg-2 order-1">
            <div class="dez-thu m-b30"><img class="rounded" src="{{thumb($sambutan->thumbnail ??'/')}}" alt="">
                <div class="text-center mt-3">
                    <span style="color:#E5742A;"><b>{{_field($sambutan,'nama_pemberi_sambutan')}}</b></span><br>
                    <small style="color:#fdfdfd">{{_field($sambutan,'jabatan')}}</small>
                 </div></div>
        </div>
        <div class="col-lg-10 order-1 text-white">

            <div class="clear"></div>
           {!!$sambutan->content ?? null!!}

        </div>

</div></div></div>
        <div class="content-area">
            <div class="container">

                <div class="row">

                    <!-- Left part start -->
                    <div class="col-lg-9 col-md-8 col-sm-6">

            <div class="section-head text-center">
                        <h3 class="text-uppercase">Berita Terbaru</h3>
                        <div class="dez-separator-outer ">
                            <div class="dez-separator bg-primary style-skew"></div>
                        </div>

                    </div>



                        @foreach(get_post()->index_limit('berita',6) as $row)
                        <div class="blog-post blog-md clearfix date-style-2" >
                            <div class="dez-post-media dez-img-effect zoom-slow" style="background:none"> <a href="javascript:void(0);"><img class="rounded" src="{{thumb($row->thumbnail)}}" alt=""></a> </div>
                            <div class="dez-post-info">
                                <div class="dez-post-title ">
                                    <h3 class="post-title"><a href="{{url($row->url)}}">{{$row->title}}</a></h3>
                                </div>
                                <div class="dez-post-meta ">
                                    <ul>
                                        <li class="post-date" style="border-radius:0 0 10px 10px"> <i class="fa fa-calendar"></i><strong>{{gettgl($row->created_at,'tglbulan')}}</strong> <span> {{gettgl($row->created_at,'tahun')}}</span> </li>
                                        <li class="post-author"><i class="fa fa-user"></i> <a href="javascript:void(0);">{{$row->user->name}}</a> </li>
                                        <li class="post-comment"><i class="fa fa-tag"></i>{!!get_group($row->group)!!} </li>
                                    </ul>
                                </div>
                                <div class="dez-post-text">
                                    <p>{{Str::limit(strip_tags($row->content),300)}} [ <a href="{{url($row->url)}}">Baca</a> ]</p>
                                </div>


                            </div>
                        </div>
                    @endforeach
                        <!-- Pagination start -->
                        <div class="pagination-bx clearfix m-b30 text-center">
                         <a href="{{url('berita')}}" class="btn btn-primary btn-sm"><i class="fa fa-newspaper" aria-hidden="true"></i> Semua Berita</a>
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

        <!-- About Company END -->
        <!-- Our Projects  -->

     <div class="container">
     <div class="Section-full">
     <div class="section-head  text-center text-dark mb-2 mt-3">
                    <h2 class="text-uppercase">Gallery</h2>
                    <div class="dez-separator-outer ">
                        <div class="dez-separator bg-dark style-skew"></div>
                    </div>

                </div>
                    <div class="section-content text-center">
                        <div class="owl-carousel img-carousel-content lightgallery owl-btn-center-lr ">
                            @foreach(get_post()->index_limit('foto',5) as $row)
                            <div class="item">
								<div class="dez-box dez-gallery-box">
									<div class="dez-thum dez-img-overlay1 dez-img-effect zoom-slow"> <a href="javascript:void(0);">
										<img src="{{thumb($row->thumbnail)}}"  alt="{{$row->title}}"> </a>
										<div class="overlay-bx">
											<div class="overlay-icon">
												<span data-exthumbimage="{{thumb($row->thumbnail)}}" data-src="{{thumb($row->thumbnail)}}" class="icon-bx-xs check-km lightimg" title="{{$row->title}}">
													<i class="far fa-image"></i>
												</span>
											</div>
										</div>
									</div>
								</div>
                            </div>
                            @endforeach

                        </div>
                    </div>
                </div>
     </div>



        <div class="section-full  bg-white content-inner overlay-white-middle" style=" background-position:right center; background-repeat:no-repeat; background-size: auto 100%;">
            <div class="container">
                <div class="section-head text-center">
                    <h2 class="text-uppercase"> Kepegawaian</h2>
                    <div class="dez-separator-outer ">
                        <div class="dez-separator bg-secondry style-skew"></div>
                    </div>

                </div>
                   @include(blade_path('pegawai'))
                </div>
        </div>

        <!-- Client logo END -->
    </div>
