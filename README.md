# Debug Toolset Class

This class helps with outputting info the developer sends to it; but it will never display to visitors.  Very handy if you are concerned about pushing the debug code to the production server.  If your IP is registered with the class then you will see a "Developer Mode" tag in the upper-right corner of the screen.

## How To Use

### Instantiating the Class
```php
$debug = debug::obtain();
```

### Add your IP's to the $development array in the class
There's an example in the class.  You can leave the ending octet blank to act as a "wildcard" for IP blocks.
```php
public $development = array(
	'Work'			=> '123.34.678.',	//Note: You can leave the ending octet blank to act as a "wildcard" for IP blocks.
	'Home'			=> '98.765.43.21',
	'Cafe'			=> '101.01.010.1'
);
```

#### Output array
This method will output your array in _pre_ tags for readability.  Like all the other methods & properties in this class, this output is only visible to the IP's listed in the $development array.
```php
$debug->printArray($myArray);
```

#### Halt processing if a PHP version is required
If your script / framework requires a PHP version minimum then you can use this method to enforce it.
```php
$debug->versionCheck('5.3.0');
```

#### Display framework files on screen for referrencing
```php
$directories = array('templates/', 'plugins/');
$debug->debugBar($directories);
```

#### Output all your defines
```php
$debug->allDefines();
```

#### Output all the properties in a class
```php
$debug->allVars($class);
```

#### Output all the methods in a class
```php
$debug->allMethods($class);
```
