# ⚡ Auty — Advanced Admin Auth & Authorization for Laravel

[![Laravel](https://img.shields.io/badge/Laravel-10%2B%20%7C%2011%2B-red?logo=laravel)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.1%2B-blue?logo=php)](https://php.net)
[![License](https://img.shields.io/badge/license-MIT-green)](LICENSE)

**Auty** is a production-ready, fully-featured admin authentication and authorization package for Laravel 10+. It ships with a completely separate guard, role/permission system, OTP, 2FA, impersonation, session management, activity logging, and a clean built-in UI — all in one package.

---

## ✨ Features at a Glance

| Feature | Details |
|---|---|
| **Separate Admin Guard** | Completely isolated from the default `user` guard |
| **Role System** | `super_admin` & `admin` roles with permission-based access control |
| **OTP Auth** | Email / SMS one-time codes with pluggable providers |
| **2FA (TOTP)** | Google Authenticator compatible via `pragmarx/google2fa` |
| **Impersonation** | Super admins can view-as any admin with full audit trail |
| **Session Management** | Per-admin session tracking, revocation, suspicious login detection |
| **Activity Logs** | Every action logged with IP, user agent, method, URL |
| **Brute-Force Protection** | Rate limiting + account lock after failed attempts |
| **Admin Panel UI** | Dashboard, admin CRUD, role/permission editor, logs viewer |
| **API Token Auth** | Laravel Sanctum-powered API token support |
| **Multi-Tenancy** | Optional tenant_id scoping |
| **Localization** | All strings translatable via lang files |
| **Events & Listeners** | Extensible via standard Laravel events |
| **Artisan Commands** | `auty:install`, `auty:create-admin`, `auty:assign-role` |

---

## 🚀 Installation

### 1. Require via Composer

```bash
composer require auty/auty
```

### 2. Run the installer

```bash
php artisan auty:install
```

This will:
- Publish config → `config/auty.php`
- Publish migrations, views, lang files
- Run migrations
- Seed default roles & permissions
- Create your first Super Admin interactively

---

## ⚙️ Configuration

After installation, customize `config/auty.php`:

```php
// config/auty.php

'prefix' => 'admin',          // URL prefix: /admin/...
'guard'  => 'admin',          // auth guard name

'throttle' => [
    'enabled'      => true,
    'max_attempts' => 5,
    'lock_account' => true,
    'lock_duration_minutes' => 30,
],

'two_factor' => [
    'enabled' => true,
    'enforce' => false,   // require ALL admins to use 2FA
],

'otp' => [
    'enabled'  => true,
    'channel'  => 'email',   // email | sms
    'provider' => \Auty\Services\Otp\EmailOtpProvider::class,
],

'sessions' => [
    'track'            => true,
    'max_per_admin'    => 5,
    'suspicious_login' => true,
],
```

---

## 📁 Package Structure

```
auty/
├── src/
│   ├── AutyServiceProvider.php           # Main service provider
│   ├── Console/Commands/
│   │   ├── InstallCommand.php            # php artisan auty:install
│   │   ├── CreateAdminCommand.php        # php artisan auty:create-admin
│   │   └── AssignRoleCommand.php         # php artisan auty:assign-role
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Auth/
│   │   │   │   ├── LoginController.php
│   │   │   │   ├── LogoutController.php
│   │   │   │   ├── ForgotPasswordController.php
│   │   │   │   ├── ResetPasswordController.php
│   │   │   │   ├── OtpController.php
│   │   │   │   └── TwoFactorController.php
│   │   │   ├── DashboardController.php
│   │   │   ├── AdminController.php
│   │   │   ├── ProfileController.php
│   │   │   ├── RoleController.php
│   │   │   ├── ActivityLogController.php
│   │   │   ├── SessionController.php
│   │   │   └── ImpersonationController.php
│   │   └── Middleware/
│   │       ├── AdminAuthenticate.php     # auty.auth
│   │       ├── AdminRole.php             # auty.role:super_admin,admin
│   │       ├── AdminPermission.php       # auty.permission:admins.view
│   │       ├── SuperAdmin.php            # auty.super
│   │       ├── OtpVerified.php           # auty.otp
│   │       └── TwoFactorVerified.php     # auty.2fa
│   ├── Models/
│   │   ├── Admin.php
│   │   ├── AdminRole.php
│   │   ├── AdminPermission.php
│   │   ├── AdminActivityLog.php
│   │   ├── AdminSession.php
│   │   └── AdminOtp.php
│   ├── Services/
│   │   ├── OtpService.php
│   │   ├── TwoFactorService.php
│   │   ├── ImpersonationService.php
│   │   ├── SessionService.php
│   │   ├── ActivityLogService.php
│   │   └── Otp/EmailOtpProvider.php
│   ├── Traits/
│   │   ├── HasRoles.php
│   │   ├── HasPermissions.php
│   │   ├── HasTwoFactor.php
│   │   ├── HasOtp.php
│   │   └── LogsActivity.php
│   ├── Events/
│   │   ├── AdminLoggedIn.php
│   │   ├── AdminLoggedOut.php
│   │   ├── AdminFailedLogin.php
│   │   ├── OtpRequested.php
│   │   ├── ImpersonationStarted.php
│   │   └── ImpersonationEnded.php
│   ├── Listeners/
│   │   ├── LogAdminLogin.php
│   │   ├── LogAdminLogout.php
│   │   ├── LogFailedLogin.php
│   │   ├── LogImpersonation.php
│   │   └── SendOtpNotification.php
│   ├── Policies/
│   │   └── AdminPolicy.php
│   └── Contracts/
│       └── OtpProvider.php
├── config/auty.php
├── database/migrations/
│   ├── ..._create_admins_table.php
│   ├── ..._create_admin_roles_table.php
│   ├── ..._create_admin_activity_logs_table.php
│   ├── ..._create_admin_sessions_table.php
│   └── ..._create_admin_otps_table.php
├── resources/
│   ├── views/
│   │   ├── layouts/{app,auth}.blade.php
│   │   ├── auth/{login,otp,two-factor,forgot-password,reset-password}.blade.php
│   │   ├── dashboard/index.blade.php
│   │   ├── admins/{index,create,edit}.blade.php
│   │   ├── roles/{index,create,edit}.blade.php
│   │   ├── logs/index.blade.php
│   │   ├── sessions/index.blade.php
│   │   └── profile/index.blade.php
│   └── lang/en/{auth,admin,role,profile,session,impersonation}.php
└── routes/{web.php,api.php}
```

---

## 🛡️ Guard Configuration

The package automatically configures a separate `admin` guard. You can inspect/override in `config/auth.php`:

```php
'guards' => [
    'admin' => [
        'driver'   => 'session',
        'provider' => 'admins',
    ],
],

'providers' => [
    'admins' => [
        'driver' => 'eloquent',
        'model'  => \Auty\Models\Admin::class,
    ],
],

'passwords' => [
    'admins' => [
        'provider' => 'admins',
        'table'    => 'admin_password_reset_tokens',
        'expire'   => 60,
    ],
],
```

---

## 🔑 Middleware Usage

All middleware are registered automatically:

```php
// Protect a route — admin must be authenticated
Route::middleware('auty.auth')->group(...);

// Role-based access
Route::middleware('auty.role:super_admin')->group(...);
Route::middleware('auty.role:admin,super_admin')->group(...);

// Permission-based access
Route::middleware('auty.permission:admins.view')->group(...);
Route::middleware('auty.permission:admins.edit,admins.create')->group(...);

// Super admin only
Route::middleware('auty.super')->group(...);

// Require OTP verification
Route::middleware('auty.otp')->group(...);

// Require 2FA verification
Route::middleware('auty.2fa')->group(...);
```

---

## 👥 Roles & Permissions

### Assigning roles

```php
// Via code
$admin->assignRole('admin');
$admin->assignRole('super_admin', 'admin');   // multiple
$admin->syncRoles(['admin']);
$admin->removeRole('admin');

// Via Artisan
php artisan auty:assign-role admin@example.com super_admin
```

### Checking roles

```php
$admin->hasRole('super_admin');
$admin->hasAnyRole(['admin', 'editor']);
$admin->hasAllRoles(['admin', 'editor']);
$admin->isSuperAdmin();   // shortcut
```

### Permissions

```php
// Give direct permission
$admin->givePermission('admins.create');

// Give to role
$role->givePermission('admins.view');

// Check
$admin->hasPermission('admins.delete');
$admin->hasAnyPermission(['admins.edit', 'admins.create']);

// Gate integration
Gate::allows('admins.view');
$admin->can('admins.view');
```

---

## 🔐 OTP Authentication Flow

```
1. Admin submits email/password → login succeeds
2. If config('auty.otp.enabled') is true:
   → OTP is generated and fired via OtpRequested event
   → SendOtpNotification listener delivers OTP to email/SMS
   → Admin is redirected to /admin/otp
3. Admin enters code → verified via OtpService::verify()
4. Session key `auty_otp_verified` is set
5. Subsequent requests pass through OtpVerified middleware
```

### Custom OTP Provider

```php
// app/Otp/SmsOtpProvider.php
use Auty\Contracts\OtpProvider;

class SmsOtpProvider implements OtpProvider
{
    public function send(Admin $admin, AdminOtp $otp): void
    {
        // Send SMS via Twilio, Vonage, etc.
        app(TwilioClient::class)->messages->create($admin->phone, [
            'from' => config('services.twilio.from'),
            'body' => "Your login code: {$otp->code}",
        ]);
    }
}

// config/auty.php
'otp' => [
    'provider' => \App\Otp\SmsOtpProvider::class,
    'channel'  => 'sms',
],
```

---

## 🕵️ Impersonation

Super admins can view the panel as any other admin:

```php
// Start impersonating
$impersonation = app(\Auty\Services\ImpersonationService::class);
$impersonation->impersonate($superAdmin, $targetAdmin);

// Stop
$impersonation->stopImpersonating();

// Check
$impersonation->isImpersonating();       // bool
$impersonation->getOriginalAdmin();      // Admin|null
```

**UI**: Click "View As" on the admins list. A yellow banner appears at the top of every page while impersonating. Full activity log is recorded.

---

## 📊 Database Schema

```sql
-- admins
id, name, email, password, phone, avatar,
is_active, is_locked, locked_until,
failed_login_count, last_login_at, last_login_ip,
two_factor_secret, two_factor_enabled,
email_verified_at, tenant_id (nullable),
remember_token, deleted_at, timestamps

-- admin_roles
id, name, label, description, tenant_id, timestamps

-- admin_permissions
id, name, label, group, description, timestamps

-- admin_role_permission (pivot)
role_id, permission_id

-- admin_role_assignments (pivot)
admin_id, role_id, timestamps

-- admin_direct_permissions (pivot)
admin_id, permission_id, timestamps

-- admin_activity_logs
id, admin_id, impersonated_by, event, description,
properties (json), ip_address, user_agent,
url, method, created_at

-- admin_sessions
id, admin_id, session_id, ip_address, user_agent,
device_type, device_name, browser, platform,
location, last_activity, is_current, payload (json), timestamps

-- admin_otps
id, admin_id, code, channel, used, attempts, expires_at, timestamps

-- admin_password_reset_tokens
email, token, created_at
```

---

## 📡 Events

Listen to Auty events in your `EventServiceProvider` or any event listener:

```php
use Auty\Events\AdminLoggedIn;
use Auty\Events\AdminLoggedOut;
use Auty\Events\AdminFailedLogin;
use Auty\Events\OtpRequested;
use Auty\Events\ImpersonationStarted;
use Auty\Events\ImpersonationEnded;

// Example listener
Event::listen(AdminLoggedIn::class, function (AdminLoggedIn $event) {
    logger("Admin {$event->admin->email} logged in from {$event->ip}");
});
```

---

## 🌐 Localization

Publish and edit the lang files:

```bash
php artisan vendor:publish --tag=auty-lang
```

Files appear in `lang/vendor/auty/{locale}/`. Supports any locale via:

```php
// config/auty.php
'locale' => 'ar',  // Arabic, French, etc.
```

---

## 🔒 Security Checklist

Auty ships with these protections enabled by default:

- [x] Separate authentication guard (no user/admin collision)
- [x] Rate limiting per email+IP combination
- [x] Account lock after N failed attempts (configurable)
- [x] Soft deletes on Admin model
- [x] Password hashed via `Hash::make()` with rehash detection
- [x] CSRF protection on all forms
- [x] Session regeneration after login
- [x] Suspicious login detection (IP change)
- [x] 2FA with TOTP (RFC 6238)
- [x] OTP with expiry & attempt limiting (max 3 attempts per OTP)
- [x] Impersonation restricted to `super_admin` role
- [x] Activity logging with impersonator tracking
- [x] IP whitelist/blacklist support
- [x] Session invalidation on logout

---

## 🧪 Running Tests

```bash
cd auty
composer install
vendor/bin/phpunit
```

---

## 🤝 Extending

### Custom Admin Model

```php
// config/auty.php
'models' => [
    'admin' => \App\Models\MyAdmin::class,
],

// App\Models\MyAdmin
class MyAdmin extends \Auty\Models\Admin
{
    protected $fillable = [
        ...parent::getFillable(),
        'department',
    ];
}
```

### Custom OTP Provider (SMS via Vonage)

```php
class VonageOtpProvider implements \Auty\Contracts\OtpProvider
{
    public function send(Admin $admin, AdminOtp $otp): void
    {
        // Vonage SMS logic
    }
}
```

---

## 📝 License

MIT © Auty Package
