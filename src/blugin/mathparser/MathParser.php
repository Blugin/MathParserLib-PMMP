<?php

declare(strict_types=1);

namespace blugin\mathparser;

use pocketmine\command\{
  Command, PluginCommand, CommandExecutor, CommandSender
};
use pocketmine\plugin\PluginBase;
use MathParser\StdMathParser;
use MathParser\Interpreting\Evaluator;
use blugin\mathparser\lang\PluginLang;

class MathParser extends PluginBase implements CommandExecutor{

    /** @var MathParser */
    private static $instance = null;

    /** @return MathParser */
    public static function getInstance() : MathParser{
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

    /** @var PluginLang */
    private $language;

    public function onLoad() : void{
        self::$instance = $this;
    }

    public function onEnable() : void{
        $dataFolder = $this->getDataFolder();
        if (!file_exists($dataFolder)) {
            mkdir($dataFolder, 0777, true);
        }
        $this->language = new PluginLang($this);

        if ($this->command !== null) {
            $this->getServer()->getCommandMap()->unregister($this->command);
        }
        $this->command = new PluginCommand($this->language->translate('commands.math'), $this);
        $this->command->setPermission('math.cmd');
        $this->command->setDescription($this->language->translate('commands.math.description'));
        $this->command->setUsage($this->language->translate('commands.math.usage'));
        if (is_array($aliases = $this->language->getArray('commands.math.aliases'))) {
            $this->command->setAliases($aliases);
        }
        $this->getServer()->getCommandMap()->register('mathparser', $this->command);
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
                $sender->sendMessage($this->language->translate('commands.math.success', [self::parse($expression)]));
            } catch (\Exception $exception){
                $sender->sendMessage($this->language->translate('commands.math.failure', [$exception->getMessage()]));
            }
            return true;
        }
        return false;
    }

    /**
     * @param string $name = ''
     *
     * @return PluginCommand
     */
    public function getCommand(string $name = '') : PluginCommand{
        return $this->command;
    }

    /**
     * @return PluginLang
     */
    public function getLanguage() : PluginLang{
        return $this->language;
    }

    /**
     * @return string
     */
    public function getSourceFolder() : string{
        $pharPath = \Phar::running();
        if (empty($pharPath)) {
            return dirname(__FILE__, 4) . DIRECTORY_SEPARATOR;
        } else {
            return $pharPath . DIRECTORY_SEPARATOR;
        }
    }
}
