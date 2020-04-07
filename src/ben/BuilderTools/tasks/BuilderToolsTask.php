<?php

namespace ben\BuilderTools\tasks;

use ben\BuilderTools\BuilderTools;
use pocketmine\scheduler\Task;

class BuilderToolsTask extends Task {

    public function onRun(int $currentTick) {
        foreach (BuilderTools::getInstance()->getServer()->getOnlinePlayers() as $player) {
            if (array_key_exists($player->getName(), BuilderTools::$builders)) {
                BuilderTools::addActionBarMessage($player);
            }
        }
    }

}