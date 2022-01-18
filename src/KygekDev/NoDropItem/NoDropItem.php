<?php

/*
 * Prevent players from dropping items from their inventory
 * Copyright (C) 2020-2022 KygekDev
 *
 * This program is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 */

declare(strict_types=1);

namespace KygekDev\NoDropItem;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\utils\TextFormat;

class NoDropItem extends PluginBase implements Listener {

    public $config;

    protected function onEnable() : void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();
        $this->config = $this->getConfig();
    }

    public function onDrop(PlayerDropItemEvent $event) {
        $player = $event->getPlayer();
        if ($player->hasPermission("nodropitem.bypass")) return;
        switch ($this->config->get("world-mode")) {
            case "blacklist":
                if (in_array($player->getWorld()->getDisplayName(), $this->config->get("worlds-list"))){
                    $event->cancel();
                    $player->sendMessage(TextFormat::colorize($this->config->get("warning")));
                }
                break;
            case "whitelist":
                if (!in_array($player->getWorld()->getDisplayName(), $this->config->get("worlds-list"))){
                    $event->cancel();
                    $player->sendMessage(TextFormat::colorize($this->config->get("warning")));
                }
                break;
            default:
                $event->cancel();
                $player->sendMessage(TextFormat::colorize($this->config->get("warning")));
        }
    }

}
