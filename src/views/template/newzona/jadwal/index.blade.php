<div class="page-content">
    <!-- Breadcrumb  row -->
    <div class="breadcrumb-row" style="border-top:3px #D1580C solid;background:url({{template_asset('bg/blackbg.png')}})">
        <div class="container">
            <ul class="list-inline">
                <li><a href="{{url('/')}}">Home</a></li>
                <li>{{$title}}</li>
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
                    <h2 class="text-uppercase">Jadwal Roro</h2>
                    <div class="dez-separator-outer ">
                        <div class="dez-separator bg-primary style-skew"></div>
                    </div>
                </div>
                <ul class="nav nav-tabs nav-fill" role="tablist">
                    <li class="nav-item">
                      <a class="nav-link active" data-bs-toggle="tab" href="#jadwal1">Senin s/d Kamis, Sabtu</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" data-bs-toggle="tab" href="#jadwal2">Jumat</a>
                    </li>
                    <li class="nav-item">
                      <a class="nav-link" data-bs-toggle="tab" href="#jadwal3">Minggu</a>
                    </li>
                  </ul>

                  <!-- Tab panes -->
                  <div class="tab-content">
                    @foreach(get_post()->groups('jadwal') as $row)
                    <div id="jadwal{{$row->sort}}" class="container tab-pane {{$row->sort=='1' ? 'active':''}}"><br>
                    <table class="table table-striped table-bordered "  style="font-size:small">
                        <thead>
                            <tr>
                                <th rowspan="2" style="vertical-align: middle;text-align:center">Trip</th>
                                <th style="text-align:center">Air Putih</th>
                                <th style="text-align:center">Sungai Selari</th>

                            </tr>
                            <tr>
                                <th style="text-align:center" colspan="2">Waktu Keberangkatan</th>

                            </tr>
                        </thead>
                        <tbody>
                                @foreach(_loop(collect($row->post)->first()) as $r)
                            <tr>
                                <td style="text-align:center">
                                    <b> {{$r->trip}}</b>
                                </td>
                                @if(Str::contains($r->jenis,'Air Putih'))
                                <td style="text-align:center" class="bg-danger">
                                   <b>{{$r->sungai_selari}} </b>(TRIP BBM)

                                </td>
                                @else
                                <td style="text-align:center">
                                 <b> {{$r->sungai_selari}}</b>
                                </td>
                                @endif
                                 @if(Str::contains($r->jenis,'Selari'))
                                 <td style="text-align:center" class="bg-danger text-white">
                                    <b>  {{$r->sungai_selari}}</b> (TRIP BBM)

                                 </td>
                                 @else
                                 <td style="text-align:center">
                                    <b>{{$r->sungai_selari}}</b>
                                 </td>
                                 @endif
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                    </div>
                    @endforeach

                  </div>

                </div>
            </div>
        </div>
        <!-- 404 Page END -->
    </div>
    <!-- contact area  END -->
</div>
