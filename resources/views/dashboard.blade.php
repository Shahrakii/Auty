<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard — {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: 'Inter', sans-serif; background: #f1f5f9; color: #0f172a; min-height: 100vh; }

        .topbar {
            background: #fff;
            border-bottom: 1px solid #e2e8f0;
            padding: 1rem 2rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        .topbar-brand { font-weight: 700; font-size: 1.125rem; color: #6366f1; }
        .topbar-user  { display: flex; align-items: center; gap: .75rem; font-size: .875rem; color: #64748b; }

        .content { max-width: 800px; margin: 3rem auto; padding: 0 1rem; text-align: center; }

        .card {
            background: #fff;
            border-radius: 1rem;
            border: 1px solid #e2e8f0;
            padding: 2.5rem;
            box-shadow: 0 1px 3px rgba(0,0,0,.06);
        }

        .badge {
            display: inline-block;
            background: #d1fae5;
            color: #065f46;
            padding: .3rem .875rem;
            border-radius: 99px;
            font-size: .8rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
        }

        h1 { font-size: 1.75rem; font-weight: 700; margin-bottom: .5rem; }
        p  { color: #64748b; font-size: .9375rem; line-height: 1.6; }

        .info-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 1rem;
            margin: 2rem 0;
            text-align: left;
        }
        .info-item {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: .625rem;
            padding: 1rem;
        }
        .info-label { font-size: .75rem; font-weight: 600; text-transform: uppercase; letter-spacing: .05em; color: #94a3b8; margin-bottom: .25rem; }
        .info-value { font-size: .9375rem; font-weight: 500; }

        .btn-logout {
            background: #fee2e2;
            color: #b91c1c;
            border: none;
            padding: .6rem 1.25rem;
            border-radius: .5rem;
            font-size: .875rem;
            font-weight: 600;
            font-family: inherit;
            cursor: pointer;
        }
        .btn-logout:hover { background: #fecaca; }
    </style>
</head>
<body>

<div class="topbar">
    <div class="topbar-brand">⚡ {{ config('app.name') }}</div>
    <div class="topbar-user">
        <span>{{ $admin->name }}</span>
        <form method="POST" action="{{ route('auty.logout') }}">
            @csrf
            <button type="submit" class="btn-logout">Logout</button>
        </form>
    </div>
</div>

<div class="content">
    <div class="card">
        <div class="badge">✅ Authentication working</div>
        <h1>Welcome, {{ $admin->name }}!</h1>
        <p>You are logged into the Auty admin panel. Login, logout, OTP, and password reset are all set up and ready.</p>

        <div class="info-grid">
            <div class="info-item">
                <div class="info-label">Email</div>
                <div class="info-value">{{ $admin->email }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Last Login</div>
                <div class="info-value">{{ $admin->last_login_at?->diffForHumans() ?? 'First login' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">Last Login IP</div>
                <div class="info-value">{{ $admin->last_login_ip ?? '—' }}</div>
            </div>
            <div class="info-item">
                <div class="info-label">OTP Status</div>
                <div class="info-value">{{ config('auty.otp.enabled') ? '🟢 Enabled' : '⚫ Disabled' }}</div>
            </div>
        </div>

        <p style="font-size:.8125rem;color:#94a3b8;">
            This is a test dashboard. Replace the <code>/admin/dashboard</code> route in your app with your own.
        </p>
    </div>
</div>

</body>
</html>
