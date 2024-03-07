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

namespace Invertus\AcademyERPIntegration\Install;

use Carrier;
use Configuration;
use Db;
use Exception;
use Invertus\AcademyERPIntegration\Config\InstallConfig;
use AcademyERPIntegration;
use Language;
use PrestaShop\PrestaShop\Adapter\Entity\Shop;
use PrestaShop\PrestaShop\Adapter\Entity\Zone;

/**
 * Class Installer - responsible for module installation process
 */
class Installer extends AbstractInstaller
{
    /**
     * @var AcademyERPIntegration
     */
    private AcademyERPIntegration $module;

    /**
     * @param AcademyERPIntegration $module
     * @param array $configuration
     */
    public function __construct(AcademyERPIntegration $module)
    {
        $this->module = $module;
    }

    /**
     * {@inheritdoc}
     * @throws Exception
     */
    public function init(): bool
    {

        $this->registerHooks();
        $this->installConfiguration();
        $this->installDb();
        if (!$this->installCarrier())
        {
            return false;
        };
        return true;
    }

    public function installCarrier(): bool
    {

        $carrier = New Carrier();
        $carrier->name = 'Academy Carrier';
        $carrier->active = true;
        $carrier->deleted = false;
        $carrier->is_module = true;
        $carrier->external_module_name = 'academyerpintegration';
        $carrier->shipping_handling = false;
        $carrier->shipping_external = true;
        $carrier->range_behavior = false;
        $carrier->need_range = true;
        $carrier->is_free = true;
        $carrier->setTaxRulesGroup(0);

        foreach (Language::getLanguages(false) as $language)
        {
            $carrier->delay[$language['id_lang']] = 'delay';
        }

        if (!$carrier->add())
        {
            return false;
        }

        foreach (Zone::getZones(true) as $zone)
        {
            $carrier->addZone($zone['id_zone']);
        }

        return true;
    }

    /**
     * @throws Exception
     */
    private function registerHooks(): void
    {
        $hooks = InstallConfig::getHookList();

        if (empty($hooks)) {
            return;
        }

        foreach ($hooks as $hookName) {
            if (!$this->module->registerHook($hookName)) {
                throw new Exception(
                    sprintf('Hook %s has not been installed.', $hookName)
                );
            }
        }
    }

    /**
     * Installs global settings
     *
     * @return void
     *
     * @throws Exception
     */
    private function installConfiguration(): void
    {
        $configuration = InstallConfig::getConfigList();

        if (empty($configuration)) {
            return;
        }

        foreach ($configuration as $name => $value) {
            if (!Configuration::updateValue($name, $value)) {
                throw new Exception(
                    sprintf('Configuration %s has not been installed.', $name)
                );
            }
        }
    }

    /**
     * Reads sql files and executes
     *
     * @return void
     * @throws Exception
     */
    private function installDb(): void
    {
        $installSqlFiles = glob($this->module->getLocalPath() . 'sql/install/*.sql');

        if (empty($installSqlFiles)) {
            return;
        }

        $database = Db::getInstance();

        foreach ($installSqlFiles as $sqlFile) {
            $sqlStatements = $this->getSqlStatements($sqlFile);

            try {
                $database->execute($sqlStatements);
            } catch (Exception $exception) {
                throw new Exception($exception->getMessage());
            }
        }
    }
}
