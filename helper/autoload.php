<?php

spl_autoload_register(function ($class) {
    static $classes = NULL;
    static $path = NULL;

    if ($path === NULL) {
        $path = dirname(__FILE__) . DIRECTORY_SEPARATOR . '..';

        $traverse = function (&$paths, $dir) use ( &$traverse, $path ) {
            $files = glob($dir . DS . '*.php');
            foreach ($files as $f) {
                $n = strtolower(
                    str_replace(DIRECTORY_SEPARATOR, '\\', str_replace('.php', '', str_replace($path . DIRECTORY_SEPARATOR, '', $f))));
                $paths[$n] = $f;
            }
            $dirs = glob($dir . DIRECTORY_SEPARATOR . '*', GLOB_ONLYDIR);
            foreach ($dirs as $d) {
                $traverse($paths, $d);
            }
        };

        $classes = array();
        $traverse($classes, $path . DIRECTORY_SEPARATOR . 'client');
        $traverse($classes, $path . DIRECTORY_SEPARATOR . 'api');
        $traverse($classes, $path . DIRECTORY_SEPARATOR . 'configurator');
    }

    $cn = strtolower(str_replace('LangridSettingClient\\', '', $class));

    if (isset($classes[$cn])) {
        require $classes[$cn];
    }
});
