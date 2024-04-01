<?php
/**
 * Created by Steven.
 */
declare(strict_types=1);

namespace EBoost\FilterUrlSEO\Plugin\Magento\LayerNavigation\Block;

use Magento\Framework\Exception\NoSuchEntityException;

class Navigation
{
    /**
     * @var \Magento\Catalog\Api\ProductAttributeRepositoryInterface
     */
    protected $productAttributeRepository;
    /**
     * @var \Magento\Framework\Filter\FilterManager
     */
    protected $filter;
    /**
     * @var \EBoost\FilterUrlSEO\Model\Config
     */
    protected $config;

    public function __construct(
        \Magento\Catalog\Api\ProductAttributeRepositoryInterface $productAttributeRepository,
        \Magento\Framework\Filter\FilterManager $filter,
        \EBoost\FilterUrlSEO\Model\Config $config
    ) {
        $this->productAttributeRepository = $productAttributeRepository;
        $this->filter = $filter;
        $this->config = $config;
    }

    /**
     * Update Params
     *
     * @param \Magento\LayeredNavigation\Block\Navigation $subject
     * @param \Magento\Framework\View\LayoutInterface $layout
     */
    public function beforeSetLayout(
        \Magento\LayeredNavigation\Block\Navigation $subject,
        \Magento\Framework\View\LayoutInterface $layout
    ) {
        foreach ($this->config->getSEOAttributes() as $attributeCode) {
            if (($regionValue = $subject->getRequest()->getParam($attributeCode)) && !is_array($regionValue)) {
                $regionValue = trim($this->filter->translitUrl($regionValue));
                try {
                    $attribute = $this->productAttributeRepository->get($attributeCode);
                    $options = $attribute->getSource()->getAllOptions();
                    foreach ($options as $option) {
                        $optionLabel = trim($this->filter->translitUrl($option['label']));
                        if ($regionValue == $optionLabel) {
                            $subject->getRequest()->setParam($attributeCode, $option['value']);
                        }
                    }
                } catch (NoSuchEntityException $e) {
                }
            }
        }
    }
}