<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit34d1b33c8b49cf1e797c758656504d62
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
            $loader->prefixLengthsPsr4 = ComposerStaticInit34d1b33c8b49cf1e797c758656504d62::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit34d1b33c8b49cf1e797c758656504d62::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit34d1b33c8b49cf1e797c758656504d62::$classMap;

        }, null, ClassLoader::class);
    }
}
