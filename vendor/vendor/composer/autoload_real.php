<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInitef31b9aa808d3b0f4f9a1e7418e2ca5c
{
    private static $loader;

    public static function loadClassLoader($class)
    {
        if ('Composer\Autoload\ClassLoader' === $class) {
            require __DIR__ . '/ClassLoader.php';
        }
    }

    /**
     * @return \Composer\Autoload\ClassLoader
     */
    public static function getLoader()
    {
        if (null !== self::$loader) {
            return self::$loader;
        }

        require __DIR__ . '/platform_check.php';

        spl_autoload_register(array('ComposerAutoloaderInitef31b9aa808d3b0f4f9a1e7418e2ca5c', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInitef31b9aa808d3b0f4f9a1e7418e2ca5c', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInitef31b9aa808d3b0f4f9a1e7418e2ca5c::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}