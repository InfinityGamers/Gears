<?php
namespace Gears\Task;

use Gears\Gears;
use pocketmine\entity\Entity;
use pocketmine\scheduler\PluginTask;

class ScorpioTask extends PluginTask{

        protected $seconds = 15;
        /** @var Entity */
        protected $entity;

        public function __construct(Gears $core, Entity $entity, int $seconds){
                parent::__construct($core);
                $this->entity = $entity;
                $this->seconds = $seconds;
        }

        public function onRun(int $currentTick){

                if($this->seconds <= 0){
                        $this->getOwner()->getServer()->getScheduler()->cancelTask($this->getTaskId());
                        $this->entity->close();
                        return;
                }

                --$this->seconds;
        }
}