@extends('auty::layouts.auth')
@section('subtitle', 'Sign in to your account')

@section('content')
<form method="POST" action="{{ route('auty.login.post') }}">
    @csrf

    <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email"
               value="{{ old('email') }}"
               placeholder="admin@example.com"
               autocomplete="email" autofocus
               class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="form-group">
        <label for="password">Password</label>
        <input type="password" id="password" name="password"
               placeholder="••••••••"
               autocomplete="current-password"
               class="{{ $errors->has('password') ? 'is-invalid' : '' }}">
        @error('password')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <div class="check-row" style="margin-top:.25rem">
        <label class="check-label">
            <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}>
            Remember me
        </label>
        <a href="{{ route('auty.password.request') }}">Forgot password?</a>
    </div>

    <button type="submit" class="btn">Sign In</button>
</form>
@endsection
