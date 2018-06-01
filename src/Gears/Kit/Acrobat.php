<?php
namespace Gears\Kit;

use Gears\Gears;
use pocketmine\item\Item;
use pocketmine\level\Position;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\network\mcpe\protocol\LevelSoundEventPacket;
use pocketmine\Player;
use pocketmine\scheduler\PluginTask;

class Acrobat extends Kit{
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
                parent::__construct("Acrobat", $specialItem, $items, Kit::ALL_CLICK_MODE, $coolDown, $deactivate = -1);
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

                        if(strtolower($data->getString("kit_name")) === "acrobat"){
                                if($this->checkCoolDown($player)){

                                        $this->getGearsInstance()->getServer()->getScheduler()->scheduleRepeatingTask(
                                            new AcrobatTask($this->getGearsInstance(), $player, $player->asPosition(), $this->coolDown), 20);

                                        $motion = $player->getDirectionVector();
                                        $motion->x = $motion->x * 2.5;
                                        $motion->y = 1.5;
                                        $motion->z = $motion->z * 2.5;
                                        $player->setMotion($motion);
                                        $player->level->broadcastLevelSoundEvent($player, LevelSoundEventPacket::SOUND_BOW);
                                        return true;
                                }
                        }

                }

                return false;
        }
}

class AcrobatTask extends PluginTask{

        /** @var Gears */
        protected $core;

        /** @var Player */
        protected $player;

        /** @var Position */
        protected $usedAt = null;

        /** @var int */
        protected $coolDown = 0;

        /**
         *
         * AcrobatTask constructor.
         *
         * @param Gears    $owner
         * @param Player   $player
         * @param Position $usedAt
         * @param int      $coolDown
         *
         */
        public function __construct(Gears $owner, Player $player, Position $usedAt, int $coolDown){
                parent::__construct($owner);
                $this->core = $owner;
                $this->player = $player;
                $this->usedAt = $usedAt;
                $this->coolDown = $coolDown;
        }

        /**
         *
         * @param int $currentTick
         *
         */
        public function onRun(int $currentTick){

                if($this->coolDown <= 0 || !$this->core->getVault()->isKitEnabled($this->player) || !$this->player->isOnline()){
                        $this->core->getServer()->getScheduler()->cancelTask($this->getTaskId());
                        return;
                }

                --$this->coolDown;

        }

}