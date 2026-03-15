# Class Loading in PHP

## Comparison with Java

```java
// Java
package com.myapp.domain;
import com.myapp.infrastructure.MysqlProductRepository;
```

```php
// PHP equivalent
namespace App\Domain;
use App\Infrastructure\MysqlProductRepository;
```

### `autoload.php`

```json
{
    "autoload": {
        "psr-4": {
            "App\\": "src/"
        }
    }
}
```

This single rule tells the autoloader: whenever you see a class whose namespace starts with App\, look for it in the src/ directory, and translate the rest of the namespace into subdirectories.

Example: `App\Domain\Product → src/Domain/Product.php`

### `PSR-4`

It strips the App\ prefix, replaces it with src/, swaps backslashes for directory separators, and appends .php. That's all PSR-4 is — a naming convention that maps namespaces to folders.
