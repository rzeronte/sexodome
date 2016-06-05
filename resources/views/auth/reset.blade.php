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
            <form method="POST" action="/password/reset">
                {!! csrf_field() !!}
                <input type="hidden" name="token" value="{{ $token }}">

                @if (count($errors) > 0)
                    <ul>
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
                    Password
                    <input type="password" name="password" class="form-control">
                </div>

                <div>
                    Confirm Password
                    <input type="password" name="password_confirmation" class="form-control">
                </div>

                <div>
                    <button type="submit">
                        Reset Password
                    </button>
                </div>
            </form>
        </div>
    </div>

</div>
</body>
</html>




