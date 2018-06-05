<?php
namespace InfinityGamers\Gears\Kit;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\item\Item;
use pocketmine\level\sound\FizzSound;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Player;
class Bartender extends Kit{
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
                parent::__construct("Bartender", $specialItem, $items, Kit::HIT_PLAYER_MODE, $coolDown, $deactivate);
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
                $target = $data['Target'];
                $item = $data['Item'];

                if(($player instanceof Player) and ($target instanceof Player) and ($item instanceof Item)){
                        if(!$item->hasCustomBlockData()) return false;

                        /** @var CompoundTag $data */
                        $data = $item->getCustomBlockData();

                        if(!$data->hasTag("kit_name")) return false;

                        if(strtolower($data->getString("kit_name")) === "bartender"){
                                if($this->checkCoolDown($player)){

                                        $effect = (new EffectInstance(Effect::getEffect(Effect::NAUSEA)))->setAmplifier(50)->setDuration($this->deactivate);
                                        $target->addEffect($effect);

                                        $player->level->addSound(new FizzSound($target));

                                        return true;
                                }
                        }

                }

                return false;
        }
}