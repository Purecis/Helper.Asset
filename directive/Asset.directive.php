<?php
namespace App\Helper\Asset\Directive;

use App\System\Directive;
use App\System\Response;
use App\System\Event;
use App\System\Str;
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

        $events = explode(',', $this->directive->attr->load);
        $cache = '';

        foreach($events as $event){
            $temp = "";
            $temp .= implode("\n\t", $evt->trigger($event));

            if($event == 'definer' && $evt->count($event)){
                $cache .= "\n\t<script type='text/javascript'>\n\t" . $temp . "\n\t</script>";
            }else{
                $cache .= $temp;
            }
        }
        
        return $cache;
    }
}
