# AI Coding Guidelines for Selah

## Architecture Overview
This is a Laravel 12 Livewire starter kit using Flux UI components for reactive, modern web applications. Authentication is handled by Laravel Fortify with registration, password reset, email verification, and two-factor authentication enabled.

**Key Components:**
- **Frontend**: Livewire components in `app/Livewire/` drive reactive UI
- **UI**: Flux components (`<flux:sidebar>`, `<flux:main>`, etc.) for consistent design
- **Auth**: Fortify manages login/register/password reset/email verification; two-factor with confirmation enabled
- **Assets**: Vite builds Tailwind CSS v4 from `resources/css/app.css`

## Development Workflow
- **Setup**: Run `composer run setup` (installs PHP/Node deps, migrates DB, builds assets)
- **Development**: Use `composer run dev` for concurrent PHP/Node dev servers
- **Build**: `npm run build` compiles assets for production
- **Test**: `./vendor/bin/phpunit` runs tests with in-memory SQLite
- **Lint**: `vendor/bin/pint` enforces Laravel code style

## Coding Patterns
### Livewire Components
Place components in `app/Livewire/` with subdirs like `Settings/`. Follow this structure:
```php
class Profile extends Component
{
    public string $name = '';
    
    public function mount(): void {
        $this->name = Auth::user()->name;
    }
    
    public function updateProfileInformation(): void {
        $validated = $this->validate(['name' => 'required|string|max:255']);
        Auth::user()->update($validated);
        $this->dispatch('profile-updated');
    }
}
```

### Views and Layouts
Use Flux components in Blade templates. Main layout is `resources/views/components/layouts/app.blade.php` with sidebar navigation.

Example dashboard view:
```blade
<x-layouts.app :title="__('Dashboard')">
    <flux:main>
        <div class="grid gap-4 md:grid-cols-3">
            <!-- Content -->
        </div>
    </flux:main>
</x-layouts.app>
```

### Routes
Web routes in `routes/web.php`, settings routes in `routes/settings.php`. Use Livewire components directly as route handlers:
```php
Route::get('settings/profile', Profile::class)->name('profile.edit');
```

### Testing
Use `RefreshDatabase` trait and factories. Test authenticated routes:
```php
public function test_authenticated_users_can_visit_the_dashboard(): void
{
    $this->actingAs(User::factory()->create());
    $this->get('/dashboard')->assertStatus(200);
}
```

### Authentication
Fortify handles auth features. Two-factor columns added to users table. Use `Auth::user()` in components.

### CI/CD
GitHub Actions run on `develop`, `main`, `components` branches. Tests PHP 8.4/8.5, Node 22. Requires Flux credentials for private packages.

## Key Files
- `composer.json`: Laravel 12 + Fortify + Livewire/Flux deps
- `vite.config.js`: Tailwind v4 + Laravel Vite plugin
- `phpunit.xml`: In-memory SQLite testing config
- `.github/workflows/`: CI for tests (PHP 8.4/8.5) and linting (Pint)</content>
<parameter name="filePath">/Users/isaac/code/personal/selah/.github/copilot-instructions.md