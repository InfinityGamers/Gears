<?php
namespace InfinityGamers\Gears\Commands;
use InfinityGamers\Gears\Gears;
use InfinityGamers\Gears\Lang\Translator;
use InfinityGamers\Gears\Utils\RandomUtils;
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
                        if($this->core->getKitManager()->unloadKit($sender)){
                                $message = Translator::getMessage('successfully_reset');
                                $sender->sendMessage(RandomUtils::colorMessage($message));
                        }
                        return;
                }

                if($sender->hasPermission("kit." . strtolower($kit))){
                        $result = $this->core->getKitManager()->loadKit($sender, $kit);

                        switch($result){
                                case 4:
                                        break;
                                case 3:
                                        $message = Translator::getMessage('already_enabled');
                                        $sender->sendMessage(RandomUtils::colorMessage($message));
                                        break;
                                case 2:
                                        $message = Translator::getMessage('empty_inventory_first');
                                        $sender->sendMessage(RandomUtils::colorMessage($message));
                                        break;
                                case 1:
                                        $message = Translator::getMessage('unknown_kit');
                                        $sender->sendMessage(RandomUtils::colorMessage($message));
                                        break;
                                case 0:
                                        $message = Translator::getMessage('equipped_kit');
                                        $sender->sendMessage(RandomUtils::colorMessage($message));
                                        break;
                                default:
                                        $message = Translator::getMessage('unknown_error');
                                        $sender->sendMessage(RandomUtils::colorMessage($message));
                                        break;
                        }
                }else{
                        $message = Translator::getMessage('not_unlocked');
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