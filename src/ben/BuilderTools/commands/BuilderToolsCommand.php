<?php

namespace ben\BuilderTools\commands;

use ben\BuilderTools\BuilderTools;
use ben\BuilderTools\forms\BuilderInfoForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\item\enchantment\Enchantment;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\Item;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class BuilderToolsCommand extends Command {

    public function __construct(string $name, string $description = "", string $usageMessage = null, array $aliases = []) {
        parent::__construct($name, $description, $usageMessage, $aliases);
        $this->setPermission("buildertools.perm");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args) {
        if (!$sender instanceof Player || !$sender->hasPermission("buildertools.perm")) {
            $sender->sendMessage(BuilderTools::prefix . "You don't have permissions to use this command");
            return;
        }

        if (!isset($args[0])) {
            $sender->sendMessage(BuilderTools::prefix . "/buildertools <display/tool>");
            return;
        }

        if (strtolower($args[0]) === "display") {
            $sender->sendForm(new BuilderInfoForm());
            return;
        }

        if (strtolower($args[0]) === "tool") {
            $item = Item::get(Item::STICK);
            $item->addEnchantment(new EnchantmentInstance(Enchantment::getEnchantment(Enchantment::UNBREAKING)));
            $item->setCustomName(TextFormat::YELLOW . "Builder Tool");
            $item->setLore([TextFormat::GRAY . "Break a block to get information about it"]);
            $sender->getInventory()->addItem($item);
            $sender->sendMessage(BuilderTools::prefix . "The tool has been added to your inventory");
            return;
        }

        $sender->sendMessage(BuilderTools::prefix . "/buildertools <display/tool>");

    }

}