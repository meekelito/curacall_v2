@extends('layouts.auth')
@section('content')
          <!-- Advanced login -->
          <form method="POST" action="{{ route('password.email') }}">
            @csrf
            <div class="panel panel-body login-form"> 
              <div class="text-center">
                <img src="{{ asset('assets/images/curacall_logo.jpg') }}" width="100">
                <h5 class="content-group-lg">Login to your account <small class="display-block">Enter your Email Address</small></h5>
              </div>

              <div class="form-group has-feedback has-feedback-left">
                    <input id="email" placeholder="Email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ old('email') }}" required autofocus>

                              
                <div class="form-control-feedback">
                  <i class="icon-user text-muted"></i>
                </div>

                 @if (session('status'))
                   <span class="help-block text-success-400"><i class=" icon-checkmark4 position-left"></i>{{ session('status') }}</span>
                @endif

                 @if ($errors->has('email'))
                      <span class="help-block text-warning-400"><i class="icon-cancel-circle2 position-left"></i>{{ $errors->first('email') }}</span>
                 @endif
              </div>
                            


              <div class="form-group">
                  <button type="submit" class="btn bg-blue btn-block">
                    {{ __('Send Password Reset Link') }}
                </button>
              </div>

               <div class="form-group">
                <a href="{{ route('login') }}" class="btn bg-default btn-block">
                    Back to login
                </a>
              </div>
            </div>
          </form>
          <!-- /advanced login -->
@endsection