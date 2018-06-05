<?php
namespace InfinityGamers\Gears\Utils;
use pocketmine\Player;
class PlayerUtils{
        /**
         *
         * @param Player $p
         *
         * @return string
         *
         */
        public static function PlayerHash(Player $p){
                return $p->getXuid();
        }
}