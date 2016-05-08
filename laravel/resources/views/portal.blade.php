@extends('layouts.master')

@section('title', 'Main')

@section('top-bar')
@parent
<!-- Top Menu -->
@endsection

@section('site-head')
<div class="site-opening">
    <div class="row">
        <div class="large-6 large-centered columns">
            EcoLearnia! Have fun.
        </div>
    </div>
</div>
@endsection

@section('content')

<ul>
@foreach ($assignments as $assignable)
    <li><a href="/lms/assignment?outsetNode={{ $assignable->uuid }}">{{ $assignable->meta_title }}</a></li>
@endforeach
</ul>


@endsection
