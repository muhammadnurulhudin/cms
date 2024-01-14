<!-- Sidebar menu-->
<div class="app-sidebar__overlay" data-toggle="sidebar"></div>
<aside class="app-sidebar" style="background:#1D2327;font-size:12px;">
    <div class="app-sidebar__user" style="cursor:pointer;margin-bottom:0">
        <img class="app-sidebar__user-avatar" style="width:30px;height:30px" src="{{thumb(request()->user()->photo)}}" alt="User Image">
        <div>
            <p class="app-sidebar__user-name">{{ Auth::user()->name }}</p>
            <p class="app-sidebar__user-designation">{{ ucfirst(Auth::user()->level) }}</p>
        </div>
    </div>

    <ul class="app-menu">
        <li class="text-muted" style="padding:12px 10px;font-size:small;background:#000"> <i class="fa fa-list"
                aria-hidden="true"></i> MENU</li>
        <li><a class="app-menu__item {{ Request::is('panel/dashboard') ? 'active' : '' }}"
                href="{{ route('dashboard') }}"><i class="app-menu__icon fa fa-tachometer"></i> <span
                    class="app-menu__label">Dahsboard</span></a></li>



                <li title=""><a class="app-menu__item {{ active_item_esurat('permohonan') }}" href="{{ route('permohonan.index') }}"><i class="app-menu__icon fa fa-envelope"></i> <span  class="app-menu__label">Permohonan</span></a></li>

                <li title=""><a class="app-menu__item {{ active_item_esurat('aparat') }}" href="{{ route('aparat.index') }}"><i class="app-menu__icon fa fa-user"></i> <span  class="app-menu__label">Aparat</span></a></li>

                <li title=""><a class="app-menu__item {{ active_item_esurat('jenissurat') }}" href="{{ route('jenissurat.index') }}"><i class="app-menu__icon fa fa-user"></i> <span  class="app-menu__label">Jenis Surat</span></a></li>
                <li title=""><a class="app-menu__item {{ active_item_esurat('penandatangan') }}" href="{{ route('penandatangan.index') }}"><i class="app-menu__icon fa fa-user"></i> <span  class="app-menu__label">Penandatangan</span></a></li>



    </ul>
</aside>
