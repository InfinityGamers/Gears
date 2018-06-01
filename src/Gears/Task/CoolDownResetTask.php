<?php

namespace Gears\Task;

use Gears\Gears;
use Gears\Utils\RandomUtils;
use pocketmine\Player;
use pocketmine\scheduler\PluginTask;
use PrestigeSociety\Kits\Special\Kit\Kit;

class CoolDownResetTask extends PluginTask{

        /** @var Gears */
        protected $core;
        /** @var Player */
        protected $player;
        /** @var int $seconds */
        protected $seconds;

        /**
         *
         * CoolDownResetTask constructor.
         *
         * @param Gears  $owner
         * @param Player $player
         * @param int    $seconds
         *
         */
        public function __construct(Gears $owner, Player $player, int $seconds){
                parent::__construct($owner);
                $this->core = $owner;
                $this->player = $player;
                $this->seconds = $seconds;
        }


        /**
         *
         * Actions to execute when run
         *
         * @param int $currentTick
         *
         * @return void
         *
         */
        public function onRun(int $currentTick){
                if($this->seconds <= 0){
                        $kit = $this->core->getVault()->getPlayerKit($this->player);
                        if($kit instanceof Kit){
                                $kit->setAbilityActive(false);
                                $this->core->getVault()->setKit($this->player, $kit);
                        }
                        $this->getOwner()->getServer()->getScheduler()->cancelTask($this->getTaskId());
                        $message = $this->core->getMessage('can_use_again');
                        $this->player->sendPopup(RandomUtils::colorMessage($message));
                        return;
                }

                if($this->core->getVault()->isKitEnabled($this->player)){
                        $message = $this->core->getMessage('time_till_next_use');
                        $message = str_replace("@seconds", $this->seconds, $message);
                        $this->player->sendPopup(RandomUtils::colorMessage($message));
                        --$this->seconds;
                }else{
                        $this->core->getServer()->getScheduler()->cancelTask($this->getTaskId());
                }
        }
}