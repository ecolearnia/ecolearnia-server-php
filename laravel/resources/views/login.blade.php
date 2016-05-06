@extends('layouts.master')

@section('title', 'Login')

@section('top-bar')
@parent
<!-- Top Menu -->
@endsection

@section('content')
<br />
<div class="row">
<div class="medium-4 small-centered columns">
<div class="card">
    <div class="heading">
        <p>Login</p>
    </div>
    <div class="content">
        <form>
            <div class="row">
                <div class="large-12 columns">
                  <label>Username
                    <input type="text" name="username" placeholder="Username" />
                  </label>
                </div>
            </div>
            <div class="row">
                <div class="large-12 columns">
                  <label>Password
                    <input type="text"  name="password" placeholder="Password" />
                  </label>
                </div>
            </div>
      </form>
    </div>
    <div class="action">
        <button class="button">Sign In</button>
        <button class="button">Sign Up</button>
    </div>
</div>
</div>
</div>

<div class="row">
    <div class="medium-6 small-centered columns">
        <div class="subheader">
        Welcome to the Wonderful world of Eco Learnia
        Where learning math is FUN!
        </div>
    </div>
</div>


@endsection
