
                    <div class="section-content">
                        <div class="img-carousel-content owl-carousel lightgallery owl-btn-center-lr">
                        @foreach(get_post()->groups('unit-kerja') as $r)
                        @foreach($r->post as $row)
                        @foreach(get_post()->index_child($row->id) as $rw)
                            <div class="item">
                                <div class="dez-box dez-gallery-box">

                                    <div class="dez-thum dez-img-overlay1 dez-img-effect zoom-slow"> <a href="javascript:void(0);"><img src="{{thumb($rw->thumbnail)}}" class="rounded" alt=""></a>
                                    	<div class="overlay-bx">
											<div class="overlay-icon">
												<span data-exthumbimage="{{thumb($rw->thumbnail)}}" data-src="{{thumb($rw->thumbnail)}}" class="icon-bx-xs check-km lightimg" title="{{$rw->title}} - {{_field($rw,'jabatan')}}">
													<i class="far fa-image"></i>
												</span>
											</div>
										</div>
                                    </div>

                                </div>
                            </div>
                            @endforeach
                            @endforeach
                        @endforeach
                        </div>
                    </div>
