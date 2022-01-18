<?php

declare(strict_types=1);

namespace Kygekraqmak\NoDropItem;

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
