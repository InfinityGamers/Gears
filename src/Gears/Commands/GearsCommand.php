<?php
namespace Gears\Commands;
use Gears\Gears;
use Gears\Utils\RandomUtils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\PluginIdentifiableCommand;
use pocketmine\Player;
use pocketmine\plugin\Plugin;

class GearsCommand extends Command implements PluginIdentifiableCommand{
        /**
         * @var Gears
         */
        private $core;

        /**
         *
         * GearsCommand constructor.
         *
         * @param Gears $core
         *
         */
        public function __construct(Gears $core){
                $this->core = $core;
                parent::__construct('gear', 'Gears command', RandomUtils::colorMessage('&eUsage: /gear <name>'), ['gears']);
        }

        /**
         *
         * @param CommandSender $sender
         * @param string        $commandLabel
         * @param string[]      $args
         *
         * @return mixed|void
         *
         */
        public function execute(CommandSender $sender, $commandLabel, array $args){
                if(!($sender instanceof Player)) return;

                if(count($args) < 1){
                        $sender->sendMessage($this->getUsage());
                        $available = $this->core->getKitManager()->getAllKitsAvailableToPlayerNames($sender);
                        $available = count($available) > 0 ? implode(", ", $available) : 'none';
                        $sender->sendMessage(RandomUtils::colorMessage("&eAvailable: &f" . $available));
                        return;
                }

                $kit = $args[0];

                if(strtolower($kit) === 'reset'){
                        $this->core->getKitManager()->unloadKit($sender);
                        $message = $this->core->getMessage('successfully_reset');
                        $sender->sendMessage(RandomUtils::colorMessage($message));
                        return;
                }

                if($sender->hasPermission("kit." . strtolower($kit))){
                        $result = $this->core->getKitManager()->loadKit($sender, $kit);

                        switch($result){
                                case 3:
                                        $message = $this->core->getMessage('already_enabled');
                                        $sender->sendMessage(RandomUtils::colorMessage($message));
                                        break;
                                case 2:
                                        $message = $this->core->getMessage('empty_inventory_first');
                                        $sender->sendMessage(RandomUtils::colorMessage($message));
                                        break;
                                case 1:
                                        $message = $this->core->getMessage('unknown_kit');
                                        $sender->sendMessage(RandomUtils::colorMessage($message));
                                        break;
                                case 0:
                                        $message = $this->core->getMessage('equipped_kit');
                                        $sender->sendMessage(RandomUtils::colorMessage($message));
                                        break;
                                default:
                                        $message = $this->core->getMessage('unknown_error');
                                        $sender->sendMessage(RandomUtils::colorMessage($message));
                                        break;
                        }
                }else{
                        $message = $this->core->getMessage('not_unlocked');
                        $sender->sendMessage(RandomUtils::colorMessage($message));
                }
        }

        /**
         *
         * @return Gears
         *
         */
        public function getPlugin(): Plugin{
                return $this->core;
        }
}