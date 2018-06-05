<?php
namespace InfinityGamers\Gears\Utils;
class KitUtils{
        /**
         * @var int[]
         */
        public static $coolDown = [];

        /**
         * @param     $name
         * @param int $seconds
         * @return bool
         */
        public static function checkKitCoolDown($name, $seconds = 15){
                if(!isset(self::$coolDown[$name])){
                        self::$coolDown[$name] = time();
                        return false;
                }
                if(((time() - self::$coolDown[$name]) <= $seconds)){
                        return true;
                }
                self::$coolDown[$name] = time();
                return false;
        }

        /**
         *
         * @return array
         *
         */
        public static function getDefaultConfigData(): array{
                return [
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
                ];
        }
}