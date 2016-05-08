@extends('layouts.master')

@section('title', 'Profile')

@section('top-bar')
@parent
<!-- Top Menu -->
    @include('_partials.topmenu')
@endsection

@section('content')


        <!-- Body: top-pane -->
        <div class="row" >
            <div class="large-12 columns">
                <!-- breadcrumbs. @todo - Convert into React component  -->
                <nav aria-label="You are here:" role="navigation">
                  <ul class="breadcrumbs">
                    <li><a href="#">Home</a></li>
                    <li><a href="#">Assignment</a></li>
                    <li>Nine Numbers</li>
                    <li>
                      <span class="show-for-sr">Current: </span> Question 1
                    </li>
                  </ul>
                </nav>
            </div>
        </div>

        <!-- Body: content-pane -->
        <div class="row">
            <div class="medium-3 columns">
                @include('_partials.userbadge')
            </div>
            <div class="medium-9 columns">
                <!-- Dasboard content -->
                <!-- UNFINISHED ASSIGNMENTS -->
                

                <!-- BADGES -->

                <!-- ITEMS -->
            </div>
        </div>

@endsection
