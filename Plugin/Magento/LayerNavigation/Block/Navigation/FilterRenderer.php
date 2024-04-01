<?php
/**
 * Created by Steven.
 */
declare(strict_types=1);

namespace EBoost\FilterUrlSEO\Plugin\Magento\LayerNavigation\Block\Navigation;

class FilterRenderer
{
    /**
     * @var \Magento\Framework\Filter\FilterManager
     */
    protected $filter;
    /**
     * @var \EBoost\FilterUrlSEO\Model\Config
     */
    protected $config;

    public function __construct(
        \Magento\Framework\Filter\FilterManager $filter,
        \EBoost\FilterUrlSEO\Model\Config $config
    ) {
        $this->filter = $filter;
        $this->config = $config;
    }

    /**
     * Replace id by label for seo filter attributes
     *
     * @param \Magento\LayeredNavigation\Block\Navigation\FilterRenderer $subject
     * @param \Magento\Catalog\Model\Layer\Filter\FilterInterface $filter
     */
    public function beforeRender(
        \Magento\LayeredNavigation\Block\Navigation\FilterRenderer $subject,
        \Magento\Catalog\Model\Layer\Filter\FilterInterface $filter
    ) {
        if ($filter instanceof \Magento\CatalogSearch\Model\Layer\Filter\Attribute
            && in_array($filter->getRequestVar(), $this->config->getSEOAttributes())) {
            foreach ($filter->getItems() as $index => $item) {
                $item->setValue(trim($this->filter->translitUrl($item->getLabel())));
            }
        }
    }
}