<?php
namespace InfinityGamers\Gears\KitManager;
use InfinityGamers\Gears\Event\PlayerActivateKitEvent;
use InfinityGamers\Gears\Event\PlayerLoadKitEvent;
use InfinityGamers\Gears\Exception\UnknownClickModeException;
use InfinityGamers\Gears\Gears;
use InfinityGamers\Gears\Kit\Kit;
use pocketmine\Player;
class KitManager{
        /**
         * @var Kit[]
         */
        protected $kits = [];

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
                                return isset($this->kits[$kit->getName()]);
                        }
                }elseif(is_string($kit)){
                        return isset($this->kits[$kit]);
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
                        $this->kits[$kit->getName()] = $kit;
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
                $this->kits = [];
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
                        unset($this->kits[$kit]);
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
                return $this->kits;
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

                foreach($this->kits as $kit){
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

                foreach($this->kits as $kit){
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
                        return clone $this->kits[$name];
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
                $kit = $this->getKitByName($name);

                if($kit === null) return 1;

                $ev = new PlayerLoadKitEvent($this->gears, $p, $kit);
                $this->gears->getServer()->getPluginManager()->callEvent($ev);
                if($ev->isCancelled()) return 4;

                if($this->gears->getStorage()->isKitEnabled($p)) return 3;
                if($p->getInventory()->firstEmpty() === -1 || $p->getArmorInventory()->firstEmpty() === -1) return 2;

                $p->removeAllEffects();
                $contents = $this->getKitItems($name);

                $kit->onLoad($p);

                $this->gears->runKitCommands($p, $name);
                $this->gears->setKitEffects($p, $name);
                $armor = $this->gears->getArmorContents($contents);

                $special = array_pop($contents);
                $p->getInventory()->setContents(array_merge([$special], $contents));
                $p->getArmorInventory()->setContents($armor);
                $this->gears->getStorage()->setKitEnabled($p, $kit);
                return 0;
        }

        /**
         *
         * @param Player $p
         *
         * @return bool
         *
         */
        public function unloadKit(Player $p){
                $kit = $this->gears->getStorage()->getPlayerKit($p);
                if($kit === null) return false;

                $ev = new PlayerLoadKitEvent($this->gears, $p, $kit);
                $this->gears->getServer()->getPluginManager()->callEvent($ev);
                if($ev->isCancelled()) return false;

                if($kit instanceof Kit){
                        $kit->onUnload($p);
                        $p->removeAllEffects();
                        $p->getInventory()->clearAll();
                        $p->getArmorInventory()->clearAll();
                        $this->gears->getStorage()->setKitDisabled($p);
                        return true;
                }

                return false;
        }

        /**
         *
         * @param $data
         * @param $clickMode
         *
         */
        public function callEvent($data, $clickMode){
                if($clickMode < Kit::RIGHT_CLICK_MODE && $clickMode > Kit::MOVE_PLAYER_MODE){
                        throw new UnknownClickModeException('Click mode ' . $clickMode . ' not found in ' . __METHOD__);
                }

                $player = $data['Player'];
                $kit = $this->gears->getStorage()->getPlayerKit($player);
                if(($kit !== null) and ($kit->clickMode == $clickMode)){
                        $ev = new PlayerActivateKitEvent($this->gears, $player, $kit, $clickMode);
                        $this->gears->getServer()->getPluginManager()->callEvent($ev);
                        if(!$ev->isCancelled()){
                                $kit->onUseSpecialItem($data);
                        }
                }
        }
}