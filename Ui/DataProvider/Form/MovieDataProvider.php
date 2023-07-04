<?php

namespace Infobase\CustomerMovie\Ui\DataProvider\Form;

use Magento\Framework\App\Request\DataPersistorInterface;
use Magento\Ui\DataProvider\AbstractDataProvider;

use Infobase\CustomerMovie\Model\ResourceModel\Movie\Collection;
use Infobase\CustomerMovie\Model\ResourceModel\Movie\CollectionFactory;

class MovieDataProvider extends AbstractDataProvider
{

    /**
     * @var Collection $collection
     */
    protected $collection;

    /**
     * @var DataPersistorInterface $dataPersistor
     */
    protected $dataPersistor;

    protected $loadedData;

    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        DataPersistorInterface $dataPersistor,
        array $meta = [],
        array $data = []
    ) {
        $this->collection = $collectionFactory->create();
        $this->dataPersistor = $dataPersistor;
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
    }

    public function getData()
    {
        if(isset($this->loadedData)) {
            return $this->loadedData;
        }

        $items = $this->collection->getItems();

        foreach ($items as $movie) {
            $data = $movie->getData();

            $this->loadedData[$movie->getId()] = $data;
        }

        $data = $this->dataPersistor->get('movie_data');

        if(!empty($data)) {
            $movie = $this->collection->getNewEmptyItem();
            $movie->setData($data);
            $this->loadedData[$movie->getId()] = $movie->getData();
            $this->dataPersistor->clear('movie_data');
        }

        return $this->loadedData;
    }

}
