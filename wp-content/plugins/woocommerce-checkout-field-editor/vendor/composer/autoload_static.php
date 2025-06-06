<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit082b0eae9513d422dddc6010c2986dee
{
    public static $prefixLengthsPsr4 = array (
        'C' => 
        array (
            'Composer\\Installers\\' => 20,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'Composer\\Installers\\' => 
        array (
            0 => __DIR__ . '/..' . '/composer/installers/src/Composer/Installers',
        ),
    );

    public static $classMap = array (
        'Composer\\InstalledVersions' => __DIR__ . '/..' . '/composer/InstalledVersions.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit082b0eae9513d422dddc6010c2986dee::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit082b0eae9513d422dddc6010c2986dee::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit082b0eae9513d422dddc6010c2986dee::$classMap;

        }, null, ClassLoader::class);
    }
}
