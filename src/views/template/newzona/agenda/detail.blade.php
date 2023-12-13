<div class="page-content">
        <!-- Breadcrumb  row -->
        <div class="breadcrumb-row">
            <div class="container">
                <ul class="list-inline">
                    <li><a href="{{url('/')}}">Beranda</a></li>
                    <li>Agenda</li>
                </ul>
            </div>
        </div>
        <!-- Breadcrumb  row END -->
        <!-- contact area -->
        <div class="container">
            <!-- 404 Page -->
			<div class="section-content">
                <div class="row">
                    <div class="col-lg-12">
                    <div class="section-head text-center mt-5">
                        <h2 class="text-uppercase">{{$detail->title}}</h2>
                        <div class="dez-separator-outer ">
                            <div class="dez-separator bg-primary style-skew"></div>
                        </div>
                    </div>
                    <p>Tempat : {{_field($detail,'tempat')}}<br>
    Alamat : {{_field($detail,'alamat')}}<br>
    Keterangan : {!!$detail->content!!}</p>
        <p>
            {!!$detail->content!!}
        </p>

                    </div>
                </div>
			</div>
            <!-- 404 Page END -->
        </div>
        <!-- contact area  END -->
    </div>
