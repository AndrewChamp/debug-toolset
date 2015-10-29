# Debug Toolset Class

This class helps with outputting info the developer sends to it; but it will never display to visitors.  Very handy if you are concerned about pushing the debug code to the production server.  If your IP is registered with the class then you will see a "Developer Mode" tag in the upper-right corner of the screen.

## How To Use

### Instantiating the Class
```php
$debug = debug::obtain();
```

### Halt processing if a PHP version is required
```php
$debug->versionCheck('5.3.0');
```

### Output array
```php
$debug->printArray($myArray);
```

### Display framework files on screen for referrencing
```php
$directories = array('templates/', 'plugins/');
$debug->debugBar($directories);
```

### Output all your defines
```php
$debug->allDefines();
```

### Output all the properties in a class
```php
$debug->allVars($class);
```

### Output all the methods in a class
```php
$debug->allMethods($class);
```
