@extends('layouts.master')

@section('title', 'Login')

@section('top-bar')
@parent
<!-- Top Menu -->
<div class="top-bar secondary">
  <div id="responsive-menu">
    <div class="top-bar-left">
        <div class="menu">
            <button type="button" class="button">HOME</button>
            <button type="button" class="button">SAMPLE PRACTICE</button>
        </div>
    </div>
  </div>
</div>
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

                <div class="card">
                    <img src="/images/Avatar_Penguin-512.png" />
                    <div class="heading">
                      Jane Doe
                    </div>
                    <div class="content">
                      <p>Score 432</p>
                    </div>
                </div>

            </div>
            <div class="medium-9 columns">
                <div>
                    <select id="nodeSelector"></select>
                </div>
                <button id="nextButton" type="button" class="success button" onClick="navigateNext()">Next</button>
                <div class="eli-assignment-placeholder" id="placeholder-scoreboard" ></div>
            </div>
        </div>

@endsection
