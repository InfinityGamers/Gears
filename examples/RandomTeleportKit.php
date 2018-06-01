<?php

use Gears\Kit\Kit;
use pocketmine\item\Item;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Player;
class RandomTeleportKit extends Kit{
        public function __construct(){
                $kitName = "RandomTeleportKit";
                $specialItem = Item::get(345, 0, 1); //compass
                $items = [
                    Item::get(276, 0, 1) // diamond sword
                ]; // generic items
                $clickMode = Kit::ALL_CLICK_MODE; // check all click modes in the Kit class
                $coolDown = 30; // in seconds
                $deactivateTime = -1; // in seconds, not needed in this case

                parent::__construct($kitName, $specialItem, $items, $clickMode, $coolDown, $deactivateTime);
        }

        public function onUseSpecialItem(array $data){
                $player = $data['Player'];
                $item = $data['Item'];

                if(($player instanceof Player) and ($item instanceof Item)){
                        if(!$item->hasCustomBlockData()) return false;

                        /** @var CompoundTag $data */
                        $data = $item->getCustomBlockData();

                        if(!$data->hasTag("kit_name")) return false;
                        if(strtolower($data->getString("kit_name")) === "randomteleportkit"){
                                $randomX = mt_rand(0, 1000);
                                $randomY = mt_rand(0, 256);
                                $randomZ = mt_rand(0, 1000);

                                $v3 = new Vector3($randomX, $randomY, $randomZ);

                                $safeSpawn = $player->level->getSafeSpawn($v3);

                                $player->teleport($safeSpawn);
                        }
                }

                return true;
        }
}