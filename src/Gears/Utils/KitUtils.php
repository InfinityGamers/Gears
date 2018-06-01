<?php
namespace Gears\Utils;
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
}