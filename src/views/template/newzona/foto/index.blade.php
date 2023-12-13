<div class="page-content">
        <!-- inner page banner -->

        <!-- inner page banner END -->
        <!-- Breadcrumb row -->
        <div class="breadcrumb-row">
            <div class="container">
                <ul class="list-inline">
                    <li><a href="javascript:void(0);">Home</a></li>
                    <li>Foto</li>
                </ul>
            </div>
        </div>
        <!-- Breadcrumb row END -->
        <div class="content-area">
            <!-- Left & right section start -->
            <div class="container">
                <!-- Gallery -->
                <div class="section-head text-center">
                        <h2 class="text-uppercase">{{get_post_type($post_type)}}</h2>
                        <div class="dez-separator-outer ">
                            <div class="dez-separator bg-primary style-skew"></div>
                        </div>
                    </div>
                <div class="clearfix">
				
                            <ul id="masonry" class="row dez-gallery-listing gallery-grid-4 m-lr0 lightgallery">

                        @foreach($index as $row)
                        <li class="home card-container col-lg-4 col-md-4 col-sm-6">
								<div class="dez-box dez-gallery-box">
									<div class="dez-thum dez-img-overlay1 dez-img-effect zoom-slow"> <a href="javascript:void(0);"> 
										<img src="{{thumb($row->post_thumbnail)}}"  alt=""> </a>
										<div class="overlay-bx">
											<div class="overlay-icon"> 
												<span data-exthumbimage="{{thumb($row->post_thumbnail)}}" data-src="{{thumb($row->post_thumbnail)}}" class="icon-bx-xs check-km lightimg"  title="{{$row->post_title}}">		
													<i class="far fa-image"></i> 
												</span>
											</div>
										</div>
									</div>
								</div>
							</li>
                        @endforeach
              
                 
                    </ul>
                </div>
                <!-- Gallery END -->
                <!-- Pagination start -->
                <div class="pagination-bx  clearfix ">
                {{$index->links('vendor.pagination.bootstrap-5')}}
                </div>
                <!-- Pagination END -->
            </div>
            <!-- Left & right section  END -->
        </div>
    </div>