<?php

namespace InfinityGamers\Gears\Task;

use InfinityGamers\Gears\Gears;
use pocketmine\Player;
use pocketmine\scheduler\Task;

class BerserkerTask extends Task{

        protected $seconds = 15;
        /** @var Gears */
        protected $core;
        /** @var Player */
        protected $player;

        public function __construct(Gears $core, Player $player, int $seconds){
                $this->core = $core;
                $this->player = $player;
                $this->seconds = $seconds;
        }

        public function onRun(int $currentTick){
                if($this->seconds <= 0){
                        $this->player->setScale(1);
                        $this->core->getScheduler()->cancelTask($this->getTaskId());
                        return;
                }
                --$this->seconds;
        }
}