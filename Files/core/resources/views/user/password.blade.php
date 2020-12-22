@extends('layout.profilemaster')

@section('title', 'Change Passowrd')

@section('headertxt', 'Change Passowrd')

@section('content')

    <h3>Change Password</h3>
    <hr>
    <form method="post" action="{{route('user.updatePassword')}}">
      {{csrf_field()}}
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="">Old Password:</label>
            <input type="password" name="old_password" value="" class="form-control" placeholder="Old Password">
            @if ($errors->has('old_password'))
                <span style="color:red;">
                    <strong>{{ $errors->first('old_password') }}</strong>
                </span>
            @else
                @if ($errors->first('oldPassMatch'))
                    <span style="color:red;">
                        <strong>Old password doesn't match with the existing password</strong>
                    </span>
                @endif
            @endif
          </div>
        </div>
        <div class="col-md-6">
          <div class="form-group">
            <label for="">New Password:</label>
            <input type="password" name="password" value="" class="form-control" placeholder="New Password">
            @if ($errors->has('password'))
                <span style="color:red;">
                    <strong>{{ $errors->first('password') }}</strong>
                </span>
            @endif
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-6">
          <div class="form-group">
            <label for="">Confirm Password:</label>
            <input type="password" name="password_confirmation" value="" class="form-control" placeholder="Confirm Password">
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-md-12 text-center">
          <button type="submit" class="btn base-bg white-txt">Update Password</button>
        </div>
      </div>
    </form>
@endsection
