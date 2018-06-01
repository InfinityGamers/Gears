<?php
namespace Gears\Kit;
use Gears\Gears;
use pocketmine\block\Block;
use pocketmine\entity\Entity;
use pocketmine\item\Item;
use pocketmine\level\Level;
use pocketmine\math\AxisAlignedBB;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\AddEntityPacket;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;
use pocketmine\scheduler\PluginTask;
use pocketmine\Server;

class Thor extends Kit{
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
                parent::__construct("Thor", $specialItem, $items, Kit::RIGHT_CLICK_MODE, $coolDown, $deactivate);
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

                        if(strtolower($data->getString("kit_name")) === "thor"){
                                if($this->checkCoolDown($player)){
                                        $p = $player->getTargetBlock(100);
                                        $light = new AddEntityPacket();
                                        $light->type = 93;
                                        $light->entityRuntimeId = Entity::$entityCount++;
                                        $light->metadata = array();
                                        $light->attributes = [];
                                        $light->position = $p;

                                        $p = $p->asPosition();
                                        $v3 = new Vector3($p->x, $p->y, $p->z);

                                        $player->level->broadcastLevelSoundEvent($v3, LevelSoundEventPacket::SOUND_THUNDER, 93);
                                        Server::getInstance()->broadcastPacket($player->getLevel()->getPlayers(), $light);

                                        foreach($player->getLevel()->getNearbyEntities(
                                            new AxisAlignedBB($p->x - 5, $p->y - 5, $p->z - 5, $p->x + 5, $p->y + 5, $p->z + 5), $player) as $ent){
                                                $ent->setHealth($ent->getHealth() - 1);
                                                $ent->setOnFire(15);
                                        }

                                        $blocks = [];

                                        for($x = -5; $x <= 5; ++$x){
                                                for($z = -5; $z <= 5; ++$z){
                                                        $blocks[] = $p->getLevel()->getBlock($p->add($x, 0, $z));
                                                        $blocks[] = $p->getLevel()->getBlock($p->add($x, 1, $z));
                                                        $p->getLevel()->setBlock($p->add($x, 0, $z), Block::get(Block::NETHERRACK));
                                                        $p->getLevel()->setBlock($p->add($x, 1, $z), Block::get(Block::FIRE));
                                                }
                                        }

                                        $player->getServer()->getScheduler()->scheduleRepeatingTask(new ThorTask($this->getGearsInstance(), $p->level, $blocks, $this->deactivate), 20);

                                        return true;
                                }
                        }
                }
                return false;
        }
}

class ThorTask extends PluginTask{

        protected $seconds = 15;

        /** @var Level */
        protected $level;
        /** @var Block[] */
        protected $blocks = [];

        public function __construct(Gears $core, Level $level, array $blocks, int $seconds){
                parent::__construct($core);
                $this->level = $level;
                $this->blocks = $blocks;
                $this->seconds = $seconds;
        }

        public function onRun(int $currentTick){

                if(count($this->blocks) <= 0){
                        $this->getOwner()->getServer()->getScheduler()->cancelTask($this->getTaskId());
                        return;
                }

                if($this->seconds <= 10){
                        for($i = 0; $i <= 10; ++$i){
                                $top = array_shift($this->blocks);
                                $this->level->setBlock($top, $top);

                                if(count($this->blocks) >= 1){
                                        $bottom = array_shift($this->blocks);
                                        $this->level->setBlock($bottom, $bottom);
                                }
                        }
                }

                --$this->seconds;
        }
}