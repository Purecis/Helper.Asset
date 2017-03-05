<?php
namespace App\Helper\Asset\Directive;

use App\System\Directive;
use App\System\Response;
use App\System\Event;
use App\Helper\Asset as AssetHelper;

class Asset extends Directive
{
    public $priority = 20;
    
    public function __bootstrap(){
        // fallback to initialize assets
        new AssetHelper;
    }
    
    public function handle()
    {  
        $evt = new Event("AssetsManagment");

        $cache = '';
        if($this->directive->attr->load == 'definer' && $evt->count($this->directive->attr->load)){
            $cache .= "\n\t<script type='text/javascript'>\n\t";
        }

        $cache .= implode("\n\t", $evt->trigger($this->directive->attr->load));

        if($this->directive->attr->load == 'definer' && $evt->count($this->directive->attr->load)){
            $cache .= "\n\t</script>";
        }
        return $cache;
    }
}
