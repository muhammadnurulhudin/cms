<meta charset="utf-8">
<title>{{request()->is('/') ? $title : $title.' - '.get_option('site_title')}}</title>
  <meta http-equiv="X-UA-Compatible" content="IE=edge">

<meta name="author" content="Abu Umar's House">
<meta charset="UTF-8">
<meta name="language" content="Indonesia" />
<meta name="revisit-after" content="7" />
<meta name="webcrawlers" content="all" />
<meta name="rating" content="general" />
<meta name="spiders" content="all" />
<meta name="robots" content="all" />
<meta property="fb:app_id" content="" />
<meta property="fb:pages" content="" />
<meta name="facebook-domain-verification" content="" />
<!-- meta fb og start -->
<meta property="og:url" content="{{$url}}" />
<meta property="og:type" content="article" />
<meta property="og:title" content="{{$title}}" />
<meta property="og:image" content="{{$thumbnail}}" />
<meta property="og:site_name" content="{{get_option('site_title')}}" />
<meta property="og:description" content="{{ $description}}" />
<meta name="description" content="{{ $description}}">
<meta name="keywords" content="{{ $keywords}}">
<!-- meta fb og end -->

  <!-- FAVICONS ICON -->
  <link rel="icon" href="{{file_exists(public_path(get_option('favicon'))) ? url(get_option('favicon')) : 'data:image/x-icon;base64,AAABAAEAEBAAAAEAIABoBAAAFgAAACgAAAAQAAAAIAAAAAEAIAAAAAAAAAQAAAAAAAAAAAAAAAAAAAAAAAAAAAAAsWkA/2Y+FP9mPhT/Zj4U/2Y+FP9mPhT/Zj4U/2Y+FP9mPhT/Zj4U/2Y+FP9mPhT/Zj4U/7FpAP8AAAAAAAAAALFpAP9mPhT/Zj4U/2Y+FP9mPhT/Zj4U/2Y+FP9mPhT/Zj4U/2Y+FP9mPhT/CAUC/2Y+FP+xaQD/AAAAAAAAAACxaQD/Zj4U/2Y+FP9mPhT/Zj4U/2Y+FP9mPhT/Zj4U/2Y+FP9mPhT/EwwE/2Y+FP9mPhT/sWkA/wAAAAAAAAAAsWkA/2Y+FP9mPhT/Zj4U/2Y+FP9mPhT/Zj4U/2Y+FP8XDgX/Zj4U/2Y+FP9mPhT/Zj4U/7FpAP8AAAAAAAAAAAAAAAAAAAD/AgEA/2Y+FP8AAAD/GBwm/wAAAP8AAAD/AQEB/wMCAP9gOxP/JBUA/xEKAP8AAAAAAAAAAAAAAAAAAAAAAAAAAJlbBv8KDA//p8b2/5at4v91ibr/dYm6/5at4v+WreL/rM38/2Y+FP8AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAACszfz/lq3i/5at4v+WreL/lq3i/5at4v+WreL/lq3i/5at4v+szfz/AAAAAAAAAAAAAAAAAAAAAAgIDP8AAAD/qsv6/5at4v+WreL/fJHD/4Wazf+WreL/lq3i/5at4v+WreL/pcT0/w8SFv8PEhj/AAAAAKzN/P+WreL/rM38/6zN/P+WreL/lq3i/5at4v+WreL/lq3i/5at4v+WreL/lq3i/6zN/P+szfz/jqTV/6zN/P8AAAD/AAAA/wAAAP+szfz/CAgI//////91ibr/lq3i/5at4v91ibr/AgMI/wAAAP+szfz/BQYI/wAAAP8AAAD/AAAAAFZWVv8AAAD/AAAA/wAAAP+WreL/lq3i/5at4v+WreL/lq3i/5at4v8AAAD/AAAA/wAAAP8AAAD/AAAAAAAAAAAAAAD/AAAA/3aNrf+WreL/lq3i/5at4v+Qptn/dYex/5at4v+WreL/lq3i/1lqgv8AAAD/BgYG/6mchTEAAAAAAAAA/wAAAP8AAAD/AAAA/wAAAP8AAAD/AAAA/wAAAP8AAAD/AAAA/6zN/P8AAAD/AAAA/1dXV/8AAAAAAAAAAFdXV/9XV1f/MjIy/wAAAP8AAAD/AAAA/wAAAP8AAAD/TU1N/1dXV/9XV1f/AAAA/ysrK/9XV1f/AAAAAAAAAAAAAAAAs7OzNVdXV/9XV1f/DQ0N/wAAAP8AAAD/AAAA/wAAAP8AAAD/V1dX/1dXV/8AAAD/AAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAAEpKSv9XV1f/V1dX/1dXV/9XV1f/V1dX/wAAAAAAAAAAAAAAAAAAAAAAAAAAgAEAAIABAACAAQAAgAEAAMADAADgBwAA4AcAAIABAAAAAAAAAAAAAIABAACAAQAAgAEAAIABAADgAwAA+B8AAA=='}}" type="image/x-icon" />
  <!-- PAGE TITLE HERE -->

  <!-- MOBILE SPECIFIC -->
  <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, shrink-to-fit=no">
  <meta name="mobile-web-app-capable" content="yes">
  <meta name="apple-mobile-web-app-capable" content="yes">
  <meta name="apple-touch-fullscreen" content="yes">
  <meta name="theme-color" content="#07c">
  <!-- home screen icon defaults - 48x48 -->
  <link rel="apple-touch-icon" href=""/>
  <meta name="HandheldFriendly" content="True">
  <link rel="apple-touch-startup-image" href="">
  <meta name="application-name" content="{{get_option('site_title')}}">
  @if(get_post_type())
  <script type="text/javascript" src="https://platform-api.sharethis.com/js/sharethis.js#property=63e08254b71a0b00126c118c&product=inline-share-buttons" async="async"></script>
  @endif
