<?php
namespace InfinityGamers\Gears\Kit;
use InfinityGamers\Gears\Task\SpiderTask;
use pocketmine\block\Block;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\item\Item;
use pocketmine\math\AxisAlignedBB;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Player;
class Spider extends Kit{
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
                parent::__construct("Spider", $specialItem, $items, Kit::RIGHT_CLICK_MODE, $coolDown, $deactivate);
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
                $block = $data['Block'];
                $item = $data['Item'];

                if(($player instanceof Player) and ($item instanceof Item) and ($block instanceof Block)){
                        if(!$item->hasCustomBlockData()) return false;

                        /** @var CompoundTag $data */
                        $data = $item->getCustomBlockData();

                        if(!$data->hasTag("kit_name")) return false;

                        if(strtolower($data->getString("kit_name")) === "spider"){
                                if($this->checkCoolDown($player)){
                                        $p = $player->getTargetBlock(100);

                                        $player->setCanClimbWalls(true);

                                        foreach($player->getLevel()->getNearbyEntities(
                                            new AxisAlignedBB($p->x - 5, $p->y - 5, $p->z - 5, $p->x + 5, $p->y + 5, $p->z + 5), $player) as $ent){
                                                if($ent instanceof Player){
                                                        $ent->addEffect((new EffectInstance(Effect::getEffect(Effect::POISON)))->setDuration($this->deactivate * 20));
                                                }
                                        }

                                        $blocks = [];

                                        for($x = -5; $x <= 5; ++$x){
                                                for($z = -5; $z <= 5; ++$z){
                                                        $blocks[] = $p->getLevel()->getBlock($p->add($x, 0, $z));
                                                        $blocks[] = $p->getLevel()->getBlock($p->add($x, 1, $z));
                                                        $p->getLevel()->setBlock($p->add($x, 0, $z), Block::get(Block::COBWEB));
                                                        $p->getLevel()->setBlock($p->add($x, 1, $z), Block::get(Block::COBWEB));
                                                }
                                        }
                                        $player->getServer()->getScheduler()->scheduleRepeatingTask(new SpiderTask($this->getGearsInstance(), $player, $p->level, $blocks, $this->deactivate), 20);

                                        return true;
                                }
                        }
                }
                return false;
        }
}