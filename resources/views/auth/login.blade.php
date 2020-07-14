@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Login') }}</div>

                <div class="card-body">
                    <form method="POST" action="{{ route('login') }}">
                        @csrf

                        <div class="form-group row">
                            <label for="email" class="col-md-4 col-form-label text-md-right">{{ __('E-Mail Address') }}</label>

                            <div class="col-md-6">
                                <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                                @if ($errors->has('email'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('email') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <label for="password" class="col-md-4 col-form-label text-md-right">{{ __('Password') }}</label>

                            <div class="col-md-6">
                                <input id="password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                                @if ($errors->has('password'))
                                    <span class="invalid-feedback" role="alert">
                                        <strong>{{ $errors->first('password') }}</strong>
                                    </span>
                                @endif
                            </div>
                        </div>

                        <div class="form-group row">
                            <div class="col-md-6 offset-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>

                                    <label class="form-check-label" for="remember">
                                        {{ __('Remember Me') }}
                                    </label>
                                </div>
                            </div>
                        </div>

                        <div class="form-group row mb-0">
                            <div class="col-md-8 offset-md-4">
                                <button type="submit" class="btn btn-primary">
                                    {{ __('Login') }}
                                </button>

                                @if (Route::has('password.request'))
                                    <a class="btn btn-link" href="{{ route('password.request') }}">
                                        {{ __('Forgot Your Password?') }}
                                    </a>
                                @endif
                            </div>
                            <div class="col-md-8 offset-md-4" style="margin-top: 1%">
                                <a class="btn btn-info" href="{{route('cas.login')}}">CAS Login</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card" style="margin-top: 3%">
                <div class="card-header">Demo login data</div>
                <p style="color: red;text-align: center">All data have been anonymized, but look real thanks to the <a href="https://github.com/fzaninotto/Faker" target="_blank">Faker</a> library.</p>
                <div class="card-body">
                    <table class="table">
                        <thead>
                        <tr>
                            <th scope="col">Role</th>
                            <th scope="col">Email</th>
                            <th scope="col">Password</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr>
                            <td>Organising Committee</td>
                            <td>oc@agm.com</td>
                            <td>abc123</td>
                        </tr>
                        <tr>
                            <td>Participant</td>
                            <td>participant@agm.com</td>
                            <td>abc123</td>
                        </tr>
                        <tr>
                            <td>Check-in</td>
                            <td>checkin@agm.com</td>
                            <td>abc123</td>
                        </tr>
                        <tr>
                            <td>Voting Devices</td>
                            <td>vd@agm.com</td>
                            <td>abc123</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
