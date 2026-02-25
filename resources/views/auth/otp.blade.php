@extends('auty::layouts.auth')
@section('subtitle', 'Enter the code sent to your email')

@section('content')
<form method="POST" action="{{ route('auty.otp.verify') }}">
    @csrf
    <div class="form-group">
        <label for="code">One-Time Code</label>
        <input type="text" id="code" name="code"
               placeholder="123456"
               maxlength="{{ config('auty.otp.length', 6) }}"
               autocomplete="one-time-code" autofocus
               style="letter-spacing:.25rem; font-size:1.25rem; text-align:center;"
               class="{{ $errors->has('code') ? 'is-invalid' : '' }}">
        @error('code')
            <div class="invalid-feedback">{{ $message }}</div>
        @enderror
    </div>

    <button type="submit" class="btn">Verify Code</button>
</form>

<div class="form-footer" style="margin-top:1rem;">
    Didn't receive it?
    <form method="POST" action="{{ route('auty.otp.resend') }}" style="display:inline">
        @csrf
        <button type="submit" style="background:none;border:none;color:#6366f1;font-weight:500;cursor:pointer;font-size:.875rem;">
            Resend code
        </button>
    </form>
</div>
@endsection
