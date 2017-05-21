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
            <form method="POST" action="/password/email">
                {!! csrf_field() !!}

                @if (count($errors) > 0)
                    <ul style="color:red;">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                @endif

                <div>
                    Email
                    <input type="email" name="email" value="{{ old('email') }}" class="form-control">
                </div>

                <div>
                    <button type="submit" class="btn btn-primary" style="margin-top:20px;width:100%;">
                        Send Password Reset Link
                    </button>
                    <a href="{{route('login')}}" class="btn btn-primary" style="width:100%;margin-top:20px;">back to login</a>
                    <a href="http://{{\App\rZeBot\sexodomeKernel::getMainPlataformDomain()}}" class="btn btn-success" style="width:100%;margin-top:10px;">back to website</a>
                </div>
            </form>

        </div>
    </div>

</div>
</body>
</html>