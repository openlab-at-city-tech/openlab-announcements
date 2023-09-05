<?php

// autoload_real.php @generated by Composer

class ComposerAutoloaderInit610ceb52e3b96ffbdbb730a09c022894
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

        spl_autoload_register(array('ComposerAutoloaderInit610ceb52e3b96ffbdbb730a09c022894', 'loadClassLoader'), true, true);
        self::$loader = $loader = new \Composer\Autoload\ClassLoader(\dirname(__DIR__));
        spl_autoload_unregister(array('ComposerAutoloaderInit610ceb52e3b96ffbdbb730a09c022894', 'loadClassLoader'));

        require __DIR__ . '/autoload_static.php';
        call_user_func(\Composer\Autoload\ComposerStaticInit610ceb52e3b96ffbdbb730a09c022894::getInitializer($loader));

        $loader->register(true);

        return $loader;
    }
}