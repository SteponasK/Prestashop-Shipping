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

 if (!defined('_PS_VERSION_')) {
    exit;
}

use Invertus\AcademyERPIntegration\Config\TabConfig;
use Invertus\AcademyERPIntegration\Install\Installer;
use Invertus\AcademyERPIntegration\Install\Uninstaller;
use Invertus\AcademyERPIntegration\Config\Config;

class AcademyERPIntegration extends Module
{
    public function __construct()
    {
        $this->tab = 'other_modules';
        $this->name = 'academyerpintegration';
        $this->version = '1.0.0';
        $this->author = 'Invertus';

        parent::__construct();
        $this->autoLoad();
        $this->displayName = $this->l('Academy ERP integration');
        $this->description = $this->l('Print labels of shipment\'s, which can be saved to a Database with a press of a button');
        $this->need_instance = 1;
    }

    /**
     * {@inheritdoc}
     */
    public function getTabs(): array
    {
        $tabConfig = new TabConfig();

        return $tabConfig->getTabs();
    }

    /**
     * {@inheritdoc}
     */
    public function getContent(): void
    {
        $redirectLink = $this->context->link->getAdminLink(Config::SETTINGS_CONTROLLER);
        Tools::redirectAdmin($redirectLink);
    }

    /**
     * {@inheritdoc}
     */
    public function install(): bool
    {
        /** Symfony container not used intentionally */
        $installer = new Installer($this);

        return parent::install() && $installer->init();
    }

    /**
     * {@inheritdoc}
     */
    public function uninstall(): bool
    {
        /** Symfony container not used intentionally */
        $unInstaller = new Uninstaller($this);

        return parent::uninstall() && $unInstaller->init();
    }


    public function hookActionDispatcherBefore(): void
    {
        $this->autoLoad();
    }

    /**
     * Autoload's project files from /src directory
     */
    private function autoLoad(): void
    {
        $autoLoadPath = $this->getLocalPath() . 'vendor/autoload.php';

        require_once $autoLoadPath;
    }

    public function hookDisplayAdminOrderMain(array $params)
    {
       $html ='<div class="card mt-2">
       <div class="card-header">
         <h3 class="card-header-title">
           Save to Database
         </h3>
       </div>
     
       <div class="card-body">
         
         <div class="form-group row type-hidden ">
         <div class="col-sm"></div>

       </div><table class="table">
           <thead>
             <tr>
               <th class="table-head-firstName">First Name</th>
               <th class="table-head-lastName">Last Name</th>
               <th class="table-head-company">Company</th>
               <th class="table-head-country">Country</th>
               <th class="table-head-save">Save Data</th>
               <th></th>
             </tr>
           </thead>
           <tbody>
                 <tr class="d-print-none">
               <td><input type="text" id="firstName" name="firstName" class="form-control"></td>
               <td><input type="text" id="lastName" name="lastName" class="form-control"></td>
               <td><input type="text" id="company" name="company" class="form-control"></td>
               <td><input type="text" id="country" name="country" class="form-control"></td>
               
               <td>
                 <button type="submit" class="btn btn-primary btn-sm">Save Data</button>
               </td>
           </tr>
           </tbody>
           
         </tbody>

                    <thead>
           <tr>
             <th class="table-head-address1">Address 1</th>
             <th class="table-head-address2">Address 2</th>
             <th class="table-head-postcode">Postcode</th>
             <th class="table-head-city">City</th>
             <th class="table-head-print">Print Label</th>
             <th></th>
           </tr>
         </thead>
         <tbody>
               <tr class="d-print-none">
             <td><input type="text" id="address1" name="address1" class="form-control"></td>
             <td><input type="text" id="address2" name="address2" class="form-control"></td>
             <td><input type="text" id="postcode" name="postcode" class="form-control"></td>
             <td><input type="text" id="city" name="city" class="form-control"></td>
             
             <td>
               <button type="submit" class="btn btn-primary btn-sm">Print Label</button>
             </td>
         </tr>
         </tbody>
         <thead>
         <tr>
           <th class="table-head-phone">Phone</th>
           <th class="table-head-phoneMobile">Phone Mobile</th>
         </tr>
       </thead>
       <tbody>
             <tr class="d-print-none">
           <td><input type="text" id="phone" name="phone" class="form-control"></td>
           <td><input type="text" id="phoneMobile" name="phoneMobile" class="form-control"></td>
       </tr>
       </tbody>
         </table>
     
           </div>
     </div>';

        return $html;
    }
}
