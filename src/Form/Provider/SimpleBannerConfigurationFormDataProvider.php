<?php

declare(strict_types=1);

namespace cdigruttola\Module\SimpleBanner\Form\Provider;

use PrestaShop\PrestaShop\Core\Configuration\DataConfigurationInterface;
use PrestaShop\PrestaShop\Core\Form\FormDataProviderInterface;

class SimpleBannerConfigurationFormDataProvider implements FormDataProviderInterface
{
    /**
     * @var DataConfigurationInterface
     */
    private $configurationDataConfiguration;

    /**
     * @param DataConfigurationInterface $configurationDataConfiguration
     */
    public function __construct(DataConfigurationInterface $configurationDataConfiguration)
    {
        $this->configurationDataConfiguration = $configurationDataConfiguration;
    }

    /**
     * {@inheritdoc}
     */
    public function getData(): array
    {
        return $this->configurationDataConfiguration->getConfiguration();
    }

    /**
     * {@inheritdoc}
     */
    public function setData(array $data): array
    {
        return $this->configurationDataConfiguration->updateConfiguration($data);
    }
}
