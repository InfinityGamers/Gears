<?php
namespace Gears;
use Gears\Commands\GearsCommand;
use Gears\Utils\exc;
use Gears\Utils\RandomUtils;
use pocketmine\command\ConsoleCommandSender;
use pocketmine\entity\Effect;
use pocketmine\entity\EffectInstance;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;
use pocketmine\item\Item;
use Gears\Kit\{
    Acrobat, Bartender, Berserker, Iceman, Magneto, Scorpio, Spider, Swapper, Thor, TPkit
};

class Gears extends PluginBase{

        /** @var Gears */
        private static $instance = null;

        /** @var Config */
        protected $kits;

        /**
         * @var KitManager
         */
        public $kitManager;
        /**
         * @var Vault
         */
        public $vault;

        /** @var string[] */
        protected $messages = [];

        public function onLoad(){
                while(!self::$instance instanceof $this){
                        self::$instance = $this;
                }
        }

        public function onEnable(){

                if(!file_exists($this->getDataFolder())){
                        mkdir($this->getDataFolder());
                }
                $this->kits = new Config($this->getDataFolder() . "special_kits.yml", Config::YAML, [
                    'Acrobat' => [
                        'ability_item' => '288:0:1:&bAcrobat &a| Click with this to use ability',
                        'cooldown' => '10s',
                        'deactivate_time' => '-1s',
                        'helmet' => '298:0:1:default',
                        'chest' => '299:0:1:default:protection:1',
                        'legs' => '300:0:1:default',
                        'boots' => '301:0:1:default',
                        'items' => [
                            "268:0:1:default:sharpness:1"
                        ],
                        'commands' => [
                            "tell @player &5You equipped the @kit kit."
                        ],
                        'effects' => [
                            "strength:1:30"
                        ]
                    ],
                    'Bartender' => [
                        'ability_item' => '437:0:1:&bBartender &a| Hit a player with this to use ability',
                        'cooldown' => '10s',
                        'deactivate_time' => '5s',
                        'helmet' => '298:0:1:default',
                        'chest' => '299:0:1:default:protection:1',
                        'legs' => '300:0:1:default',
                        'boots' => '301:0:1:default',
                        'items' => [
                            "268:0:1:default:sharpness:1"
                        ],
                        'commands' => [
                            "tell @player &5You equipped the @kit kit."
                        ],
                        'effects' => [
                            "strength:1:30"
                        ]
                    ],
                    'Berserker' => [
                        'ability_item' => '286:0:1:&bBerserker &a| Right click this to use ability',
                        'cooldown' => '15s',
                        'deactivate_time' => '10s',
                        'helmet' => '298:0:1:default',
                        'chest' => '299:0:1:default:protection:1',
                        'legs' => '300:0:1:default',
                        'boots' => '301:0:1:default',
                        'items' => [
                            "268:0:1:default:sharpness:1"
                        ],
                        'commands' => [
                            "tell @player &5You equipped the @kit kit."
                        ],
                        'effects' => [
                            "strength:1:30"
                        ]
                    ],
                    'Iceman' => [
                        'ability_item' => '79:0:1:&bIceman &a| Walk on water to activate ability',
                        'cooldown' => '15s',
                        'deactivate_time' => '10s',
                        'helmet' => '298:0:1:default',
                        'chest' => '299:0:1:default:protection:1',
                        'legs' => '300:0:1:default',
                        'boots' => '301:0:1:default',
                        'items' => [
                            "268:0:1:default:sharpness:1"
                        ],
                        'commands' => [
                            "tell @player &5You equipped the @kit kit."
                        ],
                        'effects' => [
                            "strength:1:30"
                        ]
                    ],
                    'Magneto' => [
                        'ability_item' => '318:0:1:&eMagneto &a| Right click this to use ability',
                        'cooldown' => '15s',
                        'deactivate_time' => '5s',
                        'helmet' => '298:0:1:default',
                        'chest' => '299:0:1:default:protection:1',
                        'legs' => '300:0:1:default',
                        'boots' => '301:0:1:default',
                        'items' => [
                            "268:0:1:default:sharpness:1"
                        ],
                        'commands' => [
                            "tell @player &5You equipped the @kit kit."
                        ],
                        'effects' => [
                            "strength:1:30"
                        ]
                    ],
                    'Scorpio' => [
                        'ability_item' => '399:0:1:&eScorpio &a| Right click this to use ability',
                        'cooldown' => '15s',
                        'deactivate_time' => '5s',
                        'helmet' => '298:0:1:default',
                        'chest' => '299:0:1:default:protection:1',
                        'legs' => '300:0:1:default',
                        'boots' => '301:0:1:default',
                        'items' => [
                            "268:0:1:default:sharpness:1"
                        ],
                        'commands' => [
                            "tell @player &5You equipped the @kit kit."
                        ],
                        'effects' => [
                            "strength:1:30"
                        ]
                    ],
                    'Spider' => [
                        'ability_item' => '375:0:1:&bSpider &a| Right click with this to use ability',
                        'cooldown' => '15s',
                        'deactivate_time' => '10s',
                        'helmet' => '298:0:1:default',
                        'chest' => '299:0:1:default:protection:1',
                        'legs' => '300:0:1:default',
                        'boots' => '301:0:1:default',
                        'items' => [
                            "268:0:1:default:sharpness:1"
                        ],
                        'commands' => [
                            "tell @player &5You equipped the @kit kit."
                        ],
                        'effects' => [
                            "strength:1:30"
                        ]
                    ],
                    'Swapper' => [
                        'ability_item' => '501:0:1:&bSwapper &a| Right click on a player to use ability',
                        'cooldown' => '15s',
                        'deactivate_time' => '-1s',
                        'helmet' => '298:0:1:default',
                        'chest' => '299:0:1:default:protection:1',
                        'legs' => '300:0:1:default',
                        'boots' => '301:0:1:default',
                        'items' => [
                            "268:0:1:default:sharpness:1"
                        ],
                        'commands' => [
                            "tell @player &5You equipped the @kit kit."
                        ],
                        'effects' => [
                            "strength:1:30"
                        ]
                    ],
                    'Thor' => [
                        'ability_item' => '369:0:1:&eThor &a| Right click this to use ability',
                        'cooldown' => '15s',
                        'deactivate_time' => '10s',
                        'helmet' => '298:0:1:default',
                        'chest' => '299:0:1:default:protection:1',
                        'legs' => '300:0:1:default',
                        'boots' => '301:0:1:default',
                        'items' => [
                            "268:0:1:default:sharpness:1"
                        ],
                        'commands' => [
                            "tell @player &5You equipped the @kit kit."
                        ],
                        'effects' => [
                            "strength:1:30"
                        ]
                    ],
                ]);
                $this->kitManager = new KitManager($this);
                $this->vault = new Vault();
                $this->registerKits();

                $this->getServer()->getPluginManager()->registerEvents(new EventListener($this), $this);
                $this->getServer()->getCommandMap()->register('gear', new GearsCommand($this));

                $this->saveResource('messages.yml');

                $this->messages = (new Config($this->getDataFolder() . 'messages.yml'))->getAll();
        }

        public function registerKits(){
                $kits = [];

                $kit = $this->parseKitData('Acrobat');
                if($kit !== null){
                        $kits[] = new Acrobat($kit[0], $kit[1], $kit[2], $kit[3]);
                }

                $kit = $this->parseKitData('Bartender');
                if($kit !== null){
                        $kits[] = new Bartender($kit[0], $kit[1], $kit[2], $kit[3]);
                }

                $kit = $this->parseKitData('Berserker');
                if($kit !== null){
                        $kits[] = new Berserker($kit[0], $kit[1], $kit[2], $kit[3]);
                }


                $kit = $this->parseKitData('Iceman');
                if($kit !== null){
                        $kits[] = new Iceman($kit[0], $kit[1], $kit[2], $kit[3]);
                }

                $kit = $this->parseKitData('Magneto');

                if($kit !== null){
                        $kits[] = new Magneto($kit[0], $kit[1], $kit[2], $kit[3]);
                }


                $kit = $this->parseKitData('Scorpio');
                if($kit !== null){
                        $kits[] = new Scorpio($kit[0], $kit[1], $kit[2], $kit[3]);
                }

                $kit = $this->parseKitData('Spider');
                if($kit !== null){
                        $kits[] = new Spider($kit[0], $kit[1], $kit[2], $kit[3]);
                }

                $kit = $this->parseKitData('Swapper');
                if($kit !== null){
                        $kits[] = new Swapper($kit[0], $kit[1], $kit[2], $kit[3]);
                }

                $kit = $this->parseKitData('Thor');
                if($kit !== null){
                        $kits[] = new Thor($kit[0], $kit[1], $kit[2], $kit[3]);
                }

                $this->getKitManager()->registerKits($kits);
        }

        /**
         *
         * @param string $message
         *
         * @return string
         *
         */
        public function getMessage(string $message){
                return $this->messages[$message];
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
         * @return Vault
         *
         */
        public function getVault(): Vault{
                return $this->vault;
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
                        if($effect instanceof Effect){
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