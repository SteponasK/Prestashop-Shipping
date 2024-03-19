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
use Invertus\AcademyERPIntegration\Config\InstallConfig;
use AcademyERPIntegration;

/**
 * Class Uninstaller - responsible for module installation process
 */
class Uninstaller extends AbstractInstaller
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
        $this->uninstallConfiguration();

        if (!$this->uninstallDb()) {
            return false;
        }
        if(!$this->deleteCarriers())
        {
            return false;
        }

        return true;
    }

    protected function deleteCarriers(): bool
    {
        $result = true;
        $results = Db::getInstance()->executeS('SELECT id_carrier FROM `' . _DB_PREFIX_ .
            'carrier` where external_module_name = "' . pSQL($this->module->module_name) . '"');
        $idCarriers = array();
        foreach ($results as $r) {
            $idCarriers[] = $r['id_carrier'];
        }
        if (!empty($idCarriers)) {
            $result &= Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ .
                'carrier` where id_carrier IN (' . pSQL(implode(',', array_map('intval', $idCarriers))) . ')');
            $result &= Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ .
                'carrier_zone` where id_carrier IN (' . pSQL(implode(',', array_map('intval', $idCarriers))) . ')');
            $result &= Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ .
                'delivery` where id_carrier IN (' . pSQL(implode(',', array_map('intval', $idCarriers))) . ')');
            $result &= Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ .
                'range_price` where id_carrier IN (' . pSQL(implode(',', array_map('intval', $idCarriers))) . ')');
            $result &= Db::getInstance()->execute('DELETE FROM `' . _DB_PREFIX_ .
                'range_weight` where id_carrier IN (' . pSQL(implode(',', array_map('intval', $idCarriers))) . ')');
        }
        return $result;
    }
    private function uninstallConfiguration(): void
    {
        $configuration = InstallConfig::getConfigList();

        if (empty($configuration)) {
            return;
        }

        foreach (array_keys($configuration) as $name) {
            if (!Configuration::deleteByName($name)) {
                continue;
            }
        }
    }

    /**
     * Executes sql in uninstall.sql file which is used for uninstalling
     *
     * @return bool
     *
     * @throws \Exception
     */
    private function uninstallDb(): bool
    {
        $uninstallSqlFileName = $this->module->getLocalPath() . 'sql/uninstall/uninstall.sql';
        if (!file_exists($uninstallSqlFileName)) {
            return true;
        }

        $database = Db::getInstance();
        $sqlStatements = $this->getSqlStatements($uninstallSqlFileName);

        return $database->execute($sqlStatements);
    }
}
