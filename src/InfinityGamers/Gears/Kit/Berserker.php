<?php
namespace InfinityGamers\Gears\Kit;
use InfinityGamers\Gears\Task\BerserkerTask;
use pocketmine\item\Item;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;
class Berserker extends Kit{

        /** @var bool */
        public $activated = false;

        /**
         *
         * Berserker constructor.
         *
         * @param Item  $specialItem
         * @param array $items
         * @param int   $coolDown
         * @param int   $deactivate
         *
         */
        public function __construct(Item $specialItem, array $items = [], int $coolDown, int $deactivate = -1){
                parent::__construct("Berserker", $specialItem, $items, Kit::RIGHT_CLICK_MODE, $coolDown, $deactivate);
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

                        if(strtolower($data->getString("kit_name")) === "berserker"){
                                if($this->checkCoolDown($player)){
                                        $player->setScale(2);
                                        $player->level->broadcastLevelSoundEvent($player, LevelSoundEventPacket::SOUND_GROWL);
                                        $this->getGearsInstance()->getScheduler()->scheduleRepeatingTask(new BerserkerTask($this->getGearsInstance(), $player, $this->deactivate), 20);
                                }
                        }
                }
                return false;
        }
}