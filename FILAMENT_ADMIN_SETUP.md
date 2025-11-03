# Filament Admin Panel Setup Guide

## Overview
Your e-commerce API now has **FilamentPHP v4** installed - a modern admin panel for Laravel!

## What's Already Set Up ✅

1. **FilamentPHP v4 Installed** - Latest version with all packages
2. **Existing Admin Resources:**
   - ✅ Categories (app/Filament/Resources/Categories/CategoryResource.php)
   - ✅ Products (app/Filament/Resources/Products/ProductResource.php)
   - ✅ Orders (app/Filament/Resources/Orders/OrderResource.php)

3. **Admin User Seeder Ready** - database/seeders/AdminUserSeeder.php

## Quick Start Guide

### Step 1: Start MySQL
Open XAMPP Control Panel and start **MySQL**

### Step 2: Run Pending Migrations
```bash
php artisan migrate
```

### Step 3: Create Admin User
```bash
php artisan db:seed --class=AdminUserSeeder
```

**Default Admin Credentials:**
- Email: `admin@admin.com`
- Password: `password`

### Step 4: Start Development Server
```bash
php artisan serve
```

### Step 5: Access Admin Panel
Navigate to: http://localhost:8000/admin

Login with the credentials above!

## Creating Additional Admin Resources

### Create Remaining Resources

Run these commands to create admin resources for all models:

```bash
# User Management
php artisan make:filament-resource User --simple

# Transactions
php artisan make:filament-resource WalletTransaction --simple
php artisan make:filament-resource PointTransaction --simple

# Marketing
php artisan make:filament-resource Coupon --simple
php artisan make:filament-resource Banner --simple
php artisan make:filament-resource Special --simple

# System
php artisan make:filament-resource Feedback --simple
php artisan make:filament-resource Notification --simple
php artisan make:filament-resource AppSetting --simple
php artisan make:filament-resource DeliveryMethod --simple
```

The `--simple` flag creates a simple modal resource (single page). Remove it for full CRUD pages.

## Example: Customizing a Resource

After generating a resource, customize it in `app/Filament/Resources/`:

###  Form Example (UserResource.php):
```php
public static function form(Form $form): Form
{
    return $form->schema([
        Forms\Components\TextInput::make('name')->required(),
        Forms\Components\TextInput::make('email')->email()->required(),
        Forms\Components\TextInput::make('phone'),
        Forms\Components\FileUpload::make('profile_picture')->image(),
        Forms\Components\Toggle::make('phone_verified'),
    ]);
}
```

### Table Example (UserResource.php):
```php
public static function table(Table $table): Table
{
    return $table
        ->columns([
            Tables\Columns\TextColumn::make('name')->searchable(),
            Tables\Columns\TextColumn::make('email')->searchable(),
            Tables\Columns\TextColumn::make('phone'),
            Tables\Columns\IconColumn::make('phone_verified')->boolean(),
            Tables\Columns\TextColumn::make('created_at')->dateTime(),
        ])
        ->filters([
            Tables\Filters\Filter::make('verified')
                ->query(fn ($query) => $query->where('phone_verified', true)),
        ])
        ->actions([
            Tables\Actions\EditAction::make(),
            Tables\Actions\DeleteAction::make(),
        ]);
}
```

## Dashboard Widgets

Create custom widgets for your dashboard:

```bash
# Statistics Widget
php artisan make:filament-widget StatsOverview --stats

# Chart Widget
php artisan make:filament-widget OrdersChart --chart

# Table Widget
php artisan make:filament-widget LatestOrders --table
```

Example Stats Widget (StatsOverviewWidget.php):
```php
protected function getStats(): array
{
    return [
        Stat::make('Total Users', User::count())
            ->description('Registered users')
            ->icon('heroicon-o-users'),

        Stat::make('Total Orders', Order::count())
            ->description('All time orders')
            ->icon('heroicon-o-shopping-bag'),

        Stat::make('Revenue', '$' . Order::sum('total'))
            ->description('Total revenue')
            ->icon('heroicon-o-currency-dollar'),
    ];
}
```

## Panel Configuration

Configure your panel in `app/Providers/Filament/AdminPanelProvider.php`:

```php
public function panel(Panel $panel): Panel
{
    return $panel
        ->default()
        ->id('admin')
        ->path('admin')
        ->login()
        ->colors([
            'primary' => Color::Amber,
        ])
        ->discoverResources(in: app_path('Filament/Resources'), for: 'App\\Filament\\Resources')
        ->discoverPages(in: app_path('Filament/Pages'), for: 'App\\Filament\\Pages')
        ->pages([
            Pages\Dashboard::class,
        ])
        ->discoverWidgets(in: app_path('Filament/Widgets'), for: 'App\\Filament\\Widgets')
        ->widgets([
            Widgets\AccountWidget::class,
        ])
        ->middleware([
            EncryptCookies::class,
            AddQueuedCookiesToResponse::class,
            StartSession::class,
            AuthenticateSession::class,
            ShareErrorsFromSession::class,
            VerifyCsrfToken::class,
            SubstituteBindings::class,
            DisableBladeIconComponents::class,
            DispatchServingFilamentEvent::class,
        ])
        ->authMiddleware([
            Authenticate::class,
        ]);
}
```

## Navigation Groups

Organize resources into navigation groups by adding to your resources:

```php
protected static ?string $navigationGroup = 'E-commerce';
protected static ?int $navigationSort = 1;
protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';
```

Example organization:
- **E-commerce**: Products, Categories, Orders, Coupons
- **Users**: Users, Feedback, Addresses
- **Transactions**: Wallet Transactions, Point Transactions
- **Content**: Banners, Specials, Notifications
- **Settings**: App Settings, Delivery Methods, Languages

## Advanced Features

### 1. Relationships
```php
// In OrderResource
public static function getRelations(): array
{
    return [
        RelationManagers\OrderItemsRelationManager::class,
    ];
}
```

### 2. Global Search
```php
protected static ?string $recordTitleAttribute = 'name';

public static function getGlobalSearchResultTitle(Model $record): string
{
    return $record->name;
}
```

### 3. Bulk Actions
```php
Tables\Actions\BulkActionGroup::make([
    Tables\Actions\DeleteBulkAction::make(),
    Tables\Actions\BulkAction::make('approve')
        ->action(fn (Collection $records) => $records->each->approve())
        ->icon('heroicon-o-check'),
]),
```

### 4. Custom Pages
```bash
php artisan make:filament-page Settings
```

## File Uploads Configuration

For image uploads (products, banners, profile pictures), configure storage:

In `config/filesystems.php`, ensure public disk is set:
```php
'public' => [
    'driver' => 'local',
    'root' => storage_path('app/public'),
    'url' => env('APP_URL').'/storage',
    'visibility' => 'public',
],
```

Create storage link:
```bash
php artisan storage:link
```

## Resources Documentation

📚 Official Filament Documentation: https://filamentphp.com/docs/4.x/panels

## Troubleshooting

### Issue: Cannot access /admin
- Make sure `php artisan serve` is running
- Check that you've created an admin user
- Clear cache: `php artisan cache:clear`

### Issue: Styles not loading
```bash
php artisan filament:assets
php artisan optimize:clear
```

### Issue: Resources not appearing
```bash
php artisan filament:optimize-clear
composer dump-autoload
```

## Next Steps

1. ✅ Start MySQL and run migrations
2. ✅ Create admin user
3. ✅ Access admin panel
4. Create remaining resources for your models
5. Customize forms and tables
6. Add dashboard widgets
7. Set up file uploads for products/banners
8. Configure user roles and permissions (optional)

## Support

- Filament Discord: https://discord.com/invite/filament
- Documentation: https://filamentphp.com/docs
- GitHub: https://github.com/filamentphp/filament

---

**Your API is ready with a professional admin panel!** 🎉

Just start MySQL, run the migrations and seeder, then access http://localhost:8000/admin
