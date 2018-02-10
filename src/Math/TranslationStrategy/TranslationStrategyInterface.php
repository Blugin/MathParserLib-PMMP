<?php

namespace Math\TranslationStrategy;

/**
 * Translation strategy interface.
 *
 * @author Adrean Boyadzhiev (netforce) <adrean.boyadzhiev@gmail.com>
 * @website https://github.com/aboyadzhiev/php-math-parser
 * @license https://opensource.org/licenses/MIT MIT
 */
interface TranslationStrategyInterface
{
    public function translate(array $tokens);
}
