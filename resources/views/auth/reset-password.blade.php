@extends('auty::layouts.auth')
@section('subtitle', 'Choose a new password')

@section('content')
<form method="POST" action="{{ route('auty.password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">

    <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email"
               value="{{ old('email', $email) }}"
               class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="password">New Password</label>
        <input type="password" id="password" name="password"
               placeholder="Min. 8 characters"
               class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="password_confirmation">Confirm New Password</label>
        <input type="password" id="password_confirmation"
               name="password_confirmation" placeholder="Repeat password">
    </div>

    <button type="submit" class="btn">Reset Password</button>
</form>
@endsection
