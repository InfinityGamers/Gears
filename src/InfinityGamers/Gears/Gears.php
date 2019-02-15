<?php
namespace InfinityGamers\Gears;
use InfinityGamers\Gears\Commands\GearsCommand;
use InfinityGamers\Gears\KitManager\KitManager;
use InfinityGamers\Gears\Lang\Translator;
use InfinityGamers\Gears\Storage\Storage;
use InfinityGamers\Gears\Utils\exc;
use InfinityGamers\Gears\Utils\KitUtils;
use InfinityGamers\Gears\Utils\RandomUtils;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\item\Item;
use InfinityGamers\Gears\Kit\{
    Acrobat, Bartender, Berserker, Iceman, Magneto, Scorpio, Spider, Swapper, Thor
};
class Gears extends PluginBase{

        /** @var Config */
        protected $kits;

        /** @var KitManager */
        public $kitManager;
        /** @var Storage */
        public $vault;

        public function onEnable(){
                $this->setUpDirectories();
                $this->kits = new Config($this->getDataFolder() . "special_kits.yml", Config::YAML, KitUtils::getDefaultConfigData());
                $this->kitManager = new KitManager($this);
                $this->vault = new Storage();
                $this->registerKits();

                $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
                $this->getServer()->getCommandMap()->register('gear', new GearsCommand($this));

                $this->saveResource('config.yml');

                $lang = $this->getConfig()->get('language');

                Translator::setLanguagePath($this->getLanguagePath());
                Translator::selectLang($lang);

                $this->getLogger()->notice("Selected language: " . $lang);
        }

        public function setUpDirectories(){
                if(!file_exists($this->getDataFolder())){
                        mkdir($this->getDataFolder());
                }
                if(!file_exists($this->getLanguagePath())){
                        mkdir($this->getLanguagePath());
                }
        }

        public function registerKits(){
                $kitClasses = [
                    'Acrobat' => Acrobat::class,
                    'Bartender' => Bartender::class,
                    'Berserker' => Berserker::class,
                    'Iceman' => Iceman::class,
                    'Magneto' => Magneto::class,
                    'Scorpio' => Scorpio::class,
                    'Spider' => Spider::class,
                    'Swapper' => Swapper::class,
                    'Thor' => Thor::class
                ];

                foreach($kitClasses as $kitName => $kitClass){
                        $this->getKitManager()->registerKit(new $kitClass(...$this->parseKitData($kitName)));
                }
        }

        /**
         *
         * @return string
         *
         */
        public function getLanguagePath(): string{
                return $this->getDataFolder() . "lang/";
        }

        /**
         *
         * @return KitManager
         *
         */
        public function getKitManager(): KitManager{
                return $this->kitManager;
        }

        /**
         *
         * @return Storage
         *
         */
        public function getStorage(): Storage{
                return $this->vault;
        }
        /**
         *
         * @return Config
         *
         */
        public function getKitsConfig(): Config{
                return $this->kits;
        }

        /**
         *
         * @param string $kit
         *
         * @return mixed|null
         *
         */
        public function getRawKitConfigData(string $kit){
                return (($kit = $this->kits->get($kit)) !== false) ? $kit : null;
        }

        /**
         *
         * @param string $kit
         * @param array  $config
         *
         * @return bool
         *
         */
        public function setRawKitConfigData(string $kit, array $config){
                if($this->kits->exists($kit)){
                        $this->kits->set($kit, $config);
                        $this->kits->save();
                        return true;
                }
                return false;
        }



        /**
         *
         * @param Player $player
         * @param string $kitName
         *
         * @return array
         *
         */
        public function runKitCommands(Player $player, string $kitName){
                $kit = $this->kits->get($kitName);
                if($kit !== false){
                        foreach($kit['commands'] as &$command){
                                $command = str_replace(["@player", "@kit"],
                                    [$player->getName(), $kitName], $command);
                                $command = RandomUtils::colorMessage($command);
                        }
                        $this->sendCommands($kit['commands']);
                }
                return null;

        }

        /**
         *
         * @param Player $player
         * @param string $kitName
         *
         * @return array
         *
         */
        public function setKitEffects(Player $player, string $kitName){
                $kit = $this->kits->get($kitName);
                if($kit !== false){
                        $effects = $this->parseEffects($kit['effects']);
                        foreach($effects as $effect){
                                $player->addEffect($effect);
                        }
                }
                return null;

        }

        /**
         *
         * @param string $kitName
         *
         * @return array
         *
         */
        public function parseKitData(string $kitName){
                $kit = $this->kits->get($kitName);
                if($kit !== false){

                        $items = array_merge($this->parseItemsWithEnchants([$kit['helmet'], $kit['chest'], $kit['legs'], $kit['boots']]), $this->parseItemsWithEnchants($kit['items']));
                        /** @var \DateTime $coolDown */
                        $coolDown = exc::stringToTimestamp($kit['cooldown'])[0];
                        $coolDown = ($coolDown->getTimestamp() - time());

                        /** @var \DateTime $deactivate */
                        $deactivate = exc::stringToTimestamp($kit['deactivate_time'])[0];
                        $deactivate = ($deactivate->getTimestamp() - time());

                        $special = $this->parseItemsWithEnchants([$kit['ability_item']])[0];

                        return [$special, $items, $coolDown, $deactivate];

                }
                return null;

        }

        /**
         *
         * @param array  $kit
         *
         * @return array
         *
         */
        public function parseKitDataRaw(array $kit){
                $items = array_merge($this->parseItemsWithEnchants([$kit['helmet'], $kit['chest'], $kit['legs'], $kit['boots']]), $this->parseItemsWithEnchants($kit['items']));
                /** @var \DateTime $coolDown */
                $coolDown = exc::stringToTimestamp($kit['cooldown'])[0];
                $coolDown = ($coolDown->getTimestamp() - time());

                /** @var \DateTime $deactivate */
                $deactivate = exc::stringToTimestamp($kit['deactivate_time'])[0];
                $deactivate = ($deactivate->getTimestamp() - time());

                $special = $this->parseItemsWithEnchants([$kit['ability_item']])[0];

                return [$special, $items, $coolDown, $deactivate];

        }

        /**
         *
         * @param array $commands
         *
         */
        public function sendCommands(array $commands){
                foreach($commands as $command){
                        $this->getServer()->dispatchCommand(new ConsoleCommandSender(), $command);
                }
        }

        /**
         *
         * @param array $effects
         *
         * @return EffectInstance[]
         *
         */
        public function parseEffects(array $effects){
                $out = [];
                foreach($effects as $effect){
                        if($effect instanceof EffectInstance){
                                $out[] = $effect;
                        }else{
                                $parts = explode(":", $effect);
                                $effect = Effect::getEffectByName($parts[0]);
                                if($effect instanceof Effect){
                                        $out[] = (new EffectInstance($effect))->setAmplifier(intval($parts[1]))->setDuration(intval($parts[2]) * 20);
                                }
                        }
                }
                return $out;
        }

        /**
         *
         * @param array $items
         *
         * @return Item[]
         *
         */
        public function parseItemsWithEnchants(array $items){
                $out = [];
                foreach($items as $key => $item){
                        if($item instanceof Item){
                                $out[] = $item;
                        }else{
                                $parts = explode(':', $item);
                                $id = array_shift($parts);
                                $meta = array_shift($parts);
                                $amount = array_shift($parts);
                                $name = array_shift($parts);
                                $item = Item::fromString("$id:$meta");
                                if(!($item->getId() === Item::AIR)){
                                        $item->setCount($amount);
                                        $parts = implode(":", $parts);
                                        foreach($this->parseEnchants([$parts]) as $enchant){
                                                $item->addEnchantment($enchant);
                                        }
                                        if(strtolower($name) !== "default"){
                                                $item->setCustomName(RandomUtils::colorMessage($name));
                                        }
                                        $out[] = $item;
                                }

                        }
                }
                return $out;
        }


        /**
         *
         * @param array $enchants
         *
         * @return array|EnchantmentInstance[]
         *
         */
        public function parseEnchants(array $enchants){
                /** @var EnchantmentInstance[] $out */
                $out = [];
                $i = 1;
                /** @var Enchantment $lastEnchantment */
                $lastEnchantment = null;
                foreach($enchants as $enchant){
                        if($enchant instanceof EnchantmentInstance){
                                $out[] = $enchant;
                        }else{
                                $parts = explode(':', $enchant);
                                foreach($parts as $part){
                                        if(($i % 2) === 0){
                                                if($lastEnchantment !== null){
                                                        $out[] = new EnchantmentInstance($lastEnchantment, $part);
                                                }
                                        }else{
                                                $lastEnchantment = Enchantment::getEnchantmentByName($part);
                                        }
                                        ++$i;
                                }
                        }
                }
                return $out;
        }

        /**
         *
         * @param Item[] $contents
         *
         * @return Item[]
         *
         */
        public function getArmorContents(array &$contents): array{
                $helmetIds = [
                    Item::CHAIN_HELMET,
                    Item::DIAMOND_HELMET,
                    Item::GOLD_HELMET,
                    Item::IRON_HELMET,
                    Item::LEATHER_HELMET
                ];
                $chestplateIds = [
                    Item::CHAIN_CHESTPLATE,
                    Item::DIAMOND_CHESTPLATE,
                    Item::GOLD_CHESTPLATE,
                    Item::IRON_CHESTPLATE,
                    Item::LEATHER_CHESTPLATE
                ];
                $leggingIds = [
                    Item::CHAIN_LEGGINGS,
                    Item::DIAMOND_LEGGINGS,
                    Item::GOLD_LEGGINGS,
                    Item::IRON_LEGGINGS,
                    Item::LEATHER_LEGGINGS
                ];
                $bootIds = [
                    Item::CHAIN_BOOTS,
                    Item::DIAMOND_BOOTS,
                    Item::GOLD_BOOTS,
                    Item::IRON_BOOTS,
                    Item::LEATHER_BOOTS
                ];
                $armor = [];
                foreach($contents as $index => $content){
                        if(in_array($content->getId(), $helmetIds)){
                                $armor[0] = $content;
                                unset($contents[$index]);
                        }else if(in_array($content->getId(), $chestplateIds)){
                                $armor[1] = $content;
                                unset($contents[$index]);
                        }else if(in_array($content->getId(), $leggingIds)){
                                $armor[2] = $content;
                                unset($contents[$index]);
                        }else if(in_array($content->getId(), $bootIds)){
                                $armor[3] = $content;
                                unset($contents[$index]);
                        }
                }
                return $armor;
        }
}