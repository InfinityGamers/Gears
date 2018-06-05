<?php
namespace InfinityGamers\Gears\Storage;
use InfinityGamers\Gears\Kit\Kit;
use InfinityGamers\Gears\Utils\PlayerUtils;
use pocketmine\Player;
class Storage{
        /**
         *
         * @var Kit[]
         *
         */
        public $players = [];

        /**
         *
         * @param Player $p
         * @param        $kit
         *
         */
        public function setKit(Player $p, $kit){
                $this->players[PlayerUtils::PlayerHash($p)] = $kit;
        }

        /**
         *
         * @param Player $p
         * @param        $kit
         *
         */
        public function setKitEnabled(Player $p, $kit){
                if(!isset($this->players[PlayerUtils::PlayerHash($p)])){
                        $this->players[PlayerUtils::PlayerHash($p)] = $kit;
                }
        }

        /**
         *
         * @param Player $p
         *
         */
        public function setKitDisabled(Player $p){
                if(isset($this->players[PlayerUtils::PlayerHash($p)])){
                        unset($this->players[PlayerUtils::PlayerHash($p)]);
                }
        }

        /**
         *
         * @param Player $p
         *
         * @return Kit|null
         *
         *
         */
        public function getPlayerKit(Player $p){
                if($this->isKitEnabled($p)) return $this->players[PlayerUtils::PlayerHash($p)];
                return null;
        }

        /**
         *
         * @param Player $p
         *
         * @return bool
         *
         */
        public function isKitEnabled(Player $p){
                return isset($this->players[PlayerUtils::PlayerHash($p)]);
        }
}