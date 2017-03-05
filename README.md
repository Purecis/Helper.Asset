// Usage : 
// initialize Asset Helper Module
use App\Helper\Asset;
$asset = new Asset;

// define javascript variables
$asset->jsDefine("angular.options.first", "SomeValue");
$asset->jsDefine("angular.options.secound", ["name" => "original name", "pass"=>"123456"]);
$asset->jsDefine("angular.options.secound.name", "updated name");

// append to array and create path in js
// you can access from javascript as [codeHive.angular.plugins]
$asset->jsPush("angular.plugins", "PluginName");

// define script or style
$asset->script("script.js@Container.Module");
$asset->style("style.css@Container.Module");