<?php
namespace Gears\Event;
use Gears\Gears;
use pocketmine\event\plugin\PluginEvent;
abstract class GearsEvent extends PluginEvent{
        /**
         *
         * @param Gears $plugin
         *
         */
        public function __construct(Gears $plugin){
                parent::__construct($plugin);
        }
}