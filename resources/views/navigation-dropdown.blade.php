<!-- <nav x-data="{ open: false }"  class="bg-white border-b border-gray-100"> -->
    <!-- Sidebar -->
    <nav id="sidebar" aria-label="Main Navigation">
        <!-- Side Header -->
        <div class="content-header bg-white-5">
            <!-- Logo -->
            <a class="font-w600 text-dual" href="{{ route('dashboard') }}">
                <i class="fab fa-gofore text-primary"></i>
                <!-- <i class="fab fa-facebook-f text-primary"></i>
                <i class="fab fa-tumblr text-primary"></i> -->
                <span class="smini-hide">
                    <span class="font-w700 font-size-h5">FT</span> <span class="font-w400">1.0</span>
                </span>
            </a>
            <!-- END Logo -->

            <!-- Options -->
            <div>
                <!-- Close Sidebar, Visible only on mobile screens -->
                <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                <a class="d-lg-none text-dual ml-3" data-toggle="layout" data-action="sidebar_close" href="javascript:void(0)">
                    <i class="fa fa-times"></i>
                </a>
                <!-- END Close Sidebar -->
            </div>
            <!-- END Options -->
        </div>
        <!-- END Side Header -->

        <!-- Side Navigation -->
        <div class="content-side content-side-full">
            <ul class="nav-main">
                <li class="nav-main-item open">
                    <a class="nav-main-link nav-main-link-submenu" href="#" data-toggle="submenu" aria-haspopup="true" aria-expanded="true">
                        <i class="nav-main-link-icon far fa-id-card"></i>
                        <span class="nav-main-link-name">Driver Scorecards</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="/upload/photo">
                                <span class="nav-main-link-name">Upload Driver Photos</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="/upload/scorecards">
                                <span class="nav-main-link-name">Import Scorecards</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="/scorecards">
                                <span class="nav-main-link-name">View Scorecards</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-main-item open">
                    <a class="nav-main-link nav-main-link-submenu" href="#" data-toggle="submenu" aria-haspopup="true" aria-expanded="true">
                        <i class="nav-main-link-icon fa fa-truck"></i>
                        <span class="nav-main-link-name">Fleet Information</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="/fleet/list">
                                <span class="nav-main-link-name">Fleet</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="/mmr">
                                <span class="nav-main-link-name">MMR</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="/upload/statement">
                        <i class="nav-main-link-icon fa fa-file-csv"></i>
                        <span class="nav-main-link-name">GF Statements</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="/drivers">
                        <i class="nav-main-link-icon fa fa-users"></i>
                        <span class="nav-main-link-name">Drivers</span>
                    </a>
                </li>
                <li class="nav-main-item open">
                    <a class="nav-main-link nav-main-link-submenu" href="#" data-toggle="submenu" aria-haspopup="true" aria-expanded="true">
                        <i class="nav-main-link-icon far fa-calendar"></i>
                        <span class="nav-main-link-name">Weekly Schedule</span>
                    </a>
                    <ul class="nav-main-submenu">
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="/schedule/upload">
                                <span class="nav-main-link-name">Upload Schedule</span>
                            </a>
                        </li>
                        <li class="nav-main-item">
                            <a class="nav-main-link" href="/schedule/search">
                                <span class="nav-main-link-name">View Schedule</span>
                            </a>
                        </li>
                    </ul>
                </li>
                <li class="nav-main-heading">By Key Metrics</li>
                <li class="nav-main-heading">Revenue</li>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="/chart/total-revenue-week">
                        <i class="nav-main-link-icon fa fa-chart-pie"></i>
                        <span class="nav-main-link-name">Revenue Per Week</span>
                    </a>
                </li>
                <li class="nav-main-heading">Miles</li>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="/chart/total-miles-week">
                        <i class="nav-main-link-icon fa fa-chart-bar"></i>
                        <span class="nav-main-link-name">Miles Per Week (Total)</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="/chart/miles-week-driver">
                        <i class="nav-main-link-icon fa fa-chart-bar"></i>
                        <span class="nav-main-link-name">Miles Per Week (Driver)</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="/chart/miles-week-vehicle">
                        <i class="nav-main-link-icon fa fa-chart-pie"></i>
                        <span class="nav-main-link-name">Miles Per Week (Vehicle)</span>
                    </a>
                </li>
                <li class="nav-main-heading">MPG</li>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="/chart/mpg-week-vehicle">
                        <i class="nav-main-link-icon fa fa-chart-line"></i>
                        <span class="nav-main-link-name">MPG Per Week (Vehicle)</span>
                    </a>
                </li>
                <li class="nav-main-heading">Fuel Cost</li>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="/chart/total-fuelcost-week">
                        <i class="nav-main-link-icon fa fa-chart-bar"></i>
                        <span class="nav-main-link-name">Cost Per Week (Total)</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="/chart/fuelcost-week-vehicle">
                        <i class="nav-main-link-icon fa fa-chart-pie"></i>
                        <span class="nav-main-link-name">Cost Per Week (Vehicle)</span>
                    </a>
                </li>
                <li class="nav-main-heading">Payroll</li>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="/payroll/rates">
                        <i class="nav-main-link-icon fa fa-cogs"></i>
                        <span class="nav-main-link-name">Rate Setting</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="/payroll">
                        <i class="nav-main-link-icon fa fa-money-check"></i>
                        <span class="nav-main-link-name">Payroll Per Driver</span>
                    </a>
                </li>
                <li class="nav-main-heading">Utilities</li>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="/util/ext-links">
                        <i class="nav-main-link-icon fa fa-external-link-alt"></i>
                        <span class="nav-main-link-name">Favorite Websites</span>
                    </a>
                </li>
                <li class="nav-main-item">
                    <a class="nav-main-link" href="{{ route('util.download-data') }}">
                        <i class="nav-main-link-icon fa fa-download"></i>
                        <span class="nav-main-link-name">Download Data</span>
                    </a>
                </li>
            </ul>
        </div>
        <!-- END Side Navigation -->
    </nav>
    <!-- END Sidebar -->
    <!-- Header -->
    <header  id="page-header" >
        <!-- Header Content -->
        <div class="content-header">
            <!-- Left Section -->
            <div class="d-flex align-items-center">
                <!-- Toggle Sidebar -->
                <!-- Layout API, functionality initialized in Template._uiApiLayout()-->
                <button type="button" class="btn btn-sm btn-dual mr-2 d-lg-none" data-toggle="layout" data-action="sidebar_toggle">
                    <i class="fa fa-fw fa-bars"></i>
                </button>
                <!-- END Toggle Sidebar -->

                <!-- Toggle Mini Sidebar -->
                <!-- Layout API, functionality initialized in Template._uiApiLayout()-->
                <button type="button" class="btn btn-sm btn-dual mr-2 d-none d-lg-inline-block" data-toggle="layout" data-action="sidebar_mini_toggle">
                    <i class="fa fa-fw fa-ellipsis-v"></i>
                </button>
                <!-- END Toggle Mini Sidebar -->
            </div>
            <!-- END Left Section -->
            <div class="d-flex align-item-center">
                <div class="row">
                    <img src="{{ asset('/media/photos/logo.jpg') }}" height="60" />
                    <h1 class="gft-title">Ground Force Trucking</h1>
                </div>
            </div>
            <!-- Right Section -->
            <div class="d-flex align-items-center">
                <!-- User Dropdown -->
                <div class="dropdown d-inline-block ml-2">
                    <button type="button" class="btn btn-sm btn-dual" id="page-header-user-dropdown" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
                        <img class="rounded" src="{{ asset('/media/avatars/avatar10.jpg') }}" alt="Header Avatar" style="width: 18px;">
                        <span class="d-none d-sm-inline-block ml-1">{{ Auth::user()->name }}</span>
                        <i class="fa fa-fw fa-angle-down d-none d-sm-inline-block"></i>
                    </button>
                    <div class="dropdown-menu dropdown-menu-right p-0 border-0 font-size-sm" aria-labelledby="page-header-user-dropdown">
                        <div class="p-3 text-center bg-primary">
                            <img class="img-avatar img-avatar48 img-avatar-thumb" src="{{ asset('/media/avatars/avatar10.jpg') }}" alt="">
                        </div>
                        <div class="p-2">
                            <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('user-setting') }}">
                                <span>Settings</span>
                                <i class="si si-settings"></i>
                            </a>
                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}">
                                @csrf
                                <a class="dropdown-item d-flex align-items-center justify-content-between" href="{{ route('logout') }}" onclick="event.preventDefault();
                                                                this.closest('form').submit();">
                                    <span>{{ __('Log Out') }}</span>
                                    <i class="si si-logout ml-1"></i>
                                </a>
                            </form>
                        </div>
                    </div>
                </div>
                <!-- END User Dropdown -->
   
            </div>
            <!-- END Right Section -->
        </div>
        <!-- END Header Content -->

        <!-- Header Search -->
        <div id="page-header-search" class="overlay-header bg-white">
            <div class="content-header">
                <form class="w-100" action="be_pages_generic_search.html" method="POST">
                    <div class="input-group input-group-sm">
                        <div class="input-group-prepend">
                            <!-- Layout API, functionality initialized in Template._uiApiLayout() -->
                            <button type="button" class="btn btn-danger" data-toggle="layout" data-action="header_search_off">
                                <i class="fa fa-fw fa-times-circle"></i>
                            </button>
                        </div>
                        <input type="text" class="form-control" placeholder="Search or hit ESC.." id="page-header-search-input" name="page-header-search-input">
                    </div>
                </form>
            </div>
        </div>
        <!-- END Header Search -->

        <!-- Header Loader -->
        <!-- Please check out the Loaders page under Components category to see examples of showing/hiding it -->
        <div id="page-header-loader" class="overlay-header bg-white">
            <div class="content-header">
                <div class="w-100 text-center">
                    <i class="fa fa-fw fa-circle-notch fa-spin"></i>
                </div>
            </div>
        </div>
        <!-- END Header Loader -->
    </header>
    <!-- END Header -->
<!-- </nav> -->