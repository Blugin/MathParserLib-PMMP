<?php

namespace blugin\mathparser\command;

use pocketmine\command\{
  Command, CommandExecutor, CommandSender
};
use blugin\mathparser\MathParser as Plugin;
use blugin\mathparser\util\Translation;

class CommandListener implements CommandExecutor{

    /** @var Plugin */
    protected $owner;

    /** @param Plugin $owner */
    public function __construct(Plugin $owner){
        $this->owner = $owner;
    }

    /**
     * @param CommandSender $sender
     * @param Command       $command
     * @param string        $label
     * @param string[]      $args
     *
     * @return bool
     */
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool{
        $expression = implode(' ', $args);
        if (!empty($expression)) {
            try{
                $sender->sendMessage(Plugin::$prefix . Translation::translate('command-math@success', Plugin::parse($expression)));
            } catch (\Exception $exception){
                $sender->sendMessage(Plugin::$prefix . Translation::translate('command-math@failure', $exception->getMessage()));
            }
            return true;
        }
        return false;
    }
}