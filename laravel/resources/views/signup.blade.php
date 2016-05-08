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
<div class="card" style="width:500px; margin: 0 auto;">
    <div class="heading">
        <p>Sign Up</p>
    </div>
    <div class="content">

        <form id="SignUpForm" >
            <div class="row">
                <div class="medium-6 columns">
                    <label>First Name
                    <input name="givenName" type="text" placeholder="First Name">
                    </label>
                </div>
                <div class="medium-6 columns">
                    <label>Last Name
                    <input name="faimlyName" type="text" placeholder="Last Name">
                    </label>
                </div>
            </div>

            <div class="row">
                <div class="medium-12 columns">
                    <label>Email
                    <input name="email" type="email" placeholder="Email">
                    </label>
                </div>
            </div>

            <div class="row">
                <div class="medium-12 columns">
                    <label>Password
                    <input name="password" type="password" placeholder="Password">
                    </label>
                </div>
            </div>

            <label>
                Birthday
                <div class="row">
                    <div class="medium-4 columns">
                        <label>Month
                            <select id="dob_month" name="dob_month">
                            </select>
                        </label>
                    </div>
                    <div class="medium-4 columns">
                        <label>Day
                            <select id="dob_day" name="dob_day">
                            </select>
                        </label>
                    </div>
                    <div class="medium-4 columns">
                        <label>Year
                            <select id="dob_year" name="dob_year">
                            </select>
                        </label>
                    </div>
                </div>
            </label>

            <div class="row">
                <div class="medium-12 columns">
                    <input type="radio" name="gender" value="F" required><label for="genderFemale">Female</label>
                    <input type="radio" name="gender" value="M" ><label for="genderMale">Male</label>
                </div>
            </div>
      </form>

    </div>
    <div class="action" style="text-align: center">
        <button class="button" id="submit" >Sign up</button> &nbsp;
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

<script type="text/javascript" src="{{ asset('js/eli-externdeps.bundle.min.js') }}"></script>
<script type="text/javascript" src="{{ asset('js/eli-services.bundle.min.js') }}" type="text/javascript" ></script>

<script type="text/javascript">

$(function() {
    var months = [ 'Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec' ];
    var selectMonth = $('#dob_month');
    for(i=1; i <= 12 ; i++) {
        selectMonth.append($("<option></option>")
                    .attr("value", i)
                    .text( months[i-1] ));
    }
    var selectMonth = $('#dob_day');
    for(i=1; i <= 31 ; i++) {
        selectMonth.append($("<option></option>")
                    .attr("value", i)
                    .text(i));
    }
    var selectYear = $('#dob_year');
    var year = (new Date()).getFullYear();
    for(i=year; i > year - 80 ; i--) {
        selectYear.append($("<option></option>")
                    .attr("value", i)
                    .text(i));
    }
    $('#submit').click(function( event ) {
        var formData = captureForm();
        //alert( "Handler for .submit() called." + JSON.stringify(formData) );
        //$('#SignUpForm').submit();
        submitForm();
        event.preventDefault();
    });


});
var service = require('service');
function captureForm() {
    var formData = {};
    formData.primaryEmails = $('#SignUpForm [name="email"]').val();
    var dob = {};
    dob.year = $('#SignUpForm [name="dob_year"]').val();
    dob.month = $('#SignUpForm [name="dob_month"]').val();
    dob.day = $('#SignUpForm [name="dob_day"]').val();
    formData.profile = {
        dob: new Date(dob.year, dob.month, dob.day)
    }
    formData.auth = {
        username: $('#SignUpForm [name="email"]').val()
    }
    return formData;
}

function submitForm() {
    var formData = captureForm();
    var authSvc = new service.AuthService({baseUrl: '/api'});
    authSvc.signup(formData)
    .then(function(account) {
        if (account) {
            window.location = "/portal";
        } else {
            self.errorMessage = 'Invalid username or password';
        }
    })
    .catch(function(error) {
        if (error instanceof Error) {
            self.errorMessage = error.toString();
        }
        self.errorMessage = JSON.stringify(error, null, 2);
    });
}

</script>

@endsection
