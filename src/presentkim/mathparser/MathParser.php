<?php

namespace presentkim\mathparser;

use MathParser\Interpreting\Evaluator;
use pocketmine\command\PluginCommand;
use pocketmine\plugin\PluginBase;
use MathParser\StdMathParser;
use presentkim\mathparser\command\CommandListener;
use presentkim\mathparser\util\Translation;

class MathParser extends PluginBase{

    /** @var self */
    private static $instance = null;

    /** @var string */
    public static $prefix = '';

    /** @return self */
    public static function getInstance() : self{
        return self::$instance;
    }

    /**
     * @param string $expression
     *
     * @param array  $variables
     *
     * @return float
     */
    public static function parse(string $expression, array $variables = []) : float{
        $parser = new StdMathParser();
        $AST = $parser->parse($expression);

        $evaluator = new Evaluator($variables);
        return (float) $AST->accept($evaluator);
    }

    /** @var PluginCommand */
    private $command = null;

    public function onLoad() : void{
        if (self::$instance === null) {
            self::$instance = $this;
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

        self::$prefix = Translation::translate('prefix');
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
