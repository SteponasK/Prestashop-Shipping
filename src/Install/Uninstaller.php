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
use DbQuery;
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
        $query = new DbQuery();
        $query->select('id_carrier');
        $query->from('carrier', 'c');
        $query->where('c.external_module_name = "' . pSQL($this->module->name) . '"');
        $results = Db::getInstance()->executeS($query);
        
        foreach($results as $r){
            $carrier = new Carrier($r['id_carrier']);
            $carrier->delete();
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
