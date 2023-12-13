<div class="col-lg-3 col-md-4 col-sm-6">

                        <aside class="side-bar">
                            <div  style="background:#11112E;" class="widget  p-a20 monster-widget-placeholder-10 widget_rss">
                                <h4 class="widget-title" style="color:#E56713"> <i class="fa fa-paper-plane" aria-hidden="true"></i> Pengumuman</h4>
                                <ul>
                                   @php $pengumuman =  get_post()->detail('pengumuman'); @endphp
                                   @if($pengumuman)
                                    <li><span class="rss-date "> <b class="text-warning">{{$pengumuman->title}}</b><br> <i class="fas fa-clock    "></i> {{tanggal_indo($pengumuman->created_at)}}</span>
                                        <div class="rssSummary text-light text-justify">{!!Str::limit(strip_tags($pengumuman->content),200)!!} <a href="{{url($pengumuman->url)}}"  class="text-warning">Selengkapnya</a></div>
                                        @else
                                        <div class="alert alert-warning">Belum ada Pengumuman</div>
                                    @endif
                                </ul>
                            </div>
                            <div class="widget">
                                <div class="dez-tabs">
                                    <ul class="nav nav-tabs">
                                        <li class="nav-item"><a class="nav-link active" data-bs-toggle="tab" href="#jadwal1"> Senin-Sabtu</a></li>
                                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#jadwal2"> Jumat</a></li>
                                        <li class="nav-item"><a class="nav-link" data-bs-toggle="tab" href="#jadwal3"> Ahad</a></li>
                                    </ul>
                                    <div class="tab-content">
                                        <style>
                                            .jdw
                                               tr td {padding:2px;}
                                            .jdw td,th {border:1px solid #a8a8a8}
                                            .jdw th {color:#3b3b3b}
                                        </style>
                                    @foreach(get_post()->groups('jadwal') as $row)

                                        <div id="jadwal{{$row->sort}}" class="tab-pane {{$row->sort=='1' ? 'active':''}}" style="">
                                            <table border="1" class=" jdw" style="font-size:12px;border-collapse: collapse">
                                                <tr>
                                                    <th rowspan="2" style="text-align:center">Trip</th>
                                                    <th style="text-align:center">Air Putih</th>
                                                    <th style="text-align:center">Sei Selari</th>
                                                </tr>
                                                <tr>
                                                    <th colspan="2" style="text-align:center">Jam( WIB )</th>
                                                </tr>
                                                <style>

                                                </style>
                                                @foreach(_loop(collect($row->post)->first()) as $r)
                                                <tr>
                                                    <td style="text-align:center">
                                                        <b> {{$r->trip}}</b>
                                                    </td>
                                                    @if(Str::contains($r->jenis,'Air Putih'))
                                                    <td style="text-align:center" class="bg-danger text-white">
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

                                            </table>
                                        </div>
                                        @endforeach


                                    </div>
                                </div>
                            </div>
                        {{-- <div class="widget ">
                            {!!get_banner('<div class="mb-3">','</div>','banner-samping-kanan')!!}
                        </div> --}}

                        <div class="widget ">
                            <h4 class="widget-title"> <i class="fa fa-calendar-day" aria-hidden="true"></i> Agenda</h4>

                            <ul class="pt-0">
                                @foreach(get_post()->index_limit('agenda',5) as $row)

                                <li style="list-style: none;margin-bottom:6px"> <span style="background:#11112E;padding:5px 8px;float:left;color:white;border-radius:15px;text-align:center;margin-right:10px;" >
<h3 style="margin-bottom:-10px" class="text-warning" >{{getTgl(_field($row,'tanggal').' 00:00:00','tanggal')}}</h3><small style="font-size:10px">{{getTgl(_field($row,'tanggal').' 00:00:00','tglbulan')}}</small>
                                    </span> <small> <i class="fas fa-map-marked    "></i> {{_field($row,'tempat')}}</small><br><a href="{{url($row->url)}}" class="text-dark" style="font-size:13px;"><h5>{{Str::headline($row->title)}}</h5></a></li>
                                @endforeach
                            </ul>
                    </div>
                    <br>
                            <div class="widget recent-posts-entry">
                                <h4 class="widget-title"> <i class="fa fa-rss" aria-hidden="true"></i> Berita Popular</h4>
                                <div class="widget-post-bx">
                                    @foreach(get_post()->index_popular('berita') as $row)
                                    <div class="widget-post clearfix">
                                        <div class="dez-post-media" style="background:none"> <img class="rounded" src="{{thumb($row->thumbnail)}}" alt="" width="200" > </div>
                                        <div class="dez-post-info">
                                            <div class="dez-post-header">
                                                <h6 class="post-title"><a href="{{url($row->url)}}">{{$row->title}}</a></h6>
                                            </div>
                                            <div class="dez-post-meta">
                                                <ul>
                                                    <li class="post-author"><small>By <a href="{{url($row->url)}}">{{$row->user->name}}</a></small></li>

                                                </ul>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach

                                </div>
                            </div>
                            @if(get_module_info('group') || request()->is('/'))
                            <div class="widget widget_categories">
                                <h4 class="widget-title"><i class="fa fa-tags" aria-hidden="true"></i> Kategori {{$title ?? 'Berita'}}</h4>
                                <ul>
                                    @foreach(request()->is('/') ? get_post()->groups('berita') : get_post()->groups(get_post_type()) as $row)
                                    <li><a href="{{url($row->url)}}">{{$row->name}}</a> ({{$row->post->count()}})</li>
                                    @endforeach
                                </ul>
                            </div>
                            @endif



                        </aside>
                    </div>
