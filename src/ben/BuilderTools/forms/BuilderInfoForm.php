<?php

namespace ben\BuilderTools\forms;

use ben\BuilderTools\BuilderTools;
use jojoe77777\FormAPI\CustomForm;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class BuilderInfoForm extends CustomForm {

    public function __construct() {

        $callable = function (Player $player, $data) {

            if (!$data[0] && !$data[1]) {
                if (array_key_exists($player->getName(), BuilderTools::$builders)) {
                    unset(BuilderTools::$builders[$player->getName()]);
                }
                $player->sendMessage(BuilderTools::prefix . "You disabled the display");
                return;
            }

            BuilderTools::$builders[$player->getName()] = ["coords" => false, "id" => false];

            if ($data[0]) {
                BuilderTools::$builders[$player->getName()]["coords"] = true;
            }

            if ($data[1]) {
                BuilderTools::$builders[$player->getName()]["id"] = true;
            }

            $player->sendMessage(BuilderTools::prefix . "The display has been updated");

        };

        parent::__construct($callable);

        $this->setTitle(BuilderTools::prefix . TextFormat::YELLOW .  "Display");

        $this->addToggle("Show coordinates", 1);
        $this->addToggle("Show item id", 1);

    }

}