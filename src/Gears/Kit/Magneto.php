<?php
namespace Gears\Kit;
use pocketmine\item\Item;
use pocketmine\math\AxisAlignedBB;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;

class Magneto extends Kit{
        /**
         *
         * Thor constructor.
         *
         * @param Item  $specialItem
         * @param array $items
         * @param int   $coolDown
         * @param int   $deactivate
         *
         */
        public function __construct(Item $specialItem, array $items = [], int $coolDown, int $deactivate = -1){
                parent::__construct("Magneto", $specialItem, $items, Kit::RIGHT_CLICK_MODE, $coolDown, $deactivate);
        }

        /**
         *
         * @param array $data
         *
         * @return bool
         *
         */
        public function onUseSpecialItem($data){
                $player = $data['Player'];
                $item = $data['Item'];

                if(($player instanceof Player) and ($item instanceof Item)){
                        if(!$item->hasCustomBlockData()) return false;

                        /** @var CompoundTag $data */
                        $data = $item->getCustomBlockData();

                        if(!$data->hasTag("kit_name")) return false;

                        if(strtolower($data->getString("kit_name")) === "magneto"){
                                if($this->checkCoolDown($player)){

                                        $p = $player;

                                        $player->level->broadcastLevelSoundEvent($p, LevelSoundEventPacket::SOUND_SPLASH);

                                        foreach($player->getLevel()->getNearbyEntities(
                                            new AxisAlignedBB($p->x - 10, $p->y - 10, $p->z - 10, $p->x + 10, $p->y + 10, $p->z + 10), $player) as $ent){
                                                $ent->teleport($player);
                                                $motion = $ent->getDirectionVector();
                                                $motion->multiply(-2);
                                                $motion->y = 1.5;
                                                $ent->setMotion($motion);
                                        }

                                        return true;
                                }
                        }
                }
                return false;
        }
}