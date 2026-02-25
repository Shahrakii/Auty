@extends('auty::layouts.auth')
@section('subtitle', 'Reset your password')

@section('content')
<p style="font-size:.875rem;color:#64748b;margin-bottom:1.25rem;text-align:center;">
    Enter your admin email and we'll send you a password reset link.
</p>

<form method="POST" action="{{ route('auty.password.email') }}">
    @csrf
    <div class="form-group">
        <label for="email">Email Address</label>
        <input type="email" id="email" name="email"
               value="{{ old('email') }}"
               placeholder="admin@example.com"
               autofocus
               class="{{ $errors->has('email') ? 'is-invalid' : '' }}">
        @error('email')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn">Send Reset Link</button>
</form>

<div class="form-footer">
    <a href="{{ route('auty.login') }}">← Back to login</a>
</div>
@endsection
