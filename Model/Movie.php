<?php

namespace Infobase\CustomerMovie\Model;

use Magento\Framework\Model\AbstractModel;
use Infobase\CustomerMovie\Model\ResourceModel\Movie as MovieResourceModel;

class Movie extends AbstractModel
{
    const REGISTRY_KEY = 'infobase_customermovie_movie';

    /**
     * @return void
     */
    public function _construct()
    {
        $this->_init(MovieResourceModel::class);
    }
}
