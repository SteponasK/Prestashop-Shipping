<?php
/**
 * NOTICE OF LICENSE
 *
 * @author    INVERTUS, UAB www.invertus.eu <support@invertus.eu>
 * @copyright Copyright (c) permanent, INVERTUS, UAB
 * @license   MIT
 * @see       /LICENSE
 *
 *  International Registered Trademark & Property of INVERTUS, UAB
 */

declare(strict_types=1);

namespace Invertus\AcademyERPIntegration\Config;

/**
 * Class InstallConfig - responsible for module installation configurations
 */
final class InstallConfig
{
    /**
     * Disable object initiation
     */
    private function __construct()
    {
    }

    /**
     * Hook list to be used in module
     */
    public static function getHookList(): array
    {
        return ['actionDispatcherBefore', 'displayAdminOrderMain']; // Hook list
    }

    /**
     * Configuration list to be used in module
     */
    public static function getConfigList(): array
    {
        return []; // Config associative list, config values will be initiated on install
    }
}
