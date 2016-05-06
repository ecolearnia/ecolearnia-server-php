<html>
    <head>
        <title>EcoLearnia - @yield('title')</title>


        <link rel="stylesheet" href="{{ asset('css/app.css') }}">

        <script src="https://code.jquery.com/jquery-2.1.4.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/modernizr/2.8.3/modernizr.min.js"></script>
        <script type="text/javascript" src="{{ asset('js/deps.bundle.min.js') }}"></script>
        <script type="text/javascript" src="{{ asset('js/main.bundle.js') }}"></script>
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
                <strong>EcoLearnia - Dashboard</strong>
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
                    <li>Jane Doe</li>
                    <li><img src="Avatar_Penguin-512.png" width="40" /></li>
                  </ul>
                </div>
              </div>
            </div>

            @show
        </div> <!--  class="header" -->

        @section('site-head')
        @show

        <div class="body">
            <div class="row">
                <div class="large-12 columns">
@yield('content')
                </div>
            </div>
        </div>

        <div class="footer" >
            <div class="row ">
              <div class="large-12 columns">
                EcoLearnia 2016
                About | Contact Us | Social
              </div>
            </div>
        </div>

    </body>
</html>
