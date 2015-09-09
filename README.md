[![Latest Stable Version](https://img.shields.io/packagist/v/krenor/eloquent-filter.svg?style=flat-square)](https://packagist.org/packages/krenor/eloquent-filter)
[![License](https://img.shields.io/packagist/l/krenor/eloquent-filter.svg?style=flat-square)](https://packagist.org/packages/krenor/eloquent-filter)

# eloquent-filter

Simple and easy filtering an Eloquent Query of [Laravel 5.1](http://laravel.com/) with Inputs!

## Installation

### Step 1: Install Through Composer

Add to your root composer.json and install with `composer install` or `composer update`

    {
      require: {
        "krenor/eloquent-filter": "~1.0"
      }
    }

or use `composer require krenor/eloquent-filter` in your console.

### Step 2: Import the Trait

In the Model you want to listen to Inputs, and automatically
filter them down by that input, just use and add the Trait.

```php
namespace App\Http\Models;

use Illuminate\Database\Eloquent\Model;
use Krenor\EloquentFilter\FilterableTrait;

class Order extends Model
{
	use FilterableTrait;

    ...
}
```


## Usage

Add a `protected $filterable = []` to the Model using the Trait.

1. **Column Names**
	* Correspond to the **column name** in the URL and filter down by that.
	URL : */some_orders/all?status_id=2*
	`protected $filterable = [ 'status_id' ]`

2. **Aliases**
	1. Use an **column alias** instead for *nice names*.
	URL : */some_orders/all?status=2*
	`protected $filterable = [ 'status_id' => 'status' ]`

	2. Add **values aliases** to a column alias.
	URL : */some_orders/all?status=processing*
    ```php
    protected $filterable = [
        // column name => column alias
        'status_id' => ['status' => [
            // value aliases in database => input value
            1 => 'pending',
            2 => 'processing',
            3 => 'completed'
        ]
    ]
	```

Now each time you run a query like `Order::with('relation1')->paginate()`
the filter is automatically applied and checks for the Inputs in the URL.
Note that this package **currently only supports the equal operator** for filtering a query down.

## Contributing

If you believe you have found an issue, please report it using the [GitHub issue tracker](https://github.com/deringer/laravel-nullable-fields/issues),
or better yet, fork the repository and submit a [pull request](https://github.com/krenor/eloquent-filter/blob/master/CONTRIBUTING.md).



## Licence

eloquent-filter is distributed under the terms of the [MIT license](https://github.com/krenor/ldap-auth/blob/master/LICENSE.md)