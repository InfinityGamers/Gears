<?php
namespace Gears\Kit;
use pocketmine\math\AxisAlignedBB;
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
         * @param Player $player
         *
         * @return \pocketmine\entity\Entity
         *
         */
        public function getEntityInView(Player $player){
                $pointA = $player->subtract(100, 100, 100);
                $pointB = $player->add(100, 100, 100);

                $distance = new AxisAlignedBB($pointA->x, $pointA->y, $pointA->z, $pointB->x, $pointB->y, $pointB->z);
                foreach($player->level->getNearbyEntities($distance, $player) as $entity){

                        $toEntity = $entity->asVector3()->subtract($player->asVector3());
                        $direction = $player->getDirectionVector();
                        $exact = $toEntity->normalize()->dot($direction);

                        if($exact >= 0.999){
                                return $entity;
                        }
                }

                return null;
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

                                $entity = $this->getEntityInView($player);

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