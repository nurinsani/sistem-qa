<nav class="mt-2">
    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
        @foreach ($menus as $menu)
            @if ($menu->children->count())
                <li class="nav-item has-treeview">
                    <a href="#" class="nav-link">
                        <i class="nav-icon {{ $menu->icon }}"></i>
                        <p>
                            {{ $menu->name }}
                            <i class="right fas fa-angle-left"></i>
                        </p>
                    </a>

                    <ul class="nav nav-treeview">
                        @foreach ($menu->children as $child)
                            <li class="nav-item">
                                <a href="{{ url($child->url) }}" class="nav-link">
                                    <i class="nav-icon {{ $child->icon }}"></i>
                                    <p>{{ $child->name }}</p>
                                </a>
                            </li>
                        @endforeach
                    </ul>
                </li>
            @else
                <li class="nav-item">
                    <a href="{{ url($menu->url) }}" class="nav-link">
                        <i class="nav-icon {{ $menu->icon }}"></i>
                        <p>{{ $menu->name }}</p>
                    </a>
                </li>
            @endif
        @endforeach
    </ul>
</nav>