<?php
namespace InfinityGamers\Gears\Kit;
use pocketmine\block\Block;
use pocketmine\block\Water;
use pocketmine\item\Item;
use pocketmine\Player;
class Iceman extends Kit{

        /** @var array */
        protected $processedBlocks = [];

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
                parent::__construct("Iceman", $specialItem, $items, Kit::MOVE_PLAYER_MODE, $coolDown, $deactivate = -1);
        }

        /**
         *
         * @param Player $player
         *
         */
        public function onUnload(Player $player){
                foreach($this->processedBlocks as $index => $block){
                        $block->getLevel()->setBlock($block, $block);
                        unset($this->processedBlocks[$index]);
                }
        }

        /**
         *
         * @param Player $player
         *
         */
        public function onDeath(Player $player){
                foreach($this->processedBlocks as $index => $block){
                        $block->getLevel()->setBlock($block, $block);
                        unset($this->processedBlocks[$index]);
                }
        }

        /**
         *
         * @param Player $player
         *
         */
        public function onQuit(Player $player){
                foreach($this->processedBlocks as $index => $block){
                        $block->getLevel()->setBlock($block, $block);
                        unset($this->processedBlocks[$index]);
                }
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
                if(($player instanceof Player)){
                        foreach($this->processedBlocks as $index => $block){
                                $block->getLevel()->setBlock($block, $block);
                                unset($this->processedBlocks[$index]);
                        }
                        for($x = -1; $x <= 1; ++$x){
                                for($z = -1; $z <= 1; ++$z){
                                        if($player->isSneaking()){
                                                if(($block = $player->level->getBlock($player->add($x, 1, $z))) instanceof Water){
                                                        $this->processedBlocks[] = $player->getLevel()->getBlock($player->add($x, 1, $z));
                                                        $player->getLevel()->setBlock($player->add($x, 1, $z), Block::get(Block::ICE));
                                                        continue;
                                                }
                                                if(($block = $player->level->getBlock($player->add($x, -1, $z))) instanceof Water){
                                                        $this->processedBlocks[] = $player->getLevel()->getBlock($player->add($x, -1, $z));
                                                        $player->getLevel()->setBlock($player->add($x, -1, $z), Block::get(Block::ICE));
                                                        continue;
                                                }
                                        }else{
                                                if(($block = $player->level->getBlock($player->add($x, -1, $z))) instanceof Water){
                                                        $this->processedBlocks[] = $player->getLevel()->getBlock($player->add($x, -1, $z));
                                                        $player->getLevel()->setBlock($player->add($x, -1, $z), Block::get(Block::ICE));
                                                        continue;
                                                }
                                                if(($block = $player->level->getBlock($player->add($x, -2, $z))) instanceof Water){
                                                        $this->processedBlocks[] = $player->getLevel()->getBlock($player->add($x, -2, $z));
                                                        $player->getLevel()->setBlock($player->add($x, -2, $z), Block::get(Block::ICE));
                                                        continue;
                                                }
                                        }
                                }
                        }

                }

                return false;
        }
}