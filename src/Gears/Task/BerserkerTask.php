<?php

namespace Gears\Task;

use Gears\Gears;
use pocketmine\Player;
use pocketmine\scheduler\PluginTask;

class BerserkerTask extends PluginTask{

        protected $seconds = 15;

        /** @var Player */
        protected $player;

        public function __construct(Gears $core, Player $player, int $seconds){
                parent::__construct($core);
                $this->player = $player;
                $this->seconds = $seconds;
        }

        public function onRun(int $currentTick){
                if($this->seconds <= 0){
                        $this->player->setScale(1);
                        $this->getOwner()->getServer()->getScheduler()->cancelTask($this->getTaskId());
                        return;
                }
                --$this->seconds;
        }
}