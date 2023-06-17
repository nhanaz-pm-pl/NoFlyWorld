<?php

declare(strict_types=1);

namespace NhanAZ\NoFlyWorld;

use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\Listener;
use pocketmine\permission\DefaultPermissions;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;

class Main extends PluginBase implements Listener {

	protected function onEnable(): void {
		$this->getServer()->getPluginManager()->registerEvents($this, $this);
		$this->saveDefaultConfig();
	}

	public function onEntityTeleport(EntityTeleportEvent $event): void {
		$entity = $event->getEntity();
		if ($entity instanceof Player) {
			$player = $entity;
			$to = $event->getTo();
			$toWorld = $to->getWorld();
			$isAdmin = $player->hasPermission(DefaultPermissions::ROOT_OPERATOR) || $player->getGamemode()->equals(GameMode::CREATIVE());
			$noFlyWorld = $this->getConfig()->get("NoFlyWorld");
			$isNoFlyWorld = in_array($toWorld->getDisplayName(), $noFlyWorld) || in_array($toWorld->getFolderName(), $noFlyWorld);
			if ($isNoFlyWorld && ($player->isFlying() || $player->getAllowFlight()) && !$isAdmin) {
				$player->setFlying(false);
				$player->setAllowFlight(false);
			}
		}
	}
}
