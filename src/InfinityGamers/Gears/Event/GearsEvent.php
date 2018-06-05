<?php
namespace InfinityGamers\Gears\Event;
use InfinityGamers\Gears\Gears;
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