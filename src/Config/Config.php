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
 * Class Config - responsible for module main configurations
 */
class Config
{
    /**
     * Disable object initiation
     */
    private function __construct()
    {
    }

    /**
     * Controller configurations
     */
    public const PARENT_CONTROLLER = 'AdminParentModulesSf';
    public const TAB_CONTROLLER = 'AdminAcademyERPIntegrationTab';
    public const SETTINGS_CONTROLLER = 'AdminAcademyERPIntegrationSettings';
}
