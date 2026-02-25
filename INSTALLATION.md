# 🚀 Crudly Installation Guide

## Install from Packagist

```bash
composer require shahrakii/crudly:@dev
```

## Publish Configuration

```bash
php artisan vendor:publish --provider="Shahrakii\Crudly\CrudlyServiceProvider"
```

This creates:
- `config/crudly.php` - Configuration file
- `stubs/` - Customizable templates

## Link Storage

```bash
php artisan storage:link
```

## Quick Start

### 1. Create Migration
```bash
php artisan make:migration create_posts_table
```

Edit migration:
```php
Schema::create('posts', function (Blueprint $table) {
    $table->id();
    $table->string('title');
    $table->text('content');
    $table->string('featured_image')->nullable();
    $table->timestamps();
});
```

### 2. Run Migration
```bash
php artisan migrate
```

### 3. Generate CRUD
```bash
php artisan crudly:generate Post --routes
```

### 4. Start Server
```bash
php artisan serve
```

### 5. Visit
```
http://localhost:8000/posts
```

---

## Commands

### Generate Complete CRUD
```bash
php artisan crudly:generate Post --routes
```

### Generate Model Only
```bash
php artisan crudly:model Post
```

### Generate Controller Only
```bash
php artisan crudly:controller PostController Post
```

### Force Overwrite
```bash
php artisan crudly:generate Post --force --routes
```

---

## Features

✅ One-command CRUD generation  
✅ Dark theme UI (Tailwind CSS)  
✅ Smart schema detection  
✅ Auto validation rules  
✅ Image upload support  
✅ Customizable stubs  
✅ Professional design  
✅ Production-ready  

---

## Configuration

Edit `config/crudly.php` to customize:
- Route prefix
- Middleware
- Excluded columns
- Pagination
- And more...

---

## Documentation

See `README.md` for complete documentation.

---

**Happy generating!** 🎉
