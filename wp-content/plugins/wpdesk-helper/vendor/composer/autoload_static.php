<?php

// autoload_static.php @generated by Composer

namespace Composer\Autoload;

class ComposerStaticInit76d84459a69c1b51227969a5d4e90c83
{
    public static $files = array (
        '0509b34a4bd7aebefeac629c9dc8a978' => __DIR__ . '/..' . '/wpdesk/wp-notice/src/WPDesk/notice-functions.php',
    );

    public static $prefixLengthsPsr4 = array (
        'W' => 
        array (
            'WPDesk\\PluginBuilder\\' => 21,
            'WPDesk\\Notice\\' => 14,
        ),
        'P' => 
        array (
            'Psr\\Log\\' => 8,
        ),
    );

    public static $prefixDirsPsr4 = array (
        'WPDesk\\PluginBuilder\\' => 
        array (
            0 => __DIR__ . '/..' . '/wpdesk/wp-builder/src',
        ),
        'WPDesk\\Notice\\' => 
        array (
            0 => __DIR__ . '/..' . '/wpdesk/wp-notice/src/WPDesk/Notice',
        ),
        'Psr\\Log\\' => 
        array (
            0 => __DIR__ . '/..' . '/psr/log/Psr/Log',
        ),
    );

    public static $classMap = array (
        'Psr\\Log\\AbstractLogger' => __DIR__ . '/..' . '/psr/log/Psr/Log/AbstractLogger.php',
        'Psr\\Log\\InvalidArgumentException' => __DIR__ . '/..' . '/psr/log/Psr/Log/InvalidArgumentException.php',
        'Psr\\Log\\LogLevel' => __DIR__ . '/..' . '/psr/log/Psr/Log/LogLevel.php',
        'Psr\\Log\\LoggerAwareInterface' => __DIR__ . '/..' . '/psr/log/Psr/Log/LoggerAwareInterface.php',
        'Psr\\Log\\LoggerAwareTrait' => __DIR__ . '/..' . '/psr/log/Psr/Log/LoggerAwareTrait.php',
        'Psr\\Log\\LoggerInterface' => __DIR__ . '/..' . '/psr/log/Psr/Log/LoggerInterface.php',
        'Psr\\Log\\LoggerTrait' => __DIR__ . '/..' . '/psr/log/Psr/Log/LoggerTrait.php',
        'Psr\\Log\\NullLogger' => __DIR__ . '/..' . '/psr/log/Psr/Log/NullLogger.php',
        'Psr\\Log\\Test\\DummyTest' => __DIR__ . '/..' . '/psr/log/Psr/Log/Test/LoggerInterfaceTest.php',
        'Psr\\Log\\Test\\LoggerInterfaceTest' => __DIR__ . '/..' . '/psr/log/Psr/Log/Test/LoggerInterfaceTest.php',
        'Psr\\Log\\Test\\TestLogger' => __DIR__ . '/..' . '/psr/log/Psr/Log/Test/TestLogger.php',
        'WPDesk\\Notice\\AjaxHandler' => __DIR__ . '/..' . '/wpdesk/wp-notice/src/WPDesk/Notice/AjaxHandler.php',
        'WPDesk\\Notice\\Factory' => __DIR__ . '/..' . '/wpdesk/wp-notice/src/WPDesk/Notice/Factory.php',
        'WPDesk\\Notice\\Notice' => __DIR__ . '/..' . '/wpdesk/wp-notice/src/WPDesk/Notice/Notice.php',
        'WPDesk\\Notice\\PermanentDismissibleNotice' => __DIR__ . '/..' . '/wpdesk/wp-notice/src/WPDesk/Notice/PermanentDismissibleNotice.php',
        'WPDesk\\PluginBuilder\\BuildDirector\\LegacyBuildDirector' => __DIR__ . '/..' . '/wpdesk/wp-builder/src/BuildDirector/LegacyBuildDirector.php',
        'WPDesk\\PluginBuilder\\Builder\\AbstractBuilder' => __DIR__ . '/..' . '/wpdesk/wp-builder/src/Builder/AbstractBuilder.php',
        'WPDesk\\PluginBuilder\\Builder\\InfoBuilder' => __DIR__ . '/..' . '/wpdesk/wp-builder/src/Builder/InfoBuilder.php',
        'WPDesk\\PluginBuilder\\Plugin\\AbstractPlugin' => __DIR__ . '/..' . '/wpdesk/wp-builder/src/Plugin/AbstractPlugin.php',
        'WPDesk\\PluginBuilder\\Plugin\\ActivationTracker' => __DIR__ . '/..' . '/wpdesk/wp-builder/src/Plugin/ActivationTracker.php',
        'WPDesk\\PluginBuilder\\Plugin\\Hookable' => __DIR__ . '/..' . '/wpdesk/wp-builder/src/Plugin/Hookable.php',
        'WPDesk\\PluginBuilder\\Plugin\\HookableCollection' => __DIR__ . '/..' . '/wpdesk/wp-builder/src/Plugin/HookableCollection.php',
        'WPDesk\\PluginBuilder\\Plugin\\HookableParent' => __DIR__ . '/..' . '/wpdesk/wp-builder/src/Plugin/HookableParent.php',
        'WPDesk\\PluginBuilder\\Plugin\\HookablePluginDependant' => __DIR__ . '/..' . '/wpdesk/wp-builder/src/Plugin/HookablePluginDependant.php',
        'WPDesk\\PluginBuilder\\Plugin\\PluginAccess' => __DIR__ . '/..' . '/wpdesk/wp-builder/src/Plugin/PluginAccess.php',
        'WPDesk\\PluginBuilder\\Plugin\\TemplateLoad' => __DIR__ . '/..' . '/wpdesk/wp-builder/src/Plugin/TemplateLoad.php',
        'WPDesk\\PluginBuilder\\Storage\\Exception\\ClassAlreadyExists' => __DIR__ . '/..' . '/wpdesk/wp-builder/src/Storage/Exception/ClassAlreadyExists.php',
        'WPDesk\\PluginBuilder\\Storage\\Exception\\ClassNotExists' => __DIR__ . '/..' . '/wpdesk/wp-builder/src/Storage/Exception/ClassNotExists.php',
        'WPDesk\\PluginBuilder\\Storage\\PluginStorage' => __DIR__ . '/..' . '/wpdesk/wp-builder/src/Storage/PluginStorage.php',
        'WPDesk\\PluginBuilder\\Storage\\StaticStorage' => __DIR__ . '/..' . '/wpdesk/wp-builder/src/Storage/StaticStorage.php',
        'WPDesk\\PluginBuilder\\Storage\\StorageFactory' => __DIR__ . '/..' . '/wpdesk/wp-builder/src/Storage/StorageFactory.php',
        'WPDesk_API_KEY' => __DIR__ . '/../..' . '/classes/class-wc-key-api.php',
        'WPDesk_API_MENU' => __DIR__ . '/../..' . '/classes/class-wc-api-manager-menu.php',
        'WPDesk_API_Manager' => __DIR__ . '/../..' . '/classes/class-wc-api-manager.php',
        'WPDesk_API_Password_Management' => __DIR__ . '/../..' . '/classes/class-wc-api-manager-passwords.php',
        'WPDesk_Helper' => __DIR__ . '/../..' . '/classes/class-helper.php',
        'WPDesk_Helper_Debug_Log' => __DIR__ . '/../..' . '/classes/class-wpdesk-helper-debug-log.php',
        'WPDesk_Helper_License_Activator' => __DIR__ . '/../..' . '/classes/class-wpdesk-helper-license-activator.php',
        'WPDesk_Helper_List_Table' => __DIR__ . '/../..' . '/classes/views/class-wpdesk-helper-list-table.php',
        'WPDesk_Logger' => __DIR__ . '/../..' . '/inc/wpdesk-logger.php',
        'WPDesk_Logger_Factory' => __DIR__ . '/../..' . '/inc/wpdesk-logger-factory.php',
        'WPDesk_Tracker' => __DIR__ . '/../..' . '/inc/wpdesk-tracker/class-wpdesk-tracker.php',
        'WPDesk_Tracker_Data_Provider' => __DIR__ . '/../..' . '/inc/wpdesk-tracker/data_provider/class-wpdesk-tracker-data-provider.php',
        'WPDesk_Tracker_Data_Provider_Gateways' => __DIR__ . '/../..' . '/inc/wpdesk-tracker/data_provider/class-wpdesk-tracker-data-provider-gateways.php',
        'WPDesk_Tracker_Data_Provider_Identification' => __DIR__ . '/../..' . '/inc/wpdesk-tracker/data_provider/class-wpdesk-tracker-data-provider-identification.php',
        'WPDesk_Tracker_Data_Provider_Identification_Gdpr' => __DIR__ . '/../..' . '/inc/wpdesk-tracker/data_provider/class-wpdesk-tracker-data-provider-identification-gdpr.php',
        'WPDesk_Tracker_Data_Provider_Jetpack' => __DIR__ . '/../..' . '/inc/wpdesk-tracker/data_provider/class-wpdesk-tracker-data-provider-jetpack.php',
        'WPDesk_Tracker_Data_Provider_License_Emails' => __DIR__ . '/../..' . '/inc/wpdesk-tracker/data_provider/class-wpdesk-tracker-data-provider-license-emails.php',
        'WPDesk_Tracker_Data_Provider_Orders' => __DIR__ . '/../..' . '/inc/wpdesk-tracker/data_provider/class-wpdesk-tracker-data-provider-orders.php',
        'WPDesk_Tracker_Data_Provider_Orders_Country' => __DIR__ . '/../..' . '/inc/wpdesk-tracker/data_provider/class-wpdesk-tracker-data-provider-orders-country.php',
        'WPDesk_Tracker_Data_Provider_Orders_Month' => __DIR__ . '/../..' . '/inc/wpdesk-tracker/data_provider/class-wpdesk-tracker-data-provider-orders-month.php',
        'WPDesk_Tracker_Data_Provider_Plugins' => __DIR__ . '/../..' . '/inc/wpdesk-tracker/data_provider/class-wpdesk-tracker-data-provider-plugins.php',
        'WPDesk_Tracker_Data_Provider_Products' => __DIR__ . '/../..' . '/inc/wpdesk-tracker/data_provider/class-wpdesk-tracker-data-provider-products.php',
        'WPDesk_Tracker_Data_Provider_Products_Variations' => __DIR__ . '/../..' . '/inc/wpdesk-tracker/data_provider/class-wpdesk-tracker-data-provider-products-variations.php',
        'WPDesk_Tracker_Data_Provider_Server' => __DIR__ . '/../..' . '/inc/wpdesk-tracker/data_provider/class-wpdesk-tracker-data-provider-server.php',
        'WPDesk_Tracker_Data_Provider_Settings' => __DIR__ . '/../..' . '/inc/wpdesk-tracker/data_provider/class-wpdesk-tracker-data-provider-settings.php',
        'WPDesk_Tracker_Data_Provider_Shipping_Classes' => __DIR__ . '/../..' . '/inc/wpdesk-tracker/data_provider/class-wpdesk-tracker-data-provider-shipping-classes.php',
        'WPDesk_Tracker_Data_Provider_Shipping_Methods' => __DIR__ . '/../..' . '/inc/wpdesk-tracker/data_provider/class-wpdesk-tracker-data-provider-shipping-methods.php',
        'WPDesk_Tracker_Data_Provider_Shipping_Methods_Zones' => __DIR__ . '/../..' . '/inc/wpdesk-tracker/data_provider/class-wpdesk-tracker-data-provider-shipping-methods-zones.php',
        'WPDesk_Tracker_Data_Provider_Templates' => __DIR__ . '/../..' . '/inc/wpdesk-tracker/data_provider/class-wpdesk-tracker-data-provider-templates.php',
        'WPDesk_Tracker_Data_Provider_Theme' => __DIR__ . '/../..' . '/inc/wpdesk-tracker/data_provider/class-wpdesk-tracker-data-provider-theme.php',
        'WPDesk_Tracker_Data_Provider_User_Agent' => __DIR__ . '/../..' . '/inc/wpdesk-tracker/data_provider/class-wpdesk-tracker-data-provider-user-agent.php',
        'WPDesk_Tracker_Data_Provider_Users' => __DIR__ . '/../..' . '/inc/wpdesk-tracker/data_provider/class-wpdesk-tracker-data-provider-users.php',
        'WPDesk_Tracker_Data_Provider_Wordpress' => __DIR__ . '/../..' . '/inc/wpdesk-tracker/data_provider/class-wpdesk-tracker-data-provider-wordpress.php',
        'WPDesk_Tracker_Factory' => __DIR__ . '/../..' . '/inc/wpdesk-tracker/class-wpdesk-tracker-factory.php',
        'WPDesk_Tracker_Sender' => __DIR__ . '/../..' . '/inc/wpdesk-tracker/sender/class-wpdesk-tracker-sender.php',
        'WPDesk_Tracker_Sender_Exception_WpError' => __DIR__ . '/../..' . '/inc/wpdesk-tracker/sender/Exception/class-wpdesk-tracker-sender-exception-wperror.php',
        'WPDesk_Tracker_Sender_Logged' => __DIR__ . '/../..' . '/inc/wpdesk-tracker/sender/class-wpdesk-tracker-sender-logged.php',
        'WPDesk_Tracker_Sender_Wordpress_To_WPDesk' => __DIR__ . '/../..' . '/inc/wpdesk-tracker/sender/class-wpdesk-tracker-sender-wordpress-to-wpdesk.php',
        'WPDesk_Update_API_Check' => __DIR__ . '/../..' . '/classes/class-wc-plugin-update.php',
    );

    public static function getInitializer(ClassLoader $loader)
    {
        return \Closure::bind(function () use ($loader) {
            $loader->prefixLengthsPsr4 = ComposerStaticInit76d84459a69c1b51227969a5d4e90c83::$prefixLengthsPsr4;
            $loader->prefixDirsPsr4 = ComposerStaticInit76d84459a69c1b51227969a5d4e90c83::$prefixDirsPsr4;
            $loader->classMap = ComposerStaticInit76d84459a69c1b51227969a5d4e90c83::$classMap;

        }, null, ClassLoader::class);
    }
}
