laravel-admin-ext/log-viewer
============================

## Installation

```
$ composer require laravel-admin-ext/log-viewer -vvv

```

Open `app/Providers/AppServiceProvider.php`, and call the `LogViewer::boot` method within the `boot` method:

```php
<?php

namespace App\Providers;

use Encore\Admin\LogViewer\LogViewer;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function boot()
    {
        LogViewer::boot();
    }
}
```

At last run: 

```
$ php artisan admin:import log-viewer
```

Finally open `http://localhost/admin/logs`.

License
------------
Licensed under [The MIT License (MIT)](LICENSE).
