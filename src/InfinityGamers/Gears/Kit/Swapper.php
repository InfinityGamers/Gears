<?php
namespace InfinityGamers\Gears\Kit;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\item\Item;
use pocketmine\Player;
class Swapper extends Kit{

        /**
         *
         * Swapper constructor.
         *
         * @param Item  $specialItem
         * @param array $items
         * @param int   $coolDown
         * @param int   $deactivate
         *
         */
        public function __construct(Item $specialItem, array $items = [], int $coolDown, int $deactivate = -1){
                parent::__construct("Swapper", $specialItem, $items, Kit::RIGHT_CLICK_MODE, $coolDown, $deactivate);
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
                        if(strtolower($data->getString("kit_name")) === "swapper"){
                                $entity = $this->getTargetEntity($player);
                                if($entity instanceof Player){
                                        if($this->checkCoolDown($player)){
                                                $playerPos = $player->getPosition();
                                                $player->teleport($entity);
                                                $entity->teleport($playerPos);
                                                $player->level->broadcastLevelSoundEvent($player, LevelSoundEventPacket::SOUND_TELEPORT);
                                                $player->level->broadcastLevelSoundEvent($entity, LevelSoundEventPacket::SOUND_TELEPORT);
                                                return true;
                                        }
                                }
                        }
                }
                return false;
        }
}