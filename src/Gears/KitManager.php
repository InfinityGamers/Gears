<?php
namespace Gears;
use Gears\Event\PlayerActivateKitEvent;
use Gears\Kit\Kit;
use pocketmine\Player;

class KitManager{
        /**
         * @var Kit[]
         */
        protected $KITS = [];

        /**
         * @var Gears
         */
        private $gears;

        public function __construct(Gears $gears){
                $this->gears = $gears;
        }

        /**
         *
         * @param $kit
         *
         * @return bool
         *
         */
        public function isKitRegistered($kit){
                if(is_object($kit)){
                        if(($kit instanceof Kit)){
                                return isset($this->KITS[$kit->getName()]);
                        }
                }elseif(is_string($kit)){
                        return isset($this->KITS[$kit]);
                }
                return false;
        }

        /**
         *
         * @param array $kits
         *
         */
        public function registerKits($kits){
                foreach($kits as $kit){
                        $this->registerKit($kit);
                }
        }

        /**
         *
         * @param $kit
         *
         * @return bool
         *
         */
        public function registerKit($kit){
                if(($kit instanceof Kit) && !($this->isKitRegistered($kit))){
                        $kit->setGearsInstance($this->gears);
                        $this->KITS[$kit->getName()] = $kit;
                        return true;
                }
                return false;
        }

        /**
         *
         * @return bool
         *
         */
        public function unRegisterAllKits(){
                $this->KITS = [];
                return true;
        }

        /**
         *
         * @param $kit
         *
         * @return bool
         *
         */
        public function unRegisterKit($kit){
                if($this->isKitRegistered($kit)){
                        unset($this->KITS[$kit]);
                        return true;
                }
                return false;
        }

        /**
         *
         * @return Kit[]
         *
         */
        public function getAllKits(){
                return $this->KITS;
        }

        /**
         *
         * @param Player $player
         *
         * @return Kit[]
         *
         */
        public function getAllKitsAvailableToPlayer(Player $player){
                $kits = [];

                foreach($this->KITS as $kit){
                        if($player->hasPermission($kit->getPermissionNode())){
                                $kits[] = $kit;
                        }
                }

                return $kits;
        }

        /**
         *
         * @return string[]
         *
         */
        public function getAllKitNames(){
                $kits = [];

                foreach($this->KITS as $kit){
                        $kits[] = $kit->getName();
                }

                return $kits;
        }

        /**
         *
         * @param Player $player
         *
         * @return string[]
         *
         */
        public function getAllKitsAvailableToPlayerNames(Player $player){
                $kits = [];

                foreach($this->getAllKitsAvailableToPlayer($player) as $kit){
                        $kits[] = $kit->getName();
                }

                return $kits;
        }

        /**
         *
         * @param $name
         *
         * @return Kit|null
         *
         */
        public function getKitByName($name){
                if($this->isKitRegistered($name)){
                        return $this->KITS[$name];
                }
                return null;
        }

        /**
         *
         * @param $name
         *
         * @return array|\pocketmine\item\Item[]
         *
         *
         */
        public function getKitItems($name){
                $kit = $this->getKitByName($name);

                if($kit instanceof Kit){

                        $contents = $kit->getItems();
                        $contents[] = $kit->getSpecialItem();

                        return $contents;
                }

                return null;
        }

        /**
         *
         * @param Player $p
         * @param        $name
         *
         * @return bool
         *
         */
        public function loadKit(Player $p, $name){
                if($this->gears->getVault()->isKitEnabled($p)) return 3;
                if($p->getInventory()->firstEmpty() === -1 || $p->getArmorInventory()->firstEmpty() === -1) return 2;

                $p->removeAllEffects();

                $contents = $this->getKitItems($name);
                if($contents === null) return 1;

                $this->gears->runKitCommands($p, $name);
                $this->gears->setKitEffects($p, $name);

                $armor = $this->gears->getArmorContents($contents);

                $special = array_pop($contents);
                $p->getInventory()->setContents(array_merge([$special], $contents));
                $p->getArmorInventory()->setContents($armor);
                $this->gears->getVault()->setKitEnabled($p, clone $this->getKitByName($name));
                return 0;
        }

        /**
         *
         * @param Player $p
         *
         */
        public function unloadKit(Player $p){
                $kit = $this->gears->getVault()->getPlayerKit($p);
                if($kit instanceof Kit){
                        $p->removeAllEffects();
                        $kit->onUnload($p);
                        $p->getInventory()->clearAll();
                        $p->getArmorInventory()->clearAll();
                        $this->gears->getVault()->setKitDisabled($p);
                }
        }

        /**
         *
         * @param $data
         * @param $clickMode
         *
         */
        public function callEvent($data, $clickMode){
                if($clickMode < Kit::RIGHT_CLICK_MODE && $clickMode > Kit::MOVE_PLAYER_MODE){
                        throw new \BadMethodCallException('Unknown click mode ' . $clickMode . ' on ' . __METHOD__);
                }

                $player = $data['Player'];
                $kit = $this->gears->getVault()->getPlayerKit($player);
                if(($kit !== null) and ($kit->clickMode == $clickMode)){
                        $ev = new PlayerActivateKitEvent($this->gears, $player, $kit, $clickMode);
                        $this->gears->getServer()->getPluginManager()->callEvent($ev);
                        if(!$ev->isCancelled()){
                                $kit->onUseSpecialItem($data);
                        }
                }
        }
}