# Installing

First Install Package

```bash
$ composer require iracode-com/filament-organizational-shield
```

First Publish The Spatie PermissionServiceProvider

```bash
php artisan vendor:publish --provider= Spatie\Permission\PermissionServiceProvider
```

Second Publish The Plugin Filament Resources, Migrations, Config file, Translation

```bash
php artisan iracode-shield:publish
```

Run Artisan Migrate For Add Columns To Table `Users`

```bash
php artisan migrate
```

Add These Plugins To List `app/Providers/Filament/AdminPanelProvider.php`

```php
<?php

namespace App\Providers\Filament;

use IracodeCom\FilamentNotification\FilamentNotificationPlugin;
use Filament\PanelProvider;
use Filament\Panel;

class AdminPanelProvider extends PanelProvider
{
    public function panel( Panel $panel ) : Panel
    {
        return $panel
            ->plugins( [
                
                FilamentShieldPlugin::make(), // <-- required
                
                BreezyCore::make()->myProfile(hasAvatars: true), // <-- required
                
                FilamentOrganizationalShieldPlugin::make(), // <-- required
                
            ] )
        ;
    }
}
```

# Security

If you discover any security related issues, please email `ardavanshamroshan@yahoo.com` instead of using the issue tracker.

# License

The MIT License (MIT). Please see [License File](LICENSE) for more information.