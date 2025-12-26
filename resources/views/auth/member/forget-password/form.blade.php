<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="shortcut icon" href="{{fileExist(['url'=>@$site_setting->favicon,'type'=>'favicon'])}}" type="image/x-icon">
    <link rel="icon" href="{{fileExist(['url'=>@$site_setting->favicon,'type'=>'favicon'])}}" type="image/x-icon">
    <title>{{(@$site_setting->title_suffix)?(@$site_setting->title_suffix):'Project Name'}} | {{ @$title ?? "" }} Login</title>
    <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.0/css/bootstrap.min.css" rel="stylesheet">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.1/jquery.min.js"></script>
    <style type="text/css">
        body, html {
            font-family: "Roboto", sans-serif;
        }
        .card-container.card {
            max-width: 350px;
            padding: 40px 40px;
        }
        .btn {
            font-weight: 700;
            height: 36px;
            -moz-user-select: none;
            -webkit-user-select: none;
            user-select: none;
            cursor: default;
        }
        .card {
            background-color: #f7f7f7d4;
            padding: 20px 25px 30px;
            margin: 0 auto 25px;
            margin-top: 50px;
            -moz-border-radius: 2px;
            -webkit-border-radius: 2px;
            border-radius: 2px;
            -moz-box-shadow: 15px 15px 27px rgb(0 0 0 / 50%);
            -webkit-box-shadow: 15px 15px 27px rgb(0 0 0 / 50%);
            box-shadow: 15px 15px 27px rgb(0 0 0 / 50%);
            -moz-box-shadow: 15px 15px 27px rgb(0 0 0 / 50%);
            -webkit-box-shadow: 15px 15px 27px rgb(0 0 0 / 50%);
            box-shadow: 15px 15px 27px rgb(0 0 0 / 50%);
        }
        .profile-img-card {
            width: 100px;
        }
        .profile-name-card {
            font-size: 16px;
            font-weight: bold;
            text-align: center;
            margin: 10px 0 0;
            min-height: 1em;
        }
        .form-signin input{
            direction: ltr;
            height: 44px;
            font-size: 16px;
        }
        .form-signin input,
        .form-signin button {
            width: 100%;
            display: block;
            margin-bottom: 10px;
            z-index: 1;
            position: relative;
            -moz-box-sizing: border-box;
            -webkit-box-sizing: border-box;
            box-sizing: border-box;
        }
        .form-signin .form-control:focus {
            border-color: rgb(104, 145, 162);
            outline: 0;
            -webkit-box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgb(104, 145, 162);
            box-shadow: inset 0 1px 1px rgba(0,0,0,.075),0 0 8px rgb(104, 145, 162);
        }
        .btn.btn-signin {
            cursor: pointer;
            font-weight: 700;
            font-size: 14px;
            height: 36px;
            -moz-border-radius: 3px;
            -webkit-border-radius: 3px;
            border-radius: 3px;
            border: none;
            -o-transition: all 0.218s;
            -moz-transition: all 0.218s;
            -webkit-transition: all 0.218s;
            transition: all 0.218s;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card card-container">
            <div class="text-center">
                <img src="{{fileExist(['url'=>@$site_setting->logo,'type'=>'logo'])}}" class="profile-img-card img-circle">
            </div>
            <p id="profile-name" class="profile-name-card">Reset Password</p>
            <br>
            <form class="form-signin" id="form-signin" action="{{ route('member.forget.password.post') }}" method="post">
                {{csrf_field()}}
                @if(session()->has('forget_password_success'))
                <div class="alert alert-success"><strong>{{session()->get('forget_password_success')}}</strong></div>
                @endif
                @if(session()->has('forget_password_error'))
                <div class="alert alert-danger"><strong>{{session()->get('forget_password_error')}}</strong></div>
                @endif
                @if($errors->has('email'))
                <div class="alert alert-danger"><strong>{{ $errors->first('email') }}</strong></div>
                @endif
                <div class="form-group {{$errors->has('email') ? 'has-error' : '' }}">
                    <input type="text" class="form-control" name="email" id="email" value="" placeholder="Enter Email">
                </div>
                <div class="row">
                    <div class="col-md-12 col-xs-12 col-sm-12 col-lg-12">
                        <button class="btn btn-lg btn-primary btn-block btn-signin" type="submit">Send Password Reset Link</button>
                    </div>
                </div>
            </form>
            <div class="row">
                <div class="form-group col-md-12 col-xs-12 col-sm-12 col-lg-12">
                    <a class="btn btn-lg btn-success btn-block btn-signin" href="{{url('/')}}">Home</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/jquery.validate.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-validate/1.19.5/additional-methods.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function(){       
            $('#form-signin').validate({
                ignore:[],
                errorClass:'text-danger',
                validClass:'text-success',
                errorPlacement: function(error, element){
                    error.insertAfter(element);
                },
                rules : {
                    'email' : {
                        required : true,
                        email:true
                    },
                },
                messages : {
                    'email' : {
                        required : 'The email address must be a required.',
                        email : 'The email must be a valid email address.',
                    }
                }
            });
        });
    </script>
</body>
</html>
