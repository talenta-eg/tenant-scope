# Tenant Scope (Strong Global Scope for Laravel)

This package provides shared `tenant_id` global scoping across Laravel services.

## Installation

### 1. Install via Composer
```bash
composer require skillup/tenant-scope
```

If you're using it locally, add the repo to your `composer.json`:
```json
"repositories": [
    {
        "type": "path",
        "url": "../packages/tenant-scope"
    }
]
```

### 2. Publish Config (Optional)
```bash
php artisan vendor:publish --tag=config
```

### 3. Usage in Model
```php
use TenantScope\Traits\BelongsToTenant;

class User extends Model
{
    use BelongsToTenant;

    protected $fillable = [
        'tenant_id', 'name', 'email'
    ];
}
```

### 4. Set Tenant Context on (Request, Jobs, Artisan)

Unit tests
In your Background jobs , Artisan commands and Unit tests:
```php
TenantContext::setTenantId($request->header('X-Tenant-ID'));
```

### 5. Set Tenant Context from Middleware
Once your package is installed:

- The middleware is auto-registered as ```tenant.context```
- You can get ```tenant_id``` from ```header``` or ```jwt```
- You can use it in routes:
```php
Route::middleware('tenant.context')->group(function () {
   // Get only users belongs to the tenant
   $users= \App\Models\User::all()->toArray();

});
```

## How to Use in Microservices
- Include the package via Composer
- Call `TenantContext::setTenantId($id)` early in the request lifecycle
- Add `use BelongsToTenant` on all tenant-scoped models

## Indexing Best Practice
In Laravel migration:
```php
$table->index(['tenant_id', 'email']); // Composite index with tenant_id first
```

---
