@extends('layouts.auth')
@section('content')
          <!-- Advanced login -->
          <form method="POST" action="{{ route('password.update') }}">
            @csrf
            <input type="hidden" name="token" value="{{ $token }}">
            <div class="panel panel-body login-form"> 
              <div class="text-center">
                <img src="{{ asset('assets/images/curacall_logo.jpg') }}" width="100">
                <h5 class="content-group-lg">Login to your account <small class="display-block">Enter your Username / Email</small></h5>
              </div>

              <div class="form-group has-feedback has-feedback-left">
                   <input id="email" type="email" class="form-control{{ $errors->has('email') ? ' is-invalid' : '' }}" name="email" value="{{ $email ?? old('email') }}" placeholder="Email" required autofocus>

                              
                <div class="form-control-feedback">
                  <i class="icon-user text-muted"></i>
                </div>

              </div>

                <div class="form-group has-feedback has-feedback-left">
                     <input id="password" placeholder="Password" type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" name="password" required>

                              
                <div class="form-control-feedback">
                  <i class="icon-user text-muted"></i>
                </div>

              </div>

                <div class="form-group has-feedback has-feedback-left">
                     <input id="password-confirm" placeholder="Confirm Password" type="password" class="form-control" name="password_confirmation" required>

                <div class="form-control-feedback">
                  <i class="icon-user text-muted"></i>
                </div>

                @if ($errors->any())
                      <span class="help-block text-warning-400"><i class="icon-cancel-circle2 position-left"></i>{{ $errors->first() }}</span>
                @endif
                
                @if (session('status'))
                   <span class="help-block text-success-400"><i class=" icon-checkmark4 position-left"></i>{{ session('status') }}</span>
                @endif
              </div>
                            


              <div class="form-group">
                <button type="submit" class="btn bg-blue btn-block">
                    Change Password
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

                 