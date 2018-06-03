<?php
namespace Gears\Task;


use Gears\Gears;
use pocketmine\level\Position;
use pocketmine\Player;
use pocketmine\scheduler\PluginTask;

class AcrobatTask extends PluginTask{

        /** @var Gears */
        protected $core;

        /** @var Player */
        protected $player;

        /** @var Position */
        protected $usedAt = null;

        /** @var int */
        protected $coolDown = 0;

        /**
         *
         * AcrobatTask constructor.
         *
         * @param Gears    $owner
         * @param Player   $player
         * @param Position $usedAt
         * @param int      $coolDown
         *
         */
        public function __construct(Gears $owner, Player $player, Position $usedAt, int $coolDown){
                parent::__construct($owner);
                $this->core = $owner;
                $this->player = $player;
                $this->usedAt = $usedAt;
                $this->coolDown = $coolDown;
        }

        /**
         *
         * @param int $currentTick
         *
         */
        public function onRun(int $currentTick){

                if($this->coolDown <= 0 || !$this->core->getVault()->isKitEnabled($this->player) || !$this->player->isOnline()){
                        $this->core->getServer()->getScheduler()->cancelTask($this->getTaskId());
                        return;
                }

                --$this->coolDown;

        }

}