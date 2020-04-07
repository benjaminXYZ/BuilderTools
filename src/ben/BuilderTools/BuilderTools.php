<?php

namespace ben\BuilderTools;

use ben\BuilderTools\commands\BuilderToolsCommand;
use ben\BuilderTools\tasks\BuilderToolsTask;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat;

class BuilderTools extends PluginBase implements Listener {

    const prefix = TextFormat::YELLOW . "BuilderTools" . TextFormat::DARK_GRAY . " » " .  TextFormat::GRAY;

    public static $builders = [];

    public static $instance;

    public function onEnable() {

        $this->getLogger()->info(self::prefix . "Plugin has been enabled!");

        self::$instance = $this;

        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        $this->getScheduler()->scheduleRepeatingTask(new BuilderToolsTask(), 10);

        $this->getServer()->getCommandMap()->register("buildertools", new BuilderToolsCommand("buildertools", "BuilderTools Command", null, ["bts"]));

    }

    public static function addActionBarMessage(Player $player) {

        if (!array_key_exists($player->getName(), self::$builders)) return;

        $coords = self::$builders[$player->getName()]["coords"];
        $id = self::$builders[$player->getName()]["id"];

        $message = "";

        if ($coords) {
            $message .= TextFormat::GRAY . "Coordinates: " . TextFormat::YELLOW . strval(round($player->getX(), 0)) . ":"  . strval(round($player->getY(), 0)) .  ":"  . strval(round($player->getZ(), 0));
        }

        if ($id) {
            if ($coords) {
                $message .= TextFormat::DARK_GRAY . " | ";
            }
            $message .= TextFormat::GRAY . "Id: " . TextFormat::YELLOW . $player->getInventory()->getItemInHand()->getId() . ":" . TextFormat::YELLOW . $player->getInventory()->getItemInHand()->getDamage();
        }

        $player->addActionBarMessage($message);

    }

    public function onQuit(PlayerQuitEvent $event) {

        $player = $event->getPlayer();

        if (array_key_exists($player->getName(), self::$builders)) {
            unset(self::$builders[$player->getName()]);
        }

    }

    public function onBreak(BlockBreakEvent $event) {

        $player = $event->getPlayer();
        $block = $event->getBlock();

        if ($player->hasPermission("buildertools.perm") && $player->getInventory()->getItemInHand()->getCustomName() === TextFormat::YELLOW . "Builder Tool") {

            $player->sendMessage(self::prefix . "Id: " . TextFormat::YELLOW . $block->getId() . ":" . $block->getDamage() . TextFormat::DARK_GRAY . " | " . TextFormat::GRAY . TextFormat::GRAY . "Coordinates: " . TextFormat::YELLOW . strval(round($block->getX(), 0)) . ":"  . strval(round($block->getY(), 0)) . ":"  . strval(round($block->getZ(), 0)));

            $event->setCancelled();

        }

    }

    public static function getInstance() : self {

        return self::$instance;

    }


}