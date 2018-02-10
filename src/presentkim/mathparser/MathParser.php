<?php

namespace presentkim\mathparser;

use pocketmine\command\PluginCommand;
use pocketmine\plugin\PluginBase;
use Math\Parser;
use presentkim\mathparser\command\CommandListener;
use presentkim\mathparser\util\Translation;

class MathParser extends PluginBase{

    /** @var self */
    private static $instance = null;

    /** @var Parser */
    private static $parser = null;

    /** @return self */
    public static function getInstance() : self{
        return self::$instance;
    }

    /** @return Parser */
    public static function getParser() : Parser{
        return self::$parser;
    }

    /** @var PluginCommand */
    private $command = null;

    public function onLoad() : void{
        if (self::$instance === null) {
            self::$instance = $this;
            self::$parser = new Parser();
        }
    }

    public function onEnable() : void{
        $dataFolder = $this->getDataFolder();
        if (!file_exists($dataFolder)) {
            mkdir($dataFolder, 0777, true);
        }

        $langfilename = $dataFolder . 'lang.yml';
        if (!file_exists($langfilename)) {
            $resource = $this->getResource('lang/eng.yml');
            fwrite($fp = fopen("{$dataFolder}lang.yml", "wb"), $contents = stream_get_contents($resource));
            fclose($fp);
            Translation::loadFromContents($contents);
        } else {
            Translation::load($langfilename);
        }

        if ($this->command !== null) {
            $this->getServer()->getCommandMap()->unregister($this->command);
        }

        $this->command = new PluginCommand(Translation::translate('command-math'), $this);
        $this->command->setExecutor(new CommandListener($this));
        $this->command->setPermission('math.cmd');
        $this->command->setDescription(Translation::translate('command-math@description'));
        $this->command->setUsage(Translation::translate('command-math@usage'));
        if (is_array($aliases = Translation::getArray('command-math@aliases'))) {
            $this->command->setAliases($aliases);
        }
        $this->getServer()->getCommandMap()->register('mathparser', $this->command);
    }

    /**
     * @param string $name = ''
     *
     * @return PluginCommand
     */
    public function getCommand(string $name = '') : PluginCommand{
        return $this->command;
    }

    /** @param PluginCommand $command */
    public function setCommand(PluginCommand $command) : void{
        $this->command = $command;
    }
}
