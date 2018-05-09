<img src="./assets/icon/index.svg" height="256" width="256">  

[![License](https://img.shields.io/github/license/PMMPPlugin/MathParserLib.svg?label=License)](LICENSE)
[![Release](https://img.shields.io/github/release/PMMPPlugin/MathParserLib.svg?label=Release)](https://github.com/PMMPPlugin/MathParserLib/releases/latest)
[![Download](https://img.shields.io/github/downloads/PMMPPlugin/MathParserLib/total.svg?label=Download)](https://github.com/PMMPPlugin/MathParserLib/releases/latest)


A plugin for math-parser lib for PocketMine-MP
(lib from https://github.com/mossadal/math-parser)

## For developers
  
#### 1. Add dependency property into plugin.yml (in two way)  

|                   | code                            |
| ----------------- | ------------------------------- |
| strong dependency | `depend: ['MathParserLib']`     |
| soft dependency   | `softdepend: ['MathParserLib']` |  
  
#### 2. Import blugin\mathparser\MathParser
````PHP  
use blugin\mathparser\MathParser;
````  
  
#### 3. Use MathParser 
````PHP  
MathParser::parse((string $expression, array $variables = []) : float
````  
  
#### 4. Example
````PHP  
echo MathParser::parse('a^2+10', ['a' => 10]); // Result : 110
````  
When you select soft dependency, use like this : 
````PHP  
if (class_exists(MathParser::class)) {
    echo MathParser::parse('a^2+10', ['a' => 10]); // Result : 110
}else{
    // Do something when not exists MathParser
}
````  
  
See instance in [VirtualChest-PMMP/PriceSubCommand](https://github.com/Blugin/VirtualChest-PMMP/blob/master/src/blugin/virtualchest/command/subcommands/PriceSubCommand.php#L31-L34)
  
  
  
  
## Command
| command | arguments      | description  |
| ------- | -------------- | ------------ |
| /math   | \<expression\> | main command |
  
  
  
  
## Permission
| permission  | default | description  |
| ----------- | ------- | ------------ |
| math.cmd    | true    | main command |
