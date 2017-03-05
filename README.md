
# Helper.Asset Module
Special Module help you manage Assets in `codeHive 3 Framework`.

## Installation : 
you can simply install it from hive command line as :

`hive install Helper.Asset`


Asset helper need Special Directives to defined in index.html file allowing events to dispatched correctly in position.
```html
<Asset load="defaults"></Asset>
<Asset load="style"></Asset>
<Asset load="definer"></Asset>
<Asset load="script"></Asset>
<Asset load="head"></Asset>
```


## Usage : 
initialize Asset Helper Module

```php
use App\Helper\Asset;
$asset = new Asset;
```

define javascript variables
```php
$asset->jsDefine("angular.options.first", "SomeValue");
$asset->jsDefine("angular.options.secound", ["name" => "original name", "pass"=>"123456"]);
$asset->jsDefine("angular.options.secound.name", "updated name");
```

append to array and create path in js
```php
// you can access from javascript as [codeHive.angular.plugins]
$asset->jsPush("angular.plugins", "PluginName");
```

define script or style
```php
$asset->script("script.js@Container.Module");
$asset->style("style.css@Container.Module");
```



## License
Copyright (c) 2013 - 2016, Purecis, Inc. All rights reserved.

This Module is part of codeHive framework and its open-sourced software licensed under the [MIT license](http://opensource.org/licenses/MIT)
