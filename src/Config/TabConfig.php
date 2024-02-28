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

use AcademyERPIntegration;

/**
 * Class TabConfig - responsible for module tab configurations
 */
class TabConfig
{
    public function getTabs(): array
    {
        return [
            [
                'name' => 'AcademyERPIntegration',
                'parent_class_name' => Config::PARENT_CONTROLLER,
                'class_name' => Config::TAB_CONTROLLER,
                'visible' => false,
            ],
            [
                'name' => 'Information',
                'parent_class_name' => Config::TAB_CONTROLLER,
                'class_name' => Config::SETTINGS_CONTROLLER,
            ],
        ];
    }
}
