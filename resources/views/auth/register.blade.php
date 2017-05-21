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

    <div class="col-md-3 col-md-offset-4">
        @if (count($errors) > 0)
            <ul style="color:red;">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        @endif
    </div>

    <div class="clearfix"></div>

    <div class="col-md-3 col-md-offset-4" style="margin-top:50px;">
        <form method="POST" action="{{route('register')}}">
            {!! csrf_field() !!}

            <div>
                Name
                <input type="text" name="name" value="{{ old('name') }}" class="form-control">
            </div>

            <div>
                Email
                <input type="email" name="email" value="{{ old('email') }}" class="form-control">
            </div>

            <div>
                Password
                <input type="password" name="password" class="form-control">
            </div>

            <div>
                Confirm Password
                <input type="password" name="password_confirmation" class="form-control">
            </div>

            <div>
                <button type="submit" class="btn btn-primary" style="width:100%;margin-top:20px;">register</button>

                <a href="{{route('login')}}" class="btn btn-primary" style="width:100%;margin-top:20px;">back to login</a>
                <a href="http://{{\App\rZeBot\sexodomeKernel::getMainPlataformDomain()}}" class="btn btn-success" style="width:100%;margin-top:10px;">back to website</a>
            </div>
        </form>
    </div>

</div>
</body>
</html>