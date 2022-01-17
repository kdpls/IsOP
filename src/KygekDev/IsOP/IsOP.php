<?php

/*
 * List OP players and check if player is OP using command
 * Copyright (C) 2021 KygekDev
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

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
        if ($command->getName() !== "isop" or !$sender->hasPermission(self::COMMAND_PERMISSION)) return true;
        
        if (isset($args[0])) {
            if (in_array(mb_strtolower($args[0]), array_map("strtolower", $this->getOpList()))) {
                $this->sendMessage($sender, "Player " . $args[0] . " is OP");
            } else {
                $this->sendMessage($sender, "Player " . $args[0] . " is not OP");
            }
            return true;
        }
        $getOpList = implode(", ", $this->getOpList());
        $opList = ($getOpList == null) ? TF::RED . "Null" :  $getOpList;
        $this->sendMessage($sender, "OP players: " . $opList);
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