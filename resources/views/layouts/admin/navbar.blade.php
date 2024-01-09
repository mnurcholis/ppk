<div class="navbar navbar-expand-md navbar-dark">
    <div class="navbar-brand">
        <a href="{{ url('dashboard') }}" class="d-inline-block">
            <img src="{{ get_setting()->logo ? route('helper.show-picture', ['path' => get_setting()->logo]) : asset('limitless/global_assets/images/logo_light.png') }}"
                alt="">
        </a>
    </div>

    <div class="d-md-none">
        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbar-mobile">
            <i class="icon-tree5"></i>
        </button>
        <button class="navbar-toggler sidebar-mobile-main-toggle" type="button">
            <i class="icon-paragraph-justify3"></i>
        </button>
    </div>

    <div class="collapse navbar-collapse" id="navbar-mobile">
        <ul class="navbar-nav">
            <li class="nav-item">
                <a href="#" class="navbar-nav-link sidebar-control sidebar-main-toggle d-none d-md-block">
                    <i class="icon-paragraph-justify3"></i>
                </a>
            </li>
        </ul>

        <span class="navbar-text ml-md-3 mr-md-auto">
            <span class="badge bg-success">Online</span>
        </span>

        <ul class="navbar-nav">

            <li class="nav-item dropdown dropdown-user">
                <a href="#" class="navbar-nav-link dropdown-toggle" data-toggle="dropdown">
                    <img src="{{ asset('limitless/') }}/global_assets/images/placeholders/placeholder.jpg"
                        class="rounded-circle" alt="">
                    <span>{{ auth()->user()->name }}</span>
                </a>

                <div class="dropdown-menu dropdown-menu-right">
                    <a href="#" class="dropdown-item"><i class="icon-user-plus"></i> My profile</a>
                    <div class="dropdown-divider"></div>
                    <a href="{{ route('setting') }}" class="dropdown-item"><i class="icon-cog5"></i> Setting Web</a>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button type="submit" class="dropdown-item"><i class="icon-switch2"></i> Logout</a>
                    </form>
                </div>
            </li>
        </ul>
    </div>
</div>
