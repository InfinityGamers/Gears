<?php
namespace InfinityGamers\Gears\Task;

use InfinityGamers\Gears\Gears;
use pocketmine\entity\Entity;
use pocketmine\scheduler\Task;

class ScorpioTask extends Task{

        protected $seconds = 15;
        /** @var Gears */
        protected $core;
        /** @var Entity */
        protected $entity;

        public function __construct(Gears $core, Entity $entity, int $seconds){
                $this->core = $core;
                $this->entity = $entity;
                $this->seconds = $seconds;
        }

        public function onRun(int $currentTick){

                if($this->seconds <= 0){
                        $this->core->getScheduler()->cancelTask($this->getTaskId());
                        $this->entity->close();
                        return;
                }

                --$this->seconds;
        }
}