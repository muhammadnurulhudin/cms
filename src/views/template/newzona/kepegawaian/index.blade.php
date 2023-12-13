<div class="page-content">
        <!-- inner page banner -->

        <!-- inner page banner END -->
        <!-- Breadcrumb row -->
        <div class="breadcrumb-row">
            <div class="container">
                <ul class="list-inline">
                    <li><a href="javascript:void(0);">Home</a></li>
                    <li>Kepegawaian</li>
                </ul>
            </div>
        </div>
        <!-- Breadcrumb row END -->
        <div class="content-area">
            <!-- Left & right section start -->
            <div class="container">
                <!-- Gallery -->
                <div class="section-head text-center">
                        <h2 class="text-uppercase">{{$title}}</h2>
                        <div class="dez-separator-outer ">
                            <div class="dez-separator bg-primary style-skew"></div>
                        </div>
                    </div>
                    <div class="section-content">
                            <ul id="masonry" class="row dez-gallery-listing gallery-grid-4 m-lr0 lightgallery">

                        @foreach(get_post()->groups('kepegawaian') as $r)
                        @foreach($r->post as $row)

                        <li class="home card-container col-md-4 col-sm-6 mb-1">

								<div class="dez-box dez-gallery-box">
									<div class="dez-thum dez-img-overlay1 dez-img-effect zoom-slow"> <a href="javascript:void(0);">
										<img src="{{thumb($row->thumbnail)}}"  alt=""> </a>
										<div class="overlay-bx">
											<div class="overlay-icon">
												<span data-exthumbimage="{{thumb($row->thumbnail)}}" data-src="{{thumb($row->thumbnail)}}" class="icon-bx-xs check-km lightimg"  title="{{$row->title}}">
													<i class="far fa-image"></i>
												</span>
											</div>
										</div>
									</div>
								</div>
							</li>
                        @endforeach
                        @endforeach


                    </ul>
                    </div>
                <!-- Gallery END -->
                <!-- Pagination start -->

                <!-- Pagination END -->
            </div>
            <!-- Left & right section  END -->
        </div>
    </div>
