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

class AcademyERPIntegration extends CarrierModule
{
    public function __construct()
    {
        $this->tab = 'other_modules';
        $this->name = 'academyerpintegration';
        $this->version = '1.0.0';
        $this->author = 'Invertus';

        parent::__construct();
        $this->autoLoad();
        $this->displayName = $this->l('Shipment PDF label Module');
        $this->description = $this->l('Save shipments to the database, then generate a PDF file of a given shipment');
        $this->need_instance = 1;
    }
    public function getOrderShippingCost($cart, $shippingCost)
    {
        // Implement Logic
    }

    public function getOrderShippingCostExternal($cart)
    {
        return $this->getOrderShippingCost($cart, 0);
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
        $order = new Order($params['id_order']);
        $carrier = Carrier::getCarrierByReference($order->getIdOrderCarrier());
        if(!($carrier instanceof Carrier)){
            return '';
        }
        $externalModuleName = $carrier->external_module_name;
        
        if(is_string($externalModuleName) && $externalModuleName == $this->name){
            $twig = $this->getContainer()->get('twig');
            $address = new Address($order->id_address_delivery);
        
        $bearerToken =  Configuration::get('ERP_API_KEY');

        $twig = $this->getContainer()->get('twig');
        return $twig->render('@Modules/academyerpintegration/views/Admin/ModuleTable.html.twig',
        ['bearerToken' => $bearerToken,
        'company' => $address->company,
        'firstName' => $address->firstname,
        'lastName' => $address->lastname,
        'city' => $address->city,
        'country' => $address->country,
        'address1' => $address->address1,
        'address2' => $address->address2,
        'postcode' => $address->postcode,
        'phone' => $address->phone,
        'phoneMobile' => $address->phone_mobile,]);
        }
    }

    public function hookActionAdminControllerSetMedia(array $params)
    {
        $this->context->controller->addJS(
            $this->getPathUri() . 'views/js/Shipping.js'
        );
    }       
}
