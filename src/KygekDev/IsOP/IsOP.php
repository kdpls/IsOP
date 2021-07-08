<?php

declare(strict_types=1);

namespace KygekDev\IsOP;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as TF;

class IsOP extends PluginBase {
    
    private const PREFIX = TF::YELLOW . "[IsOP] " . TF::RESET;
    private const COMMAND_PERMISSION = "isop.command";

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool {
        if ($command->getName() !== "isop" || !$sender->hasPermission(self::COMMAND_PERMISSION)) return true;
        
        if (isset($args[0])) {
            if (in_array(strtolower($args[0]), array_map("strtolower", $this->getOpList()))) {
                $this->sendMessage($sender, "Player " . $args[0] . " is OP");
            } else {
                $this->sendMessage($sender, "Player " . $args[0] . " is not OP");
            }
            return true;
        }
        
        $this->sendMessage($sender, "OP players: " . implode(", ", $this->getOpList()));
        return true;
    }

    /**
     * @return string[]
     */
    private function getOpList() : array {
        $this->getServer()->getOps()->reload();
        return array_keys($this->getServer()->getOps()->getAll());
    }
    
    private function sendMessage(CommandSender $sender, string $message) {
        $sender->sendMessage(self::PREFIX . TF::GREEN . $message);
    }

}