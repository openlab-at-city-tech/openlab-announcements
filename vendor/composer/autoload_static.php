<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit610ceb52e3b96ffbdbb730a09c022894
{
    public static $prefixLengthsPsr4 = array (
        'O' => 
        array (
            'OpenLab\\Announcements\\' => 22,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'OpenLab\\Announcements\\' => 
        array (
            0 => __DIR__ . '/../..' . '/classes',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit610ceb52e3b96ffbdbb730a09c022894::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit610ceb52e3b96ffbdbb730a09c022894::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit610ceb52e3b96ffbdbb730a09c022894::$classMap;

        }, null, ClassLoader::class);
    }
}
