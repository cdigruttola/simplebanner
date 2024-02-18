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

declare(strict_types=1);

namespace cdigruttola\Module\SimpleBanner\Form;

use cdigruttola\Module\SimpleBanner\Configuration\SimpleBannerConfiguration;
use cdigruttola\Module\SimpleBanner\Translations\TranslationDomains;
use PrestaShopBundle\Form\Admin\Type\FormattedTextareaType;
use PrestaShopBundle\Form\Admin\Type\MultistoreConfigurationType;
use PrestaShopBundle\Form\Admin\Type\TranslatableType;
use PrestaShopBundle\Form\Admin\Type\TranslatorAwareType;
use Symfony\Component\Form\Extension\Core\Type\DateTimeType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;

class SimpleBannerConfigurationType extends TranslatorAwareType
{
    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('banner_text', TranslatableType::class, [
                'type' => FormattedTextareaType::class,
                'required' => true,
                'label' => $this->trans('Banner Text', TranslationDomains::TRANSLATION_DOMAIN_ADMIN),
                'multistore_configuration_key' => SimpleBannerConfiguration::SIMPLE_BANNER_TEXT,
            ])
            ->add('banner_from', DateTimeType::class, [
                'label' => $this->trans('Display from', TranslationDomains::TRANSLATION_DOMAIN_ADMIN),
                'required' => false,
                'widget' => 'single_text',
                'html5' => true,
                'input' => 'datetime',
                'with_seconds' => true,
            ])
            ->add('banner_to', DateTimeType::class, [
                'label' => $this->trans('Display to', TranslationDomains::TRANSLATION_DOMAIN_ADMIN),
                'required' => false,
                'widget' => 'single_text',
                'html5' => true,
                'input' => 'datetime',
                'with_seconds' => true,
            ]);
    }

    /**
     * {@inheritdoc}
     *
     * @see MultistoreConfigurationTypeExtension
     */
    public function getParent(): string
    {
        return MultistoreConfigurationType::class;
    }
}
