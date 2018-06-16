<?php
namespace InfinityGamers\Gears\Event;
use InfinityGamers\Gears\Gears;
use InfinityGamers\Gears\Kit\Kit;
use pocketmine\event\Cancellable;
use pocketmine\Player;
class PlayerUnloadKitEvent extends GearsEvent implements Cancellable{
        public static $handlerList = null;

        /** @var Player */
        public $player;
        /** @var Kit */
        public $kit;

        /**
         *
         * PlayerActivateKitEvent constructor.
         *
         * @param Gears  $plugin
         * @param Player $player
         * @param Kit    $kit
         *
         */
        public function __construct(Gears $plugin, Player $player, Kit $kit){
                parent::__construct($plugin);
                $this->player = $player;
                $this->kit = $kit;
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
}