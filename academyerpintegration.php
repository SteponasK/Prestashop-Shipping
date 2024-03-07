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

use Invertus\AcademyERPIntegration\Config\TabConfig;
use Invertus\AcademyERPIntegration\Install\Installer;
use Invertus\AcademyERPIntegration\Install\Uninstaller;
use Invertus\AcademyERPIntegration\Config\Config;
use PrestaShop\PrestaShop\Core\Exception\ContainerNotFoundException;

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
        $this->displayName = $this->l('Academy ERP integration');
        $this->description = $this->l('This is module description');
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
     * @throws Exception
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
     * @param array $params
     * @throws ContainerNotFoundException
     */
    public function hookDisplayAdminOrderMain(array $params)
    {
        $twig = $this->getContainer()->get('twig');
        $order = new Order($params['id_order']);
        $externalModuleName = Carrier::getCarrierByReference($order->getIdOrderCarrier())->external_module_name;
        $address = new Address($order->id_address_delivery);

        if ($externalModuleName == $this->name)
        {
            return $twig->render(
                '@Modules/academyerpintegration/src/views/shipping_label.html.twig',
                [
                    'cName' => $address->company,
                    'fName' => $address->firstname,
                    'lName' => $address->lastname,
                    'city' => $address->city,
                    'country' => $address->country,
                    'address1' => $address->address1,
                    'address2' => $address->address2,
                    'postcode' => $address->postcode,
                    'phone' => $address->phone,
                    'mobile' => $address->phone_mobile
                ]);
        }
    }

    /**
     * Autoload's project files from /src directory
     */
    private function autoLoad(): void
    {
        $autoLoadPath = $this->getLocalPath() . 'vendor/autoload.php';

        require_once $autoLoadPath;
    }

    public function getOrderShippingCost($params, $shipping_cost)
    {
        // TODO: Implement getOrderShippingCost() method.
    }

    public function getOrderShippingCostExternal($params)
    {
        // TODO: Implement getOrderShippingCostExternal() method.
    }
}
