<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/html">

@include('panel._head')

<body>

<div class="container">

    <div class="row" style="margin-top:25px;">
        <div class="col-md-3 col-md-offset-4 text-center">
            <a href="{{ route('home_website') }}"><img src="{{asset('images/logo.png')}}" alt="logo sexodome" /></a>
        </div>
    </div>

    <div class="row" style="margin-top:65px;">

        <div class="col-md-3 col-md-offset-4">
            <form method="POST" action="{{ route('login') }}">
                {{ csrf_field() }}
                <div>
                    Email
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                </div>

                <div>
                    Password
                    <input type="password" name="password" id="password" class="form-control">
                </div>

                <div>
                    <input type="checkbox" name="remember" class="form-control"> <div class="text-center" style="width:100%;">Remember Me</div>
                </div>

                <div>
                    <button type="submit" class="btn btn-primary" style="width:100%;margin-top:20px;">Login</button>
                    <a href="{{route('register')}}" class="btn btn-primary" style="width:100%;margin-top:20px;">create new account</a>
                    <a href="{{route('password.request')}}" class="btn btn-primary" style="width:100%;margin-top:10px;">forgot your password?</a>
                    <a href="http://{{\App\rZeBot\sexodomeKernel::getMainPlataformDomain()}}" class="btn btn-success" style="width:100%;margin-top:10px;">back to website</a>
                </div>
            </form>
        </div>
    </div>

</div>
</body>
</html>



