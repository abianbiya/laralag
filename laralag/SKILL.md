---
name: laralag
description: "Expert guide for the Laralag Laravel package — CRUD generator and access management system. Use when: (1) Generating modules with lag:module, lag:api, lag:migration, (2) Working with RBAC (roles, permissions, scopes, CheckPermission middleware), (3) Managing menus/menu groups/modules in sidebar, (4) Modifying built-in modules (User, Role, Permission, Menu, MenuGroup, Module, RoleUser, Scope, Log, PermissionRole), (5) Writing code following Laralag conventions, (6) Configuring laralag.php, (7) Using HasPermissions, HasAuditTrail, IntrospectTable traits, (8) Customizing stub templates, (9) Livewire auth flows, (10) Debugging permissions. Triggers: 'laralag', 'lag:module', 'lag:api', 'lag:migration', 'lag:install', 'lag:seed', 'lag:sync-permission', 'HasPermissions', 'HasAuditTrail', 'CheckPermission', 'actionButton', 'MenuGroup'."
---

# Laralag Development Guide

## Package Location

```
packages/abianbiya/laralag/
```

Generated app modules go to `app/Modules/`.

## Quick Reference

### Artisan Commands

```bash
lag:install                                          # Full setup: migrate + publish + seed
lag:module {Name} [--no-menu] [--api] [--table=] [--hasFile] [--force]  # Generate CRUD
lag:api {Name} [--force]                             # Generate API controller
lag:migration {Name} [--table=]                      # Generate migration with UUID + audit
lag:seed                                             # Run all Laralag seeders
lag:sync-permission                                  # Sync route permissions to DB
```

### Defaults

`lag:module` by default:
- Creates menu entry (prompts for MenuGroup selection) — skip with `--no-menu`
- Assigns all generated permissions to the Root role
- Runs `php artisan optimize` after generation

### Standard Workflow

```bash
php artisan lag:migration Product         # 1. Create migration
# Edit migration, add columns
php artisan migrate                       # 2. Run migration
php artisan lag:module Product --api      # 3. Generate CRUD + menu + Root perms + optimize
```

## Architecture

See [references/architecture.md](references/architecture.md) for full directory structure, service provider, config options, route structure, and module pattern.

Key points:
- Modules follow: `{Name}/Controllers/`, `{Name}/Models/`, `{Name}/Views/`, `{Name}/routes.php`
- All models use `SoftDeletes`, `HasUuids`, `HasAuditTrail`
- All tables have UUID PKs + `created_by`/`updated_by`/`deleted_by` audit columns
- Views extend `Laralag::layouts.master`
- Forms use spatie/laravel-html builder + TomSelect for selects

## Code Generation

See [references/generators.md](references/generators.md) for complete command reference, stub templates, placeholder tokens, and generated code patterns.

Key conventions when writing or modifying generated code:

### Controller Pattern
- Use `Logger` trait for audit logging
- `$log` and `$title` properties for module identification
- Search via `whereAny([columns], 'LIKE', '%'.$request->cari.'%')`
- Forms as array: `$forms = ['field' => ['Label', html()->input(...)]]`
- Validate in `store()`/`update()`, redirect with success message
- Paginate with 10 items

### Model Pattern
- Traits: `SoftDeletes`, `HasUuids`, `HasAuditTrail`
- Define `$table` and `$fillable`
- Relations: `belongsTo` for FK columns, method name = column without `_id`

### View Pattern
- Index: table with search, pagination, action buttons via `actionButton()`
- Detail: table rows of field-value pairs
- Create/Edit: loop `$forms` array, TomSelect for selects
- All extend `Laralag::layouts.master`, use `@section('content')`

### Route Pattern
- `Route::controller(XController::class)->middleware(['web','auth'])->group(...)`
- Each route has `->middleware('permission:{slug}.{action}')` and `->name('{slug}.{action}')`
- Standard 7 CRUD routes: index, create, store, show, edit, update, destroy
- API routes use `['api', 'auth:sanctum']` middleware with `api/` prefix

## RBAC System

See [references/rbac.md](references/rbac.md) for complete RBAC, menu, auth, and helper function reference.

Key points:
- User model must `use HasPermissions`
- Permissions stored in session after login (not checked from DB per-request)
- Route protection: `->middleware('permission:product.index')`
- Gate: `can('product.index')` in views/controllers
- Sidebar auto-filters by user's active permissions
- Scopes enable multi-tenant role assignment (optional)
- 7 standard permissions per module: index, create, store, show, edit, update, destroy
- Helpers: `can()`, `get()`, `actionButton()`, `tanggal()`, `rupiah()`

## When Modifying the Package

### Adding a new built-in module
1. Create `src/Modules/{Name}/` with Controllers, Models, Views, routes.php
2. Follow existing module patterns (copy Role or User module)
3. Add seeder for initial data in `src/Seeders/`
4. Add permissions to `PermissionTableSeeder`
5. Add menu entries to `MenuTableSeeder` and `ModuleTableSeeder`

### Modifying stubs
- Stubs are at `resources/stubs/`
- Placeholder tokens are replaced by `GenerateModule` command
- Test changes by generating a module after editing

### Extending RBAC
- Add new session keys in `HasPermissions` trait
- Update `CheckPermission` middleware for new auth logic
- Update `Sidebar` component query for new visibility rules

### Adding config options
- Add to `config/laralag.php`
- Reference via `config('laralag.your_key')`
- Document in config file with comments
