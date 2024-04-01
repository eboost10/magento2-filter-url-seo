<?php
namespace EBoost\FilterUrlSEO\Model;

use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Locale\ResolverInterface;
use Magento\Store\Model\ScopeInterface;

class Config
{
    /**@#%
     * @const
     */
    const XML_PATH_MODULE_CONFIG = 'filter_url_seo/general/';
    /**
     * @var ResolverInterface
     */
    protected $localeResolver;

    /**
     * @var ScopeConfigInterface
     */
    private $scopeConfig;

    /**
     * Config constructor.
     *
     * @param ScopeConfigInterface $scopeConfig
     * @param ResolverInterface $localeResolver
     */
    public function __construct(
        ScopeConfigInterface $scopeConfig,
        ResolverInterface $localeResolver
    ) {
        $this->scopeConfig = $scopeConfig;
        $this->localeResolver = $localeResolver;
    }

    /**
     * Get Configuration
     *
     * @param $path
     * @return mixed
     */
    public function getConfig($path)
    {
        return $this->scopeConfig->getValue($path, ScopeInterface::SCOPE_STORES);
    }

    public function getModuleConfig($path)
    {
        return $this->getConfig(self::XML_PATH_MODULE_CONFIG . $path);
    }

    /**
     * Enable Mega menu
     *
     * @return boolean
     */
    public function isEnable()
    {
        return $this->getModuleConfig('enabled');
    }

    /**
     * @return array
     */
    public function getSEOAttributes()
    {
        if (!isset($this->_seoAttributes)) {
            $this->_seoAttributes = [];
            if ($this->isEnable() && $setting = $this->getModuleConfig('attributes')) {
                $this->_seoAttributes =  explode(',', $setting);
            }
        }

        return $this->_seoAttributes;
    }
}
