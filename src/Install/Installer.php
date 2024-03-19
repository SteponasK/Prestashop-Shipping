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

use Configuration;
use Db;
use Exception;
use Invertus\AcademyERPIntegration\Config\InstallConfig;
use AcademyERPIntegration;

/**
 * Class Installer - responsible for module installation process
 */
class Installer extends AbstractInstaller
{
    /**
     * @var AcademyERPIntegration
     */
    private $module;

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
     */
    public function init(): bool
    {
        $this->registerHooks();
        $this->installConfiguration();
        $this->installDb();

        if(!$this->installCarrier())
        {
            return false;
        }

        return true;
    }

    /**
     * @return bool
     *
     * @throws \Exception
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
     * @return bool
     *
     * @throws \Exception
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
     * @return bool
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
