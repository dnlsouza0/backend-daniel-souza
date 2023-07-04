<?php

namespace Infobase\CustomerMovie\Model\Source;

use Magento\Customer\Model\ResourceModel\Group\CollectionFactory;
use Magento\Framework\Data\OptionSourceInterface;
class GroupCustomer  implements OptionSourceInterface
{
    /**
     * @var CollectionFactory $collectionFactory
     */
    protected $collectionFactory;

    /**
     * @param CollectionFactory $collectionFactory
     */

    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    /**
     * @return array
     */
    public function toOptionArray()
    {
        $options[] = ['label' => '-- Please Select --', 'value' => ''];
        $collection = $this->collectionFactory->create()->load();

        foreach ($collection as $group) {
            $options[] = [
                'label' => $group->getCustomerGroupCode(),
                'value' => $group->getCustomerGroupId(),
            ];
        }

        return $options;
    }
}
