<?php
/**
 * Created by Steven.
 */
declare(strict_types=1);


namespace EBoost\FilterUrlSEO\Model\Config\Source;


Class FilterAttributes implements \Magento\Framework\Data\OptionSourceInterface
{
    /**
     * @var \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory
     */
    protected $attributeCollectionFactory;

    public function __construct(
        \Magento\Catalog\Model\ResourceModel\Product\Attribute\CollectionFactory $attributeCollectionFactory
    ) {
        $this->attributeCollectionFactory = $attributeCollectionFactory;
    }

    public function getFilterAttributes()
    {
        if (!isset($this->filterableAttributes)) {
            $collection = $this->getFilterableAttributesCollection();
            $select = $collection->getSelect();
            $select->reset(\Magento\Framework\DB\Select::COLUMNS)
                ->columns(['value' => 'attribute_code'], 'main_table')
                ->columns(['label' => 'frontend_label'], 'main_table');

            $this->filterableAttributes = $collection->getConnection()->fetchAll($select);
        }

        return $this->filterableAttributes;
    }

    /**
     * @return \Magento\Catalog\Model\ResourceModel\Product\Attribute\Collection
     */
    public function getFilterableAttributesCollection()
    {
        if (!isset($this->attrCollection)) {
            $this->attrCollection = $this->attributeCollectionFactory->create();
            $this->attrCollection->addIsFilterableFilter();
        }
        return $this->attrCollection;
    }

    /**
     * Return array of options as value-label pairs
     *
     * @return array Format: array(array('value' => '<value>', 'label' => '<label>'), ...)
     */
    public function toOptionArray()
    {
        return $this->getFilterAttributes();
    }
}