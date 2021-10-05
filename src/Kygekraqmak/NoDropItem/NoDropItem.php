<?php

declare(strict_types=1);

namespace Kygekraqmak\NoDropItem;

use pocketmine\plugin\PluginBase;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDropItemEvent;

class NoDropItem extends PluginBase implements Listener {

    public $config;

    public function onEnable() {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
        $this->saveDefaultConfig();
        $this->config = $this->getConfig();
    }

    public function onDrop(PlayerDropItemEvent $event) {
        $player = $event->getPlayer();
        if ($player->hasPermission("nodropitem.bypass")) return;
        switch ($this->config->get("world-mode")) {
            case "blacklist":
                foreach ($this->config->get("worlds-list") as $world) {
                    if ($player->getLevel()->getName() === $world) {
                        $event->setCancelled();
                        $player->sendMessage(str_replace("&", "§", $this->config->get("warning")));
                    }
                }
                break;
            case "whitelist":
                foreach ($this->config->get("worlds-list") as $world) {
                    if ($player->getLevel()->getName() !== $world) {
                        $event->setCancelled();
                        $player->sendMessage(str_replace("&", "§", $this->config->get("warning")));
                    }
                }
                break;
            default:
                $event->setCancelled();
                $player->sendMessage(str_replace("&", "§", $this->config->get("warning")));
        }
    }

}
