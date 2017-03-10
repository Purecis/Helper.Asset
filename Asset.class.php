<?php
 /**
 * Helper.Asset
 *
 * @category   codeHive Extras
 * @package    Helper
 * @author     Tamer Zorba <tamer.zorba@purecis.com>
 * @copyright  Copyright (c) 2013 - 2016, Purecis, Inc.
 * @license    http://package.hive.live/Helper.Asset/license  MIT License
 * @link       http://package.hive.live/Helper.Asset
 * @version    Release: 3.0
 */

namespace App\Helper;

use \App\System\Module;
use \App\System\Event;
use \App\System\Request;
use \App\System\Str;
use \App\System\Scope;
use \App\System\Loader;
use \App\System\FileSystem;

class Asset extends Module{

    /**
     * initialize defaults.
     */
    public function __bootstrap(){

        // define default meta tags
        $evt = new Event("AssetsManagment");
        $evt->addListener('defaults', "<meta charset='utf-8'>");
        $evt->addListener('defaults', "<meta name='viewport' content='width=device-width, initial-scale=1.0'>");
        $evt->addListener('defaults', "<meta http-equiv='X-UA-Compatible' content='IE=edge' />");
        
        // define default javascript codeHive path
        $hive = new Scope("config.hive");
        $this->jsDefine('path.base', $this->request->base);
        $this->jsDefine('path.app', $hive->app_path);
        $this->jsDefine('path.view', $hive->app_path . "/view");
        $this->jsDefine('path.vendor', $hive->app_path . "/vendor");
        $this->jsDefine('path.assets', $hive->assets);
        $this->jsDefine('path.library', $hive->assets . "/library");
        $this->jsDefine('path.domain', $this->request->domain);
    }

    /**
     * define javascript code.
     *
     * @param string $name_path
     * @param Mixen  $value
     * @param boolean $isPush
     */
    protected static $jsDefined = [];
    public function jsDefine()
    {
        $args = func_get_args();
        $evt = new Event("AssetsManagment");
        
        // check codeHive JS Object Container is exists
        if(sizeof(self::$jsDefined) == 0){
            $evt->addListener('definer', "var codeHive = codeHive || {};");
            array_push(self::$jsDefined, "codeHive");
        }
        
        // extract params
        $names = isset($args[0]) ? explode('.', $args[0]) : [];
        $value = isset($args[1]) ? $args[1] : '';
        $isPush = isset($args[2]);

        // handle objects and arrays
        if(is_array($value) || is_object($value)){
            $value = json_encode($value, JSON_NUMERIC_CHECK);
        }else{
            $value = '"' . $value . '"';
        }
            
        foreach($names as $idx => $name){
            $temp = $names;
            array_splice($temp, $idx+1);
            $name = implode('.', $temp);

            if(sizeof($names)-1 == $idx){
                
                // define variables
                if($isPush){
                    $evt->addListener('definer', "codeHive.{$name} = codeHive.{$name} || [];");
                    $evt->addListener('definer', "codeHive.{$name}.push({$value});");
                }else{
                    $evt->addListener('definer', "codeHive.{$name} = {$value};");
                }
            }else{
                
                // fallback address
                if(in_array("codeHive.{$name}", self::$jsDefined)){
                    continue;
                }
                $evt->addListener('definer', "codeHive.{$name} = codeHive.{$name} || {};");
                array_push(self::$jsDefined, "codeHive.{$name}");
            }
        }
    }
    
    /**
     * push string or array to javascript array.
     *
     * @param string $src    Path of js file
     * @param string $folder default load folder
     * @param string $ext    extension to load inside folder
     *
     * @return string
     */
    public function jsPush()
    {
        $args = func_get_args();
        array_push($args, true);
        
        call_user_func_array(["self", "jsDefine"], $args);
    }

    /**
     * Parse Source string Load assets.
     *
     * @param string $src    Path of js file
     * @param string $folder default load folder
     * @param string $ext    extension to load inside folder
     *
     * @return string
     */
    private function srcParser($src, $folder, $ext = false)
    {
        $hive = new Scope('config.hive');
        $request = new Request;

        // check if not external link then
        if(!Str::contains($src, '//')) {
            $src = Loader::getDir($folder . "/" . $src);
            $src = $request->base . $src;
        }
        
        return $src;
    }

    /**
     * add Script to Event.
     *
     * @param string $src      Path of js file
     * @param string $folder   default load folder
     */
    public function script($src, $folder = 'vendor')
    {
        $src = $this->srcParser($src, $folder);
        
        $evt = new Event("AssetsManagment");
        $evt->addListener('script', "<script type='text/javascript' src='{$src}'></script>");
    }

    /**
     * add Style to Event.
     *
     * @param string $src      Path of js file
     * @param string $folder   default load folder
     */
    public function style($src, $folder = 'vendor')
    {
        $src = $this->srcParser($src, $folder);
        $type = FileSystem::extension($src) == 'less' ? '/less' : '';
        
        $evt = new Event("AssetsManagment");
        $evt->addListener('style', "<link rel='stylesheet{$type}' type='text/css' href='{$src}' />");
    }
}


