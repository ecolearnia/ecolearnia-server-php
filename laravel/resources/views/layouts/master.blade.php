<html>
    <head>
        <title>EcoLearnia - @yield('title')</title>

        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.2.1/plugins/foundation.core.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.2.1/plugins/foundation.util.mediaQuery.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.2.1/plugins/foundation.util.keyboard.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.2.1/plugins/foundation.util.box.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.2.1/plugins/foundation.util.nest.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.2.1/plugins/foundation.util.triggers.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.2.1/plugins/foundation.dropdown.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/foundation/6.2.1/plugins/foundation.dropdownMenu.js"></script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
        <script type="text/javascript" src="{{ asset('js/deps.bundle.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/main.bundle.js') }}"></script>
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.6.2/css/font-awesome.min.css">
        <link href='https://fonts.googleapis.com/css?family=Special+Elite' rel='stylesheet' type='text/css'>
        <script src='https://code.responsivevoice.org/responsivevoice.js'></script>
    </head>
    <body>

        <!-- Top Menu -->
        <div class="header">
            @section('top-bar')
            <!-- Site Navigation -->
            <div class="top-bar">
              <div class="top-bar-title">
                <span data-responsive-toggle="responsive-menu" data-hide-for="medium">
                  <span class="menu-icon dark" data-toggle></span>
                </span>
                <strong><a href="/portal">EcoLearnia</a></strong>
              </div>
              <div id="responsive-menu">
                <div class="top-bar-left">
                    <ul class="menu">
                      <li><input type="search" placeholder="Search"></li>
                      <li><button type="button" class="button">Search</button></li>
                    </ul>
                </div>
                <div class="top-bar-right">
                    <ul class="menu">
@if (Auth::check())
                    <!-- AUTHENTICATED { -->
                    <li><a href="#notifications" title="Settings" >Notifications <span class="badge">5</span></a></li>
                    <li>
                        <a title="Settings" class="float-right" data-toggle="account-dropdown">{{Auth::user()->displayName}} <span class="fa fa-camera-retro" aria-hidden="true"></span></a>
                        <div class="dropdown-pane bottom"  id="account-dropdown" data-dropdown data-auto-focus="true">
                            <ul>
                            <li><a href="{{ URL::to('auth/signout') }}">{{ Lang::get('site.logout') }}</a></li>
                            <li><a href="{{ URL::to('users/' . Auth::user()->sid) }}">My Account</a></li>
                            <li><a href="{{ URL::to('auth/subaccount') }}">Sub accounts</a></li>
                            </ul>
                        </div>

                    </li>
                    <!-- } AUTHENTICATED -->
@else
                    <!-- NON-AUTHENTICATED { -->
                    <li><a href="/auth/signin"><span class="fa fa-caret-square-o-right" aria-hidden="true"></span> Sign In</a></li>
                    <li><a href="/auth/signup">Sign Up</a></li>
@endif
                    </ul>
                </div>
              </div>
            </div>

            @show
        </div> <!--  class="header" -->

        @section('site-head')
        @show

        <!-- The main body of the page -->
        <div class="body">
            <div class="row">
                <div class="large-12 columns">
@yield('content')
                </div>
            </div>
        </div>

        <!-- The footer of the page -->
        <div class="footer" >
            <div class="row ">
              <div class="large-12 columns">
                EcoLearnia 2016
                About | Contact Us | Social
              </div>
            </div>
        </div>

        <script>
        $(document).foundation();
        </script>
    </body>
</html>
