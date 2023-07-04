<?php

namespace Infobase\CustomerMovie\Model\ResourceModel\Movie;

use Infobase\CustomerMovie\Model\Movie;
use Infobase\CustomerMovie\Model\ResourceModel\Movie as MovieResource;
use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;

class Collection extends AbstractCollection
{
    protected $_idFieldName = 'id';

    public function _construct()
    {
        $this->_init(Movie::class, MovieResource::class);
    }
}
