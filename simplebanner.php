<?php
/**
 * Copyright since 2007 Carmine Di Gruttola
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 * DISCLAIMER
 *
 * Do not edit or add to this file if you wish to upgrade PrestaShop to newer
 * versions in the future. If you wish to customize PrestaShop for your
 * needs please refer to http://www.prestashop.com for more information.
 *
 * @author    cdigruttola <c.digruttola@hotmail.it>
 * @copyright Copyright since 2007 Carmine Di Gruttola
 * @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

use cdigruttola\Module\SimpleBanner\Configuration\SimpleBannerConfiguration;
use cdigruttola\Module\SimpleBanner\Translations\TranslationDomains;
use PrestaShop\PrestaShop\Adapter\SymfonyContainer;
use PrestaShop\PrestaShop\Core\Module\WidgetInterface;

if (!defined('_PS_VERSION_')) {
    exit;
}

if (file_exists(__DIR__ . '/vendor/autoload.php')) {
    require_once __DIR__ . '/vendor/autoload.php';
}

class Simplebanner extends Module implements WidgetInterface
{
    public $multistoreCompatibility = self::MULTISTORE_COMPATIBILITY_YES;

    public function __construct()
    {
        $this->name = 'simplebanner';
        $this->tab = 'front_office_features';
        $this->version = '1.0.0';
        $this->author = 'cdigruttola';
        $this->need_instance = 0;

        /*
         * Set $this->bootstrap to true if your module is compliant with bootstrap (PrestaShop 1.6)
         */
        $this->bootstrap = true;

        parent::__construct();

        $this->displayName = $this->trans('Simple banner', [], TranslationDomains::TRANSLATION_DOMAIN_ADMIN);
        $this->description = $this->trans('Simple banner line for site', [], TranslationDomains::TRANSLATION_DOMAIN_ADMIN);

        $this->ps_versions_compliancy = ['min' => '1.6', 'max' => _PS_VERSION_];
    }

    public function isUsingNewTranslationSystem()
    {
        return true;
    }

    public function install()
    {
        Configuration::updateValue(SimpleBannerConfiguration::SIMPLE_BANNER_TEXT, []);

        return parent::install()
            && $this->registerHook('displayHeader')
            && $this->registerHook('displayBanner');
    }

    public function uninstall()
    {
        Configuration::deleteByName(SimpleBannerConfiguration::SIMPLE_BANNER_TEXT);

        return parent::uninstall();
    }

    /**
     * Load the configuration form
     */
    public function getContent(): void
    {
        Tools::redirectAdmin(SymfonyContainer::getInstance()->get('router')->generate('simplebanner_controller'));
    }

    public function hookDisplayHeader()
    {
        $this->context->controller->addJS($this->_path . 'views/js/front.js');
        $this->context->controller->addCSS($this->_path . 'views/css/front.css');
    }

    public function renderWidget($hookName, array $configuration)
    {
        $widgetVariables = $this->getWidgetVariables($hookName, $configuration);
        if (empty($widgetVariables)) {
            return false;
        }

        $this->smarty->assign('banner_text', $widgetVariables);

        return $this->fetch('module:' . $this->name . '/views/templates/front/widget.tpl');
    }

    public function getWidgetVariables($hookName, array $configuration)
    {
        $from = Configuration::get(SimpleBannerConfiguration::SIMPLE_BANNER_DATE_FROM, null, null, $this->context->shop->id);
        if (isset($from) && $from) {
            $fromDate = \DateTime::createFromFormat('Y-m-d H:i:s', $from);
        }
        $to = Configuration::get(SimpleBannerConfiguration::SIMPLE_BANNER_DATE_TO, null, null, $this->context->shop->id);
        if (isset($to) && $to) {
            $toDate = \DateTime::createFromFormat('Y-m-d H:i:s', $to);
        }
        $date = new DateTime();
        if ($date >= $fromDate && $date <= $toDate) {
            return Configuration::get(SimpleBannerConfiguration::SIMPLE_BANNER_TEXT, $this->context->language->id, null, $this->context->shop->id);
        }

        return '';
    }
}
