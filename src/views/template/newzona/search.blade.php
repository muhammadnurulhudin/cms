<div class="page-content">
        <!-- inner page banner -->

        <!-- Breadcrumb row END -->
        <div class="content-area">
            <div class="container">

                <div class="row">
                    <!-- Left part start -->
                    <div class="col-lg-9 col-md-8 col-sm-6">
                    <div class="section-head text-center">
                        <h2 class="text-uppercase">Hasil Pencarian</h2>
                        <div class="dez-separator-outer ">
                            <div class="dez-separator bg-primary style-skew"></div>
                        </div>
                        <p>"{{$title}}"</p>
                    </div>
                    <div class="section-content">
                    <div class="dez-divider divider-4px bg-gray-dark text-gray-dark icon-left"><i class="fa fa-search bg-primary text-white"></i></div>
                    @foreach($index as $row)

                        <div class="m-b10 border-bottom py-1">
                            <small class="text-muted"> <i class="fas fa-clock "></i> {{tanggal_indo($row->created_at)}} / <a href="{{url($row->type)}}">{{get_module_info('title',$row->type)}}</a></small>
                           <h5 class="post-title" > <a href="{{url($row->url)}}">{{$row->title}} </a></h5>


                        </div>
                    @endforeach

                    </div>

                        <!-- Pagination start -->
                        <div class="pagination-bx clearfix m-b30">
                        {{ $index->links('pagination::bootstrap-5')}}
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
