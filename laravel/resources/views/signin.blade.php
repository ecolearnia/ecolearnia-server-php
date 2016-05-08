@extends('layouts.master')

@section('title', 'Login')

@section('top-bar')
@parent
<!-- Top Menu -->
@endsection

@section('content')
<br />
<div class="row">
<div class="medium-12 columns" >
<div class="card" style="width:320px; margin: 0 auto;">
    <div class="heading">
        <p>Login</p>
    </div>
    <div class="content">
        <div style="text-align:center">Using</div>
        <div>
            <button class="button" style="background-color: #DC4E41;"
                onclick="location.href='/auth/google?redir_url=/portal';">Google +</button>
            <button class="button" style="background-color: #3b5998; color:#fff"
                onclick="location.href='/auth/facebook?redir_url=/portal';">Facebook</button>
            <button class="button" style="background-color: #0077b5; color:#fff"
                onclick="location.href='/auth/linkedin?redir_url=/portal';">LinkedIn</button>
        </div>
        <form>
        <div style="text-align:center">Or</div>
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
    <div class="action" style="text-align: center">
        Forgot Your Password?
        <button class="button">Sign In</button> &nbsp;
        <div style="text-align:center">Don't have an account?
            <a href="/auth/signup">Sign Up</a>, it's free!</div>
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
