<?php
// app/helpers/Env.php

namespace App\Helpers;

class Env
{
    public static function load($path)
    {
        if (!file_exists($path)) {
            return;
        }

        $lines = file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        foreach ($lines as $line) {
            if (strpos(trim($line), '#') === 0) {
                continue;
            }

            list($name, $value) = explode('=', $line, 2);
            $name = trim($name);
            $value = trim($value);

            if (!array_key_exists($name, $_SERVER) && !array_key_exists($name, $_ENV)) {
                putenv(sprintf('%s=%s', $name, $value));
                $_ENV[$name] = $value;
                $_SERVER[$name] = $value;
            }
        }
    }

    public static function get($key, $default = null)
    {
        $value = getenv($key);
        if ($value === false) {
            // Fallback for some server environments
            if (isset($_SERVER[$key])) {
                return $_SERVER[$key];
            }
            if (isset($_ENV[$key])) {
                return $_ENV[$key];
            }
            return $default;
        }
        return $value;
    }
}
