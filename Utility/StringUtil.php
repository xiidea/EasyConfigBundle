<?php

namespace Xiidea\EasyConfigBundle\Utility;

abstract class StringUtil
{
    public static function getLabelFromClass($class)
    {
        return ucfirst(static::humanize(static::getClassBaseName($class)));
    }

    public static function humanize($text)
    {
        $text = preg_replace('/([A-Z]+)([A-Z][a-z])/', '\\1 \\2', $text);
        $text = preg_replace('/([a-z\d])([A-Z])/', '\\1 \\2', $text);
        $text = preg_replace('~\bdont\b~', 'don\'t', $text);

        return mb_strtolower($text, 'UTF-8');
    }

    public static function getClassBaseName($class)
    {
        $class = is_object($class) ? get_class($class) : $class;

        return basename(str_replace('\\', '/', $class));
    }
}
