<?php

declare(strict_types=1);

namespace cdigruttola\Module\SimpleBanner\Form\DataConfiguration;

use cdigruttola\Module\SimpleBanner\Configuration\SimpleBannerConfiguration;
use PrestaShop\PrestaShop\Core\Configuration\AbstractMultistoreConfiguration;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * Handles configuration data for demo multistore configuration options.
 */
final class SimpleBannerDataConfiguration extends AbstractMultistoreConfiguration
{
    private const CONFIGURATION_FIELDS = [
        'banner_text',
    ];

    /**
     * @return OptionsResolver
     */
    protected function buildResolver(): OptionsResolver
    {
        return (new OptionsResolver())
            ->setDefined(self::CONFIGURATION_FIELDS)
            ->setAllowedTypes('banner_text', 'array');
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration(): array
    {
        $return = [];
        $shopConstraint = $this->getShopConstraint();

        $return['banner_text'] = $this->configuration->get(SimpleBannerConfiguration::SIMPLE_BANNER_TEXT, null, $shopConstraint);

        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public function updateConfiguration(array $configuration): array
    {
        $shopConstraint = $this->getShopConstraint();
        $this->updateConfigurationValue(SimpleBannerConfiguration::SIMPLE_BANNER_TEXT, 'banner_text', $configuration, $shopConstraint);

        return [];
    }
}
