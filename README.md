# Cord\Settings

Settings Interface for Laravel 5 using Backpack CRUD.

## Install

Coming Soon...

1. In your terminal:
```
composer require cord/settings
```

2. For Laravel <5.5 apps, add the service provider to your config/app.php file:
```
Cord\Settings\SettingServiceProvider::class,
```

3. Publish files and run the migration.
```
php artisan vendor:publish --provider="Cord\Settings\SettingServiceProvider"
php artisan migrate
```

4. [Optional] Add a menu item for it in resources/views/vendor/backpack/base/inc/sidebar_content.blade.php
```
<!-- Settings -->
<li class="treeview">
    <a href="#"><i class="fa fa-gears"></i> <span>{{ ucfirst(trans('cord::settings.setting_plural')) }}</span> <i class="fa fa-angle-left pull-right"></i></a>
    <ul class="treeview-menu">
      <li><a href="{{ backpack_url('setting/app') }}"><i class="fa fa-wrench"></i> <span> {{ ucwords(trans('cord::settings.app').' '.trans('cord::settings.setting_plural')) }}</span></a></li>
      <li><a href="{{ backpack_url('setting/system') }}"><i class="fa fa-gear"></i> {{ ucwords(trans('cord::settings.system').' '.trans('cord::settings.setting_plural')) }}<span></span></a></li>
    </ul>
</li>
```

## Usage

### End user
Add it to the menu or access it by its routes:
- App Settings: **application/admin/setting/app**
- System Config Files: **application/admin/setting/system**

## Credits
- [Abby Janke](link-author)
- [All Contributors](link-contributors)

## Some Code Used From These Repositories
Great thanks to these amazing developers who helped make this possible:
- [Laravel-Backpack/Settings](Laravel-Backpack/Settings) (For developing base settings for application specific settings)
- [Ideatica/config-writer](Ideatica/config-writer) (For extending config repository making it editable)

## License

This package is licensed under MIT License. Please see the [License File](LICENSE.md) for full terms.
