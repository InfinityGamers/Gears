<?php

namespace InfinityGamers\Gears\Task;

use InfinityGamers\Gears\Gears;
use InfinityGamers\Gears\Lang\Translator;
use InfinityGamers\Gears\Utils\RandomUtils;
use InfinityGamers\Gears\Kit\Kit;
use pocketmine\Player;
use pocketmine\scheduler\Task;

class CoolDownResetTask extends Task{

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
                        $kit = $this->core->getStorage()->getPlayerKit($this->player);
                        if($kit instanceof Kit){
                                $kit->setAbilityActive(false);
                                $this->core->getStorage()->setKit($this->player, $kit);
                        }
                        $this->core->getScheduler()->cancelTask($this->getTaskId());
                        $message = Translator::getMessage('can_use_again');
                        $this->player->sendPopup(RandomUtils::colorMessage($message));
                        return;
                }

                if($this->core->getStorage()->isKitEnabled($this->player)){
                        $message = Translator::getMessage('time_till_next_use');
                        $message = str_replace("@seconds", $this->seconds, $message);
                        $this->player->sendPopup(RandomUtils::colorMessage($message));
                        --$this->seconds;
                }else{
                        $this->core->getScheduler()->cancelTask($this->getTaskId());
                }
        }
}