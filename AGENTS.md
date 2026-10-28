# GitHub Copilot Instructions: Laravel MVC & Blade

## 1. Stack & Environment
- **Framework:** Laravel (Latest stable version)
- **Architecture:** Standard MVC (Model-View-Controller). Use Service/Action classes when logic expands beyond basic CRUD.
- **Templating Engine:** Blade + Bootstrap 5 / CSS (with Alpine.js for lightweight client-side interactivity).
- **Database:** Eloquent ORM, Migrations, Factories, and Seeders.
- **Language:** PHP 8.x with strict typing enabled (`declare(strict_types=1);`).

---

## 2. Coding Standards & Conventions
- Adhere strictly to **PSR-12** standards and standard Laravel conventions:
  - **PascalCase:** Models, Controllers, Services, Actions, Blade Components.
  - **camelCase:** Methods, variables, routes names.
  - **snake_case:** Database tables, column names, foreign keys, Blade view filenames.
  - **kebab-case:** HTML attributes, URL paths, CSS classes.
- Declare strict types at the top of every PHP file: `declare(strict_types=1);`.
- Use explicit type hints for arguments and return types on all methods.

---

## 3. Controllers & Routing
- Keep Controllers **thin**. Delegate complex business logic to dedicated Service or Action classes.
- Use **Form Requests** (`php artisan make:request`) for validation and authorization rather than validating inline within controllers.
- Use **Route Model Binding** whenever possible.
- Return explicit response types (`View`, `RedirectResponse`, `JsonResponse`).

```php
// Example Controller Standard
declare(strict_types=1);

namespace App\Http\Controllers;

use App\Http\Requests\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function edit(User $user): View
    {
        return view('users.edit', compact('user'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $user->update($request->validated());

        return redirect()
            ->route('users.index')
            ->with('success', __('User updated successfully.'));
    }
}
```

---

## 4. Models & Eloquent ORM
- Always define explicit `$fillable` attributes on Eloquent models to guard against mass-assignment vulnerabilities.
- Explicitly define standard relationships (`hasMany`, `belongsTo`, `belongsToMany`) with type declarations.
- Avoid **N+1 query issues** by eagerly loading relations using `with()` in controllers or repository methods.
- For constrained eager-load callbacks passed to `with()` or `load()`, type the argument as the relationship instance (for example, `BelongsTo`), not `Builder`.
- Use Eloquent Scopes for repeated query constraints.

```php
// Example Model Standard
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Post extends Model
{
    protected $fillable = [
        'title',
        'slug',
        'content',
        'is_published',
        'user_id',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function comments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    public function scopePublished(Builder $query): Builder
    {
        return $query->where('is_published', true);
    }
}
```

---

## 5. Blade Templates & UI Component Structure
- Use **Blade Anonymous Components** (`<x-component-name />`) or Class-based Components for reusable UI elements.
- Maintain layout structures using modern `<x-layout>` tags rather than legacy `@extends` where feasible.
- Keep Blade views clean: zero database queries or heavy calculations inside views.
- Escape all output by default (`{{ $variable }}`). Use `{!! $variable !!}` only when HTML is explicitly trusted and sanitized.
- Always include `@csrf` in HTML forms submitting via `POST`, `PUT`, `PATCH`, or `DELETE`.

```html
{{-- Example Layout Component Usage --}}
<x-app-layout>
    <x-slot:title>
        {{ __('User Profile') }}
    </x-slot:title>

    <div class="max-w-4xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
        <h1 class="text-xl font-semibold text-gray-900">{{ $user->name }}</h1>

        <form action="{{ route('users.update', $user) }}" method="POST" class="mt-4 space-y-4">
            @csrf
            @method('PUT')

            <x-input name="email" :value="$user->email" label="Email Address" />
            <x-button type="submit">
                {{ __('Save Changes') }}
            </x-button>
        </form>
    </div>
</x-app-layout>
```

---

## 6. Security & Authorization
- Use Laravel **Policies** and **Gates** for handling access control (`$this->authorize('update', $user)` or `@can('update', $user)`).
- Never use raw SQL queries without parameter binding.
- Sanitize and validate all incoming request parameters strictly via Request classes.
- Use **hashed passwords** and never store plain text passwords.
- Enable **CSRF protection** for all state-changing requests.
- Regularly update dependencies to patch known security vulnerabilities.
- Limit sensitive data exposure in logs and error messages.

## 7. Project Domain
MVP inventory system for hygiene products.
Scope: `Category`, `Product`, `StockMovement`. Do not scaffold modules outside this scope.

## 8. Language Rules
- Code in English (classes, variables, methods, DB, routes, comments).
- UI in Spanish (Blade copy, labels, flash messages, validation messages).
- Validation messages live in `lang/es/validation.php`.

## 9. Soft Deletes
- Apply `SoftDeletes` to every entity.
- Migrations must include `$table->softDeletes()`.
- Unique constraints (`sku`, `slug`) must account for soft-deleted rows.

## 10. Out of Scope (do not add unless asked)
- Auth, users, roles, permissions
- API endpoints
- Tests
- Docker / CI
