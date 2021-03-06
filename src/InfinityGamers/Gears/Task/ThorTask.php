<?php
namespace InfinityGamers\Gears\Task;

use InfinityGamers\Gears\Gears;
use pocketmine\block\Block;
use pocketmine\level\Level;
use pocketmine\scheduler\Task;

class ThorTask extends Task{

        protected $seconds = 15;

        /** @var Gears */
        protected $core;
        /** @var Level */
        protected $level;
        /** @var Block[] */
        protected $blocks = [];

        public function __construct(Gears $core, Level $level, array $blocks, int $seconds){
                $this->core = $core;
                $this->level = $level;
                $this->blocks = $blocks;
                $this->seconds = $seconds;
        }

        public function onRun(int $currentTick){

                if(count($this->blocks) <= 0){
                        $this->core->getScheduler()->cancelTask($this->getTaskId());
                        return;
                }

                if($this->seconds <= 10){
                        for($i = 0; $i <= 10; ++$i){
                                $top = array_shift($this->blocks);
                                $this->level->setBlock($top, $top);

                                if(count($this->blocks) >= 1){
                                        $bottom = array_shift($this->blocks);
                                        $this->level->setBlock($bottom, $bottom);
                                }
                        }
                }

                --$this->seconds;
        }
}