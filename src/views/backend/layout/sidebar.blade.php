<!-- Sidebar menu-->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar" style="background:#1D2327;font-size:12px;" >
   <div class="app-sidebar__user" style="cursor:pointer;margin-bottom:0"  >
      <img class="app-sidebar__user-avatar" style="width:30px;height:30px" src="" alt="User Image">
      <div>
         <p class="app-sidebar__user-name">{{Auth::user()->name}}</p>
         <p class="app-sidebar__user-designation">{{ucfirst(Auth::user()->level)}}</p>
      </div>
   </div>

   <ul class="app-menu">
   <li class="text-muted" style="padding:12px 10px;font-size:small;background:#000"> <i class="fa fa-list" aria-hidden="true"></i> MENU</li>
           <li><a class="app-menu__item {{(Request::is(admin_path().'/dashboard'))?'active':''}}" href="{{admin_url('dashboard')}}"><i class="app-menu__icon fa fa-tachometer"></i> <span class="app-menu__label">Dahsboard</span></a></li>
    <?php
    if(Auth::user()->level=='operator'){
      $menu = collect(get_module())->where('operator',true);
    }else {
      $menu = collect(get_module())->sortBy('position');
    }
    ?>

     @foreach($menu as $pos => $row)
      @if((Auth::user()->level=='operator' ? collect(get_module())->where('parent',$row->name)->where('operator',true)->count() : collect(get_module())->where('parent',$row->name)->count())> 0)
     <li class="treeview {{active_treeview($row->name)}}" title="{{$row->name}}" >
        <a class="app-menu__item" href="#" data-toggle="treeview"><i class="app-menu__icon fa {{$row->icon}}"></i><span class="app-menu__label">{{$row->title}}</span><i class="treeview-indicator fa fa-angle-right"></i></a>
        <ul class="treeview-menu" style="background:rgb(000,000,000,.4)">
          @foreach((Auth::user()->level=='operator' ? collect(get_module())->where('parent',$row->name)->where('operator',true) : collect(get_module())->where('parent',$row->name)) as $row2)
            <li><a class="treeview-item {{active_item($row2->name)}}" href="{{admin_url($row2->name)}}"><i class="icon fa fa-caret-right"></i> &nbsp; {{$row2->title}}</a></li>
           @endforeach
        </ul>
     </li>

     @else

    <li title=""><a class="app-menu__item {{active_item($row->name)}}" href="{{admin_url($row->name)}}"><i class="app-menu__icon fa {{$row->icon}}"></i> <span class="app-menu__label">{{$row->title}}</span></a></li>

    @endif

    @endforeach
    <li class="text-muted" style="padding:12px 10px;font-size:small;background:#000"><i class="fa fa-lock" aria-hidden="true"></i> &nbsp; ADMINISTRATOR</li>

          <li title="Komentar Pengunjung"><a class="app-menu__item {{(Request::is(admin_path().'/comments'))? 'active':''}}" href="{{admin_url('comments')}}"><i class="app-menu__icon fa fa-comments"></i> <span class="app-menu__label">Komentar</span></a></li>



   </ul>
</aside>
