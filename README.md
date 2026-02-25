# 📋 Crudly - Laravel CRUD Generator

**Crudly** is a powerful Laravel package that automatically generates complete CRUD (Create, Read, Update, Delete) operations with beautiful dark-themed views, custom models, and controllers. Built with Laravel 12+ compatibility and Tailwind CSS.

---

## ✨ Features

✅ One-Command CRUD Generation  
✅ Dark Theme UI with Tailwind CSS  
✅ Smart Schema Detection  
✅ Implicit Model Binding  
✅ Auto Validation Rules  
✅ Customizable Stubs  
✅ Responsive Tables  
✅ Built-in Column Filtering  
✅ Smart Form Generation  
✅ Auto Routes Registration  
✅ Professional Admin Layout  

---

## 📦 Requirements

- PHP 8.2+
- Laravel 12.0+
- Composer
- Tailwind CSS

---

## 🚀 Installation

### Step 1: Install Package

```bash
composer require shahrakii/crudly:@dev
```

### Step 2: Publish Configuration

```bash
php artisan vendor:publish --provider="Shahrakii\Crudly\CrudlyServiceProvider"
```

This creates `config/crudly.php`

### Step 3: Verify Installation

```bash
php artisan list crudly
```

You should see:
```
crudly:generate     Generate complete CRUD operations for a model
crudly:model        Generate a model with fillable properties
crudly:controller   Generate a CRUD controller for a model
```

---

## ⚙️ Configuration

Edit `config/crudly.php`:

```php
return [
    'route_prefix' => env('CRUDLY_ROUTE_PREFIX', 'crudly'),
    
    'middleware' => env('CRUDLY_MIDDLEWARE', ['web']),
    
    'global_filters' => [
        'id',
        'created_at',
        'updated_at',
        'deleted_at',
    ],
    
    'table_filters' => [],
    
    'exclude_tables' => [
        'migrations',
        'failed_jobs',
        'password_resets',
        'password_reset_tokens',
        'personal_access_tokens',
        'sessions',
    ],
    
    'pagination' => env('CRUDLY_PAGINATION', 15),
    
    'css_framework' => env('CRUDLY_CSS_FRAMEWORK', 'tailwind'),
];
```

---

## 📖 Quick Start (5 Minutes)

### Step 1: Create Database Table

```bash
php artisan make:migration create_posts_table
```

Edit `database/migrations/YYYY_MM_DD_HHMMSS_create_posts_table.php`:

```php
public function up(): void
{
    Schema::create('posts', function (Blueprint $table) {
        $table->id();
        $table->string('title');
        $table->text('content');
        $table->string('slug')->unique();
        $table->timestamps();
    });
}
```

### Step 2: Run Migration

```bash
php artisan migrate
```

### Step 3: Generate CRUD

```bash
php artisan crudly:generate Post --routes
```

### Step 4: Start Server

```bash
php artisan serve
```

### Step 5: Visit Your App

Open browser: **http://localhost:8000/posts**

You now have:
- ✅ List page with table
- ✅ Create form
- ✅ Edit form
- ✅ View details
- ✅ Delete functionality

---

## 🔨 Commands Reference

### Generate Complete CRUD

```bash
php artisan crudly:generate Post --routes
```

**What it generates:**
- Model: `app/Models/Post.php`
- Controller: `app/Http/Controllers/PostController.php`
- Views: `resources/views/posts/index.blade.php`, `create.blade.php`, `edit.blade.php`, `show.blade.php`
- Routes: Added to `routes/web.php`

**Options:**

```bash
# Force overwrite existing files
php artisan crudly:generate Post --force --routes

# Specify custom table name
php artisan crudly:generate Article --table=blog_posts --routes

# Generate without routes (add manually later)
php artisan crudly:generate Post
```

### Generate Only Model

```bash
php artisan crudly:model Post
```

**Output:** `app/Models/Post.php`

**With force:**
```bash
php artisan crudly:model Post --force
```

### Generate Only Controller

```bash
php artisan crudly:controller PostController Post
```

**Output:** `app/Http/Controllers/PostController.php`

**With force:**
```bash
php artisan crudly:controller PostController Post --force
```

---

## 📁 Generated File Structure

```
your-project/
├── app/
│   ├── Models/
│   │   └── Post.php
│   └── Http/Controllers/
│       └── PostController.php
├── resources/
│   └── views/
│       ├── layouts/
│       │   └── app.blade.php
│       └── posts/
│           ├── index.blade.php
│           ├── create.blade.php
│           ├── edit.blade.php
│           └── show.blade.php
├── config/
│   └── crudly.php
├── routes/
│   └── web.php
└── database/
    └── migrations/
        └── 2026_02_16_create_posts_table.php
```

---

## 🎨 Complete Workflow Examples

### Example 1: Create Products CRUD

```bash
# Step 1: Create migration
php artisan make:migration create_products_table

# Step 2: Edit migration file
# Add columns: id, name, price, description, timestamps

# Step 3: Run migration
php artisan migrate

# Step 4: Generate CRUD
php artisan crudly:generate Product --routes

# Step 5: Start server
php artisan serve

# Step 6: Visit
# http://localhost:8000/products
```

### Example 2: Create Categories CRUD

```bash
php artisan make:migration create_categories_table
# Edit: id, name, slug, description, timestamps
php artisan migrate
php artisan crudly:generate Category --routes
php artisan serve
# Visit: http://localhost:8000/categories
```

### Example 3: Create Users CRUD

```bash
php artisan crudly:generate User --routes
php artisan serve
# Visit: http://localhost:8000/users
```

### Example 4: Multiple CRUDs

```bash
# Create all tables first
php artisan make:migration create_posts_table
php artisan make:migration create_categories_table
php artisan make:migration create_tags_table

# Edit all migration files with columns

# Run all migrations
php artisan migrate

# Generate all CRUDs
php artisan crudly:generate Post --routes
php artisan crudly:generate Category --routes
php artisan crudly:generate Tag --routes

# Start server
php artisan serve

# Visit:
# http://localhost:8000/posts
# http://localhost:8000/categories
# http://localhost:8000/tags
```

---

## 🌙 Dark Theme Layout

The package generates a professional dark-themed layout.

### File Location

```
resources/views/layouts/app.blade.php
```

### Features

- Dark gray background (bg-gray-900)
- Blue accent colors
- Responsive sidebar
- Beautiful tables
- Smooth transitions
- Mobile-friendly

### Customize Colors

Edit `resources/views/layouts/app.blade.php`:

```blade
<!-- Change sidebar gradient -->
<div class="from-gray-900 via-gray-800 to-gray-900">
    <!-- Change to your colors -->
</div>

<!-- Change accent color -->
<div class="from-blue-600 to-blue-700">
    <!-- Change to: purple, green, red, etc -->
</div>
```

---

## 🔧 Customization Guide

### Edit Stubs (Templates)

Location:
```
vendor/shahrakii/crudly/resources/stubs/
```

Available stubs:

```
vendor/shahrakii/crudly/resources/stubs/
├── model/
│   └── model.stub
├── controller/
│   └── controller.stub
└── views/
    ├── index.stub
    ├── create.stub
    ├── edit.stub
    ├── show.stub
    ├── form-field.stub
    └── display-field.stub
```

### Template Placeholders

| Placeholder | Example | Use |
|---|---|---|
| `{{ MODEL }}` | Post | Model class name |
| `{{ MODEL_LOWER }}` | post | Camel case |
| `{{ MODEL_PLURAL }}` | Posts | Plural |
| `{{ MODEL_PLURAL_SNAKE }}` | posts | Snake case |
| `{{ TABLE }}` | posts | Database table |
| `{{ FILLABLE }}` | ['title', 'content'] | Fillable array |
| `{{ RULES }}` | [...] | Validation rules |
| `{{ COLUMN_HEADERS }}` | <th>Title</th> | Table headers |
| `{{ COLUMN_DATA }}` | <td>{{ data }}</td> | Table data |
| `{{ FORM_FIELDS }}` | Input fields | Form inputs |
| `{{ DISPLAY_FIELDS }}` | Display blocks | Show page |

### Example: Customize Model Stub

Edit `vendor/shahrakii/crudly/resources/stubs/model/model.stub`:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class {{ MODEL }} extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = '{{ TABLE }}';

    protected $fillable = {{ FILLABLE }};

    protected $dates = ['deleted_at'];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
```

---

## 📊 Generated Code Examples

### Generated Model

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $table = 'posts';

    protected $fillable = array (
      0 => 'title',
      1 => 'content',
      2 => 'slug',
    );

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}
```

### Generated Controller

```php
<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::paginate(15);
        return view('posts.index', ['posts' => $posts]);
    }

    public function create()
    {
        return view('posts.create', ['columns' => $this->getColumns()]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string',
            'slug' => 'required|string|max:255',
        ]);

        Post::create($validated);
        return redirect()->route('posts.index')
            ->with('success', 'Post created successfully!');
    }

    public function show(Post $post)
    {
        return view('posts.show', [
            'post' => $post,
            'columns' => $this->getColumns()
        ]);
    }

    public function edit(Post $post)
    {
        return view('posts.edit', [
            'post' => $post,
            'columns' => $this->getColumns()
        ]);
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([...]);
        $post->update($validated);

        return redirect()->route('posts.index')
            ->with('success', 'Post updated successfully!');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('posts.index')
            ->with('success', 'Post deleted successfully!');
    }
}
```

### Generated Routes

```php
Route::resource('posts', App\Http\Controllers\PostController::class);
```

Creates these routes:

| Method | Route | Controller Method |
|---|---|---|
| GET | `/posts` | index() |
| GET | `/posts/create` | create() |
| POST | `/posts` | store() |
| GET | `/posts/{id}` | show() |
| GET | `/posts/{id}/edit` | edit() |
| PUT | `/posts/{id}` | update() |
| DELETE | `/posts/{id}` | destroy() |

---

## 🐛 Troubleshooting & Fixes

### Routes Not Showing

**Error:** 404 Not Found when visiting `/posts`

**Solution:**

```bash
# Clear all caches
php artisan cache:clear
php artisan route:clear
php artisan view:clear

# Refresh autoload
composer dump-autoload

# Verify routes
php artisan route:list | grep posts
```

### Views Not Found

**Error:** View [posts.index] not found

**Solution:**

```bash
# Check views exist
ls resources/views/posts/

# Clear view cache
php artisan view:clear

# Verify layout file
cat resources/views/layouts/app.blade.php
```

### Model Not Found

**Error:** Class 'App\Models\Post' not found

**Solution:**

```bash
# Refresh autoload
composer dump-autoload

# Verify model file
cat app/Models/Post.php

# Check namespace is: namespace App\Models;
```

### Table Doesn't Exist

**Error:** SQLSTATE[42S02]: Table 'posts' doesn't exist

**Solution:**

```bash
# Check migrations exist
ls database/migrations/ | grep posts

# Run migrations
php artisan migrate

# Verify table
php artisan tinker
>>> Schema::getTables();
```

### Validation Errors

**Error:** Fields are not validating

**Solution:**

```bash
# Check fillable in model
cat app/Models/Post.php | grep fillable

# Verify form field names match
cat resources/views/posts/create.blade.php

# Check controller validation
cat app/Http/Controllers/PostController.php | grep validate
```

### Auth Errors

**Error:** Route [login] not defined

**Solution:**

```bash
# Check config/crudly.php middleware
cat config/crudly.php | grep middleware

# Change from ['web', 'auth'] to ['web']
# Edit config/crudly.php
```

### Service Provider Not Found

**Error:** Class 'Shahrakii\Crudly\CrudlyServiceProvider' not found

**Solution:**

```bash
# Reinstall package
composer remove shahrakii/crudly
composer require shahrakii/crudly:@dev

# Publish files
php artisan vendor:publish --provider="Shahrakii\Crudly\CrudlyServiceProvider"

# Clear cache
php artisan cache:clear
composer dump-autoload
```

---

## ✅ Testing Your Setup

### Test 1: Verify Commands

```bash
php artisan list crudly
```

Should output 3 commands.

### Test 2: Test Database Connection

```bash
php artisan tinker
>>> DB::connection()->getPdo();
```

Should return connection object.

### Test 3: Test CRUD Generation

```bash
php artisan make:migration create_tests_table
# Edit and add: id, name, timestamps
php artisan migrate
php artisan crudly:generate Test --routes
php artisan serve
# Visit: http://localhost:8000/tests
```

### Test 4: Check Generated Files

```bash
# Model should exist
test -f app/Models/Test.php && echo "✅ Model exists"

# Controller should exist
test -f app/Http/Controllers/TestController.php && echo "✅ Controller exists"

# Views should exist
test -d resources/views/tests && echo "✅ Views exist"

# Routes should be added
grep -q "Route::resource('tests'" routes/web.php && echo "✅ Routes added"
```

---

## 🎯 Best Practices

### 1. Always Create Migration First

**✅ Correct:**
```bash
php artisan make:migration create_posts_table
php artisan migrate
php artisan crudly:generate Post --routes
```

**❌ Wrong:**
```bash
php artisan crudly:generate Post --routes
# Table doesn't exist!
```

### 2. Use Descriptive Names

**✅ Good:**
```bash
php artisan crudly:generate BlogPost --routes
php artisan crudly:generate ProductCategory --routes
```

**❌ Bad:**
```bash
php artisan crudly:generate BP --routes
php artisan crudly:generate PC --routes
```

### 3. Clear Cache After Changes

```bash
php artisan cache:clear
php artisan route:clear
php artisan view:clear
composer dump-autoload
```

### 4. Test Generated Code

```bash
php artisan tinker

# Test model
Post::create(['title' => 'Test', 'content' => 'Test', 'slug' => 'test']);
Post::all();

# Test relationships
Post::with('comments')->get();
```

### 5. Customize After Generation

Don't rely only on generated code. Add:
- Custom methods
- Relationships
- Scopes
- Validation rules
- Business logic

---

## 🔐 Security Checklist

### CSRF Protection

All forms include `@csrf` automatically.

### Authorization

Add to controller:

```php
public function edit(Post $post)
{
    $this->authorize('update', $post);
    return view('posts.edit', ['post' => $post]);
}
```

### Input Validation

Customize rules:

```php
'title' => 'required|string|max:255|unique:posts',
'content' => 'required|string|min:10',
'slug' => 'required|slug|unique:posts',
```

### SQL Injection Prevention

Always use Eloquent:

```php
// ✅ Safe
Post::where('title', $request->title)->get();

// ❌ Unsafe
Post::whereRaw("title = '{$request->title}'");
```

---

## 📚 Advanced Features

### Add Relationships

```php
// In app/Models/Post.php
public function author()
{
    return $this->belongsTo(User::class);
}

public function comments()
{
    return $this->hasMany(Comment::class);
}
```

### Load with Relationships

```php
// In PostController
public function index()
{
    $posts = Post::with('author', 'comments')
        ->latest()
        ->paginate(15);
    
    return view('posts.index', ['posts' => $posts]);
}
```

### Add Soft Deletes

Edit migration:

```php
$table->softDeletes();
```

Edit model:

```php
use Illuminate\Database\Eloquent\SoftDeletes;

class Post extends Model
{
    use HasFactory, SoftDeletes;
}
```

### Add Scopes

```php
// In model
public function scopePublished($query)
{
    return $query->where('published', true);
}

// In controller
$posts = Post::published()->paginate(15);
```

---

## 📞 Getting Help

### Documentation

Full docs: https://github.com/Shahrakii/Crudly

### Common Issues

Check issues: https://github.com/Shahrakii/Crudly/issues

### Report Bugs

Create issue with:
- PHP version: `php -v`
- Laravel version: `php artisan --version`
- Package version: `composer show shahrakii/crudly`
- Error message
- Steps to reproduce

---

## 📄 License

MIT License - Use freely in personal and commercial projects.

---

## 🙏 Credits

- **Laravel 12** - Web Framework
- **Tailwind CSS** - Styling
- **Font Awesome** - Icons

---

## 🎉 You're All Set!

### Summary of Commands

```bash
# Install
composer require shahrakii/crudly:@dev
php artisan vendor:publish --provider="Shahrakii\Crudly\CrudlyServiceProvider"

# Create table
php artisan make:migration create_posts_table
php artisan migrate

# Generate CRUD
php artisan crudly:generate Post --routes

# Run server
php artisan serve

# Visit
# http://localhost:8000/posts
```

**That's it! You now have a full working CRUD app.** 🚀

---

**Version:** 1.0.0  
**Last Updated:** February 2026  
**Author:** Shahrakii

**⭐ Please star the repository if this package helped you!**
