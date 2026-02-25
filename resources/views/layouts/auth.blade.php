<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin Login') — {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #1e293b 0%, #0f172a 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 1rem;
        }

        .card {
            background: #fff;
            border-radius: 1rem;
            box-shadow: 0 25px 60px rgba(0,0,0,.45);
            padding: 2.5rem;
            width: 100%;
            max-width: 420px;
        }

        .brand {
            text-align: center;
            margin-bottom: 2rem;
        }
        .brand-icon {
            width: 56px; height: 56px;
            background: linear-gradient(135deg,#6366f1,#8b5cf6);
            border-radius: .875rem;
            display: flex; align-items: center; justify-content: center;
            font-size: 1.5rem;
            margin: 0 auto .875rem;
        }
        .brand h1 { font-size: 1.375rem; font-weight: 700; color: #0f172a; }
        .brand p  { color: #64748b; font-size: .875rem; margin-top: .25rem; }

        .form-group { margin-bottom: 1.125rem; }

        label {
            display: block;
            font-size: .875rem;
            font-weight: 500;
            color: #374151;
            margin-bottom: .4rem;
        }

        input[type=email],
        input[type=password],
        input[type=text] {
            width: 100%;
            padding: .65rem .875rem;
            border: 1.5px solid #d1d5db;
            border-radius: .5rem;
            font-size: .9rem;
            font-family: inherit;
            outline: none;
            transition: border-color .15s, box-shadow .15s;
            color: #0f172a;
        }
        input:focus {
            border-color: #6366f1;
            box-shadow: 0 0 0 3px rgba(99,102,241,.15);
        }
        input.is-invalid { border-color: #ef4444; }

        .invalid-feedback {
            color: #ef4444;
            font-size: .8rem;
            margin-top: .3rem;
        }

        .btn {
            display: block;
            width: 100%;
            padding: .75rem;
            background: linear-gradient(135deg,#6366f1,#8b5cf6);
            color: #fff;
            border: none;
            border-radius: .5rem;
            font-size: .9375rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
            transition: opacity .15s, transform .1s;
            margin-top: 1.5rem;
        }
        .btn:hover   { opacity: .92; }
        .btn:active  { transform: scale(.99); }

        .form-footer {
            text-align: center;
            margin-top: 1.25rem;
            font-size: .875rem;
            color: #64748b;
        }
        .form-footer a { color: #6366f1; text-decoration: none; font-weight: 500; }
        .form-footer a:hover { text-decoration: underline; }

        .check-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: .25rem;
            font-size: .875rem;
        }
        .check-label { display: flex; align-items: center; gap: .375rem; color: #374151; }

        .alert {
            padding: .75rem 1rem;
            border-radius: .5rem;
            font-size: .875rem;
            margin-bottom: 1.25rem;
        }
        .alert-success { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
        .alert-error   { background: #fef2f2; color: #b91c1c; border: 1px solid #fecaca; }
    </style>
</head>
<body>
<div class="card">
    <div class="brand">
        <div class="brand-icon">⚡</div>
        <h1>{{ config('app.name') }}</h1>
        <p>@yield('subtitle', 'Admin Panel')</p>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    @yield('content')
</div>
</body>
</html>
