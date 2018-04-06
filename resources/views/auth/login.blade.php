@extends('layouts.app')

@section('content')

<div class="container">
    <div class="card card-login mx-auto mt-5">
      <div class="card-header">Login</div>
      <div class="card-body">
        <form method="POST" action="{{ route('login') }}">
            @csrf
          <div class="form-group">
            <label for="email">{{ __('E-Mail Address') }}</label>
            <input class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" id="email" name="email" type="email" aria-describedby="emailHelp" placeholder="Enter email" value="{{ old('email') }}" required autofocus>
            @if ($errors->has('email'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('email') }}</strong>
                </span>
            @endif

          </div>
          <div class="form-group">
            <label for="password">{{ __('Password') }}</label>
            <input class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="password" name="password" type="password" placeholder="Password" required>
            @if ($errors->has('email'))
                <span class="invalid-feedback">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
          </div>
          <div class="form-group">
            <div class="form-check">
              <label class="form-check-label">
                <input class="form-check-input" type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> {{ __('Remember Me') }}
            </label>
            </div>
          </div>
            <button type="submit" class="btn btn-primary btn-block">
                {{ __('Login') }}
            </button>
        </form>
        <div class="text-center">
          <a class="d-block small mt-3" href="{{ route('register') }}"> Register an Account</a>
          <a class="d-block small" href="{{ route('password.request') }}">Forgot Password?</a>
        </div>
      </div>
    </div>
  </div>
@endsection
