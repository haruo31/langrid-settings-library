<?php

spl_autoload_register(function ($class) {
    static $classes = NULL;
    static $path = NULL;

    if ($path === NULL) {
        $path = dirname(__FILE__) . '/..';

        $traverse = function (&$paths, $dir) use ( &$traverse, $path ) {
            $files = glob($dir . '/*.php');
            foreach ($files as $f) {
                $n = strtolower(
                    str_replace(DIRECTORY_SEPARATOR, '\\', str_replace('.php', '', str_replace($path . DIRECTORY_SEPARATOR, '', $f))));
                $paths[$n] = $f;
            }
            $dirs = glob($dir . '/*', GLOB_ONLYDIR);
            foreach ($dirs as $d) {
                $traverse($paths, $d);
            }
        };

        $classes = array();
        $traverse($classes, $path . '/client');
        $traverse($classes, $path . '/api');
        $traverse($classes, $path . '/configurator');
    }

    $cn = strtolower(str_replace('LangridSettingClient\\', '', $class));

    if (isset($classes[$cn])) {
        require $classes[$cn];
    }
});
