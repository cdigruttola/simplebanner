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
        'banner_from',
        'banner_to',
    ];

    /**
     * @return OptionsResolver
     */
    protected function buildResolver(): OptionsResolver
    {
        return (new OptionsResolver())
            ->setDefined(self::CONFIGURATION_FIELDS)
            ->setAllowedTypes('banner_text', 'array')
            ->setAllowedTypes('banner_from', ['null', 'datetime'])
            ->setAllowedTypes('banner_to', ['null', 'datetime']);
    }

    /**
     * {@inheritdoc}
     */
    public function getConfiguration(): array
    {
        $return = [];
        $shopConstraint = $this->getShopConstraint();

        $return['banner_text'] = $this->configuration->get(SimpleBannerConfiguration::SIMPLE_BANNER_TEXT, null, $shopConstraint);
        $return['banner_from'] = new \DateTime();
        $return['banner_to'] = new \DateTime();
        $from = $this->configuration->get(SimpleBannerConfiguration::SIMPLE_BANNER_DATE_FROM, null, $shopConstraint);
        if (isset($from) && $from) {
            $return['banner_from'] = \DateTime::createFromFormat('Y-m-d H:i:s', $from);
        }
        $to = $this->configuration->get(SimpleBannerConfiguration::SIMPLE_BANNER_DATE_TO, null, $shopConstraint);
        if (isset($to) && $to) {
            $return['banner_to'] = \DateTime::createFromFormat('Y-m-d H:i:s', $to);
        }

        return $return;
    }

    /**
     * {@inheritdoc}
     */
    public function updateConfiguration(array $configuration): array
    {
        $shopConstraint = $this->getShopConstraint();
        $configuration['banner_from'] = date_format($configuration['banner_from'], 'Y-m-d H:i:s');
        $configuration['banner_to'] = date_format($configuration['banner_to'], 'Y-m-d H:i:s');
        $this->updateConfigurationValue(SimpleBannerConfiguration::SIMPLE_BANNER_TEXT, 'banner_text', $configuration, $shopConstraint);
        $this->updateConfigurationValue(SimpleBannerConfiguration::SIMPLE_BANNER_DATE_FROM, 'banner_from', $configuration, $shopConstraint);
        $this->updateConfigurationValue(SimpleBannerConfiguration::SIMPLE_BANNER_DATE_TO, 'banner_to', $configuration, $shopConstraint);

        return [];
    }
}
