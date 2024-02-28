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

use Invertus\AcademyERPIntegration\Controller\AbstractAdminController;

/**
 * Class AdminAcademyERPIntegrationSettingsController - used for module settings
 */
class AdminAcademyERPIntegrationSettingsController extends AbstractAdminController
{
    public function __construct()
    {
        parent::__construct();

        $this->initOptionFields();
    }

    private function initOptionFields()
    {
        $this->fields_options = [
            'test_setting' => [
                'title' => $this->module->l('Test setting header'),
                'fields' => [
                    'AERP_TEST' => [
                        'title' => $this->module->l('Test setting'),
                        'type' => 'bool',
                    ],
                ],
                'submit' => [
                    'title' => $this->module->l('Save'),
                ],
            ],
        ];
    }
}
