# Laravel Menu Builder
Navigation menu builder for Laravel. This package is currently handling only menu filtering and building. This package will be able to render the menu in the blade template soon.

## Installation
1. Using Composer
    ```shell
    composer require azizsama/menu-builder
    ```
2. Publish the config file
    ```shell
    php artisan vendor:publish --provider="AzizSama\\MenuBuilder\\MenuBuilderServiceProvider"
    ```
3. Finish.

## Usage

### Creating menu

Navigate to `config/menu-builder.php` and add your menu items at the `menu.$group` array.
For example,

```php
    ... // menu.main
    [
        'text' => 'Home',
        'url' => '/',
        'target' => '',
        'active' => [
            '/',
        ],
        'auth' => true, // to apply the auth check.
        'childs' => [ // the menu childs. there is no actual limit for the descendants count.
            [
                'text' => 'About',
                'url' => '/about',
                'target' => '',
                'active' => [
                    '/about',
                ],
            ],
            [
                'text' => 'Contact',
                'url' => '/contact',
                'target' => '',
                'active' => [
                    '/contact',
                ],
            ],
        ]
    ]
```

### Creating a filter class

1. Using the `make:filter` artisan command.
    ```shell
    php artisan make:filter AuthFilter
    ```
2. Navigate to the `app/Filters` directory and open the `AuthFilter.php` file.
    ```php
    <?php
    ...
    class AuthFilter extends Filter
    {
        public function apply($item): array
        {
            if (isset($item['auth']) && $item['auth'] === true) { // check if the item must be filtered by auth check.
                if (!Auth::check()) {
                    return $this->disable($item); // disable the menu item if the user is not authenticated.
                }
            }
            // return the item if the auth check is not required.
            return $item;
        }
    }
    ```
3. Add the filter class to `config.menu-builder.filters` array.
    ```php
    ... // config.menu-builder.filters
    [
        App\Filters\AuthFilter::class,
    ]
    ```

### Getting the menu
1. Using the Facade class `MenuBuilder`
    ```php
    $menu = MenuBuilder::get('main'); // get the main group of the menu.
    $sidebar = MenuBuilder::get('sidebar'); // get the sidebar group of the menu.
    $all = MenuBuilder::get(); // get all the menu items.
    ```
2. Using the `menuBuilder()` method
    ```php
    $menu = menuBuilder()->get('main'); // get the main group of the menu.
    $sidebar = menuBuilder()->get('sidebar'); // get the sidebar group of the menu.
    $all = menuBuilder()->get(); // get all the menu items.
    ```

### Add/Modify menu items at runtime
1. Navigate to `App\Providers\AppServiceProvider` or any Service Provider class you want.
2. In the boot method, call the following method:
    ```php
    // add the \Illuminate\Contracts\Events\Dispatcher in the parameter of the boot method.
    
    $events->listen(AzizSama\MenuBuilder\Events\BuildingMenu::class, function(BuildingMenu $event) {
        $builder = $event->builder;
        // $builder->add($group, $theMenuItem);
        // example:
        $builder->add('sidebar', [
            'text' => 'Whatever',
            'url' => '/go-there-or-wherever-you-want'
            ...
        ]);

        // modify the menu item.
        // the menu key is separated by a dot sign.
        // example: 'sidebar.whatever.anything'
        // will look for the menu from the sidebar group and the 'whatever' item's child with key = 'anything'.
        $builder->edit('the.menu.key', [
            'text' => 'Change the menu text',
        ]);

        // remove the menu item.
        // the menu key is separated by a dot sign.
        // example: 'sidebar.whatever.anything'
        // will look for the menu from the sidebar group and the 'whatever' item's child with key = 'anything'.
        $builder->remove('the.menu.key');
    });
    
    ```