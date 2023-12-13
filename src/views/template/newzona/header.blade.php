<!DOCTYPE html>
<html lang="en">

<head>
    {{ init_header() }}
    <link rel="stylesheet" type="text/css" href="{{ template_asset('css/plugins.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ template_asset('css/style.css') }}">
    <link class="skin" rel="stylesheet" type="text/css" href="{{ template_asset('css/skin/skin-6.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ template_asset('css/templete.css') }}">


    <!-- REVOLUTION SLIDER CSS -->
    <link rel="stylesheet" type="text/css"
        href="{{ template_asset('plugins/revolution/revolution/css/settings.css') }}">
    <link rel="stylesheet" type="text/css"
        href="{{ template_asset('plugins/revolution/revolution/css/navigation.css') }}">
    <!-- REVOLUTION SLIDER CSS END -->
    <style>
        .header-curve .logo-header::before {
            right: -50px;
            width: 80%;
            border-right: 7px solid #D1580C;
            -webkit-transform: skew(30deg);
            -moz-transform: skew(30deg);
            -o-transform: skew(30deg);
            -ms-transform: skew(30deg);
            transform: skew(30deg);
        }

        @media (max-width: 536px) {
            .meet-ask-row {
                margin-top: 0;
            }
        }

        .no-skew {
            background-color: #0e0e1c
        }

        .main-bar,
        .is-fixed .main-bar {
            background-color: #151445;

        }

        ul.nav.navbar-nav li a {
            color: #fff
        }

        ul.nav.navbar-nav li a:hover {
            color: #dbe938
        }

        .header-curve .logo-header::before,
        .header-curve .logo-header::after {
            background-color: #11112E;
            content: "";
            position: absolute;
            bottom: 0;
            height: 120%;
            z-index: -1;
        }
        .breadcrumb-row {
            border-top:3px #D1580C solid;background:url({{template_asset('bg/blackbg.png')}});
        }
        .breadcrumb-row .list-inline li{
            color:#fff !important;
        }
        @media only screen and (max-width: 991px) {
  .header-nav .nav li a {
  color:#E5742A !important
  }
}
    </style>
</head>

<body id="bg" >
    <div id="loading-area"></div>
    <div class="page-wraper">
        <!-- header -->
        <header class="site-header header mo-left header-style-1 ">
            <!-- top bar -->
            <div class="top-bar no-skew">
                <div class="container">
                    <div class=" d-flex bar align-items-center justify-content-between">
                        <div class="dez-topbar-left">

                        </div>
                        <div class="dez-topbar-right">
                            <ul class="social-bx list-inline pull-right">
                                <li><a target="_blank" href="{{ get_option('facebook') }}"><i
                                            class="fab fa-facebook-f"></i></a></li>
                                <li><a target="_blank" href="{{ get_option('instagram') }}"><i
                                            class="fab fa-instagram"></i></a></li>
                                <li><a target="_blank" href="{{ get_option('youtube') }}"><i
                                            class="fab fa-youtube"></i></a></li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- top bar END-->
            <!-- main header -->
            <div class="sticky-header  header-curve main-bar-wraper navbar-expand-lg">
                <div class="main-bar clearfix ">
                    <div class="container clearfix">
                        <!-- website logo -->
                        <div class="logo-header mostion bg-light ">
                            <a href="{{ secure_url('/') }}">
                                <img src="{{ asset(get_option('logo')) }}" width="193" height="89"
                                    alt="">
                            </a>
                        </div>
                        <!-- nav toggle button -->
                        <button class="navbar-toggler collapsed navicon justify-content-end class="text-primary"
                            type="button" data-bs-toggle="collapse" data-bs-target="#navbarNavDropdown"
                            aria-controls="navbarNavDropdown" aria-expanded="false" aria-label="Toggle navigation">
                            <span style="background:#E5742A"></span>
                            <span style="background:#E5742A"></span>
                            <span style="background:#E5742A"></span>

                        </button>
                        <!-- extra nav -->
                        <div class="extra-nav ">
                            <div class="extra-cell">
                                <button id="quik-search-btn" type="button" class="site-button rounded"
                                    style=""><i class="fa fa-search"></i></button>
                            </div>
                        </div>
                        <!-- Quik search -->
                        <div class="dez-quik-search bg-primary">
                            <form action="{{ url('search') }}">
                                <input name="keyword" value="" type="text" class="form-control"
                                    placeholder="Cari informasi tentang...">
                                <span id="quik-search-remove"><i class="fas fa-times"></i></span>
                            </form>
                        </div>
                        <!-- main nav -->
                        <div class="header-nav navbar-collapse collapse justify-content-end" id="navbarNavDropdown">
                            <!-- Website Logo -->
                            <div class="logo-header mostion">
                                <a href="index.html" class="logo-dark"><img src="{{ asset(get_option('logo')) }}"
                                        width="193" height="89" alt=""></a>
                            </div>
                            <ul class="nav navbar-nav">

                                <li> <a href="{{ secure_url('/') }}"><i class="fa fa-home "></i> Beranda </a>

                                </li>
                                @foreach (get_menu('header') as $row)
                                    @if (get_menu('header', $row->id)->count() == 0)
                                        <li><a href="{{ link_menu($row->link) }}">{{ $row->name }}</a></li>
                                    @else
                                        <li><a href='#'>{{ $row->name }} <i class="fa fa-angle-down"></i></a>
                                            <ul class="sub-menu">
                                                @foreach (get_menu('header', $row->id) as $row2)
                                                    @if (get_menu('header', $row2->id)->count() > 0)
                                                        <li><a href="#">{{ $row2->name }}</a>
                                                            <ul class="sub-menu">
                                                                @foreach (get_menu('header', $row2->id) as $row3)
                                                                    @if (get_menu('header', $row3->id)->count() > 0)
                                                                        <li><a
                                                                                href="{{ link_menu($row3->link) }}">{{ $row3->name }}</a>
                                                                        </li>
                                                                    @else
                                                                        <li><a href="#">{{ $row3->name }} </a>
                                                                            <ul class="sub-menu">
                                                                                @foreach (get_menu('header', $row3->id) as $row4)
                                                                                    <li><a
                                                                                            href="{{ link_menu($row4->link) }}">{{ $row4->name }}</a>
                                                                                    </li>
                                                                                @endforeach

                                                                            </ul>
                                                                        </li>
                                                                    @endif
                                                                @endforeach

                                                            </ul>
                                                        </li>
                                                    @else
                                                        <li><a
                                                                href="{{ link_menu($row2->link) }}">{{ $row2->name }}</a>
                                                        </li>
                                                    @endif
                                                @endforeach
                                            </ul>
                                        </li>
                                    @endif
                                @endforeach



                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            <!-- main header END -->
        </header>
