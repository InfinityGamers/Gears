<?php

namespace InfinityGamers\Gears;

use InfinityGamers\Gears\Kit\Acrobat;
use InfinityGamers\Gears\Kit\Berserker;
use InfinityGamers\Gears\Kit\Kit;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\inventory\InventoryPickupItemEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\inventory\PlayerInventory;
use pocketmine\level\sound\DoorSound;
use pocketmine\Player;

class EventListener implements Listener{

        /** @var Gears */
        protected $core;

        /**
         *
         * KitCommand constructor.
         *
         * @param Gears $core
         *
         */
        public function __construct(Gears $core){;
                $this->core = $core;;
        }

        /**
         *
         * @param InventoryPickupItemEvent $event
         *
         */
        public function pickup(InventoryPickupItemEvent $event){
                $inv = $event->getInventory();
                $item = $event->getItem();
                if($inv instanceof PlayerInventory){
                        $player = $inv->getHolder();
                        $data = $item->namedtag;
                        if($data->hasTag("scorpio")){
                                $launcher = $data->getString("launcher");
                                $thrower = $this->core->getServer()->getPlayerExact($launcher);

                                if($thrower instanceof Player){

                                        if($player === $thrower){
                                                $event->setCancelled();
                                                return;
                                        }

                                        $player->teleport($thrower);
                                        $player->level->addSound(new DoorSound($player));
                                }
                                $item->close();
                                $event->setCancelled();
                        }
                }
        }

        /**
         *
         * @param PlayerMoveEvent $e
         *
         */
        public function move(PlayerMoveEvent $e){
                $this->core->getKitManager()->callEvent([
                    'Player' => $e->getPlayer(),
                ], Kit::MOVE_PLAYER_MODE);
        }

        /**
         *
         * @param PlayerInteractEvent $e
         *
         */
        public function interact(PlayerInteractEvent $e){

                $player = $e->getPlayer();

                if($this->core->getStorage()->isKitEnabled($player)){
                        $this->core->getKitManager()->callEvent([
                            'Player' => $e->getPlayer(),
                            'Item' => $e->getItem(),
                            'Block' => $e->getBlock()
                        ], Kit::ALL_CLICK_MODE);
                        if($e->getAction() == PlayerInteractEvent::LEFT_CLICK_AIR
                            or $e->getAction() == PlayerInteractEvent::LEFT_CLICK_BLOCK){
                                $this->core->getKitManager()->callEvent([
                                    'Player' => $e->getPlayer(),
                                    'Item' => $e->getItem(),
                                    'Block' => $e->getBlock()
                                ], Kit::LEFT_CLICK_MODE);
                        }elseif($e->getAction() == PlayerInteractEvent::RIGHT_CLICK_AIR
                            or $e->getAction() == PlayerInteractEvent::RIGHT_CLICK_BLOCK){
                                $this->core->getKitManager()->callEvent([
                                    'Player' => $e->getPlayer(),
                                    'Item' => $e->getItem(),
                                    'Block' => $e->getBlock()
                                ], Kit::RIGHT_CLICK_MODE);
                        }
                }
        }

        /**
         *
         * @param EntityDamageEvent $e
         *
         *
         */
        public function damage(EntityDamageEvent $e){

                $target = $e->getEntity();

                if($target instanceof Player){
                        $kit = $this->core->getStorage()->getPlayerKit($target);
                        if($kit instanceof Acrobat && $kit->isAbilityActive() && $e->getCause() === EntityDamageEvent::CAUSE_FALL){
                                $e->setCancelled();
                        }
                }

                if($e instanceof EntityDamageByEntityEvent){
                        $cause = $e->getDamager();
                        if($cause instanceof Player and $target instanceof Player){
                                if($this->core->getStorage()->isKitEnabled($cause)){
                                        $this->core->getKitManager()->callEvent([
                                            'Player' => $cause,
                                            'Target' => $target,
                                            'Item' => $cause->getInventory()->getItemInHand()
                                        ],
                                            Kit::HIT_PLAYER_MODE);

                                        $ability = $this->core->getStorage()->getPlayerKit($cause);

                                        if($ability instanceof Berserker && $ability->isAbilityActive()){
                                                $e->setBaseDamage($e->getBaseDamage() * 2);
                                        }
                                }
                        }
                }
        }

        /**
         *
         * @param PlayerDeathEvent $e
         *
         */
        public function death(PlayerDeathEvent $e){
                $player = $e->getPlayer();

                if($this->core->getKitManager()->unloadKit($player)){
                        $this->core->getStorage()->getPlayerKit($player)->onDeath($player);
                }
        }

        /**
         *
         * @param PlayerQuitEvent $e
         *
         */
        public function quit(PlayerQuitEvent $e){
                $player = $e->getPlayer();
                if($this->core->getStorage()->isKitEnabled($player)){
                        $this->core->getStorage()->getPlayerKit($player)->onQuit($player);
                }
        }
}