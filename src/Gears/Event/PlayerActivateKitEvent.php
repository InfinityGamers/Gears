<?php
namespace Gears\Event;
use Gears\Gears;
use Gears\Kit\Kit;
use pocketmine\event\Cancellable;
use pocketmine\Player;

class PlayerActivateKitEvent extends GearsEvent implements Cancellable{
        public static $handlerList = null;

        /** @var Player */
        public $player;
        /** @var Kit */
        public $kit;
        /** @var int */
        public $clickMode;

        /**
         *
         * PlayerActivateKitEvent constructor.
         *
         * @param Gears  $plugin
         * @param Player $player
         * @param Kit    $kit
         * @param int    $clickMode
         *
         */
        public function __construct(Gears $plugin, Player $player, Kit $kit, int $clickMode){
                parent::__construct($plugin);
                $this->player = $player;
                $this->kit = $kit;
                $this->clickMode = $clickMode;
        }

        /**
         *
         * @return Player
         *
         */
        public function getPlayer(): Player{
                return $this->player;
        }

        /**
         *
         * @return Kit
         *
         */
        public function getKit(): Kit{
                return $this->kit;
        }

        /**
         *
         * @return int
         *
         */
        public function getClickMode(): int{
                return $this->clickMode;
        }

}