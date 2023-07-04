<?php

namespace Infobase\CustomerMovie\Block\Movie;

use Magento\Framework\View\Element\Template;
use Infobase\CustomerMovie\Model\ResourceModel\Movie\CollectionFactory as MovieCollectionFactory;
use Magento\Customer\Api\GroupRepositoryInterface;
use Magento\Customer\Model\Session;
use Infobase\CustomerMovie\Helper\Config;
use Magento\Framework\Exception\LocalizedException;

class Index extends Template
{
    /**
     * @var string $_template
     */
    protected $_template = 'Infobase_CustomerMovie::list.phtml';

    /**
     * @var MovieCollectionFactory $movieCollectionFactory
     */
    protected $movieCollectionFactory;

    /**
     * @var Session $customerSession
     */
    protected $customerSession;

    /**
     * @var GroupRepositoryInterface $group
     */
    protected $group;

    /**
     * @var Config $config
     */
    protected $config;



    /**
     * @param Template\Context $context
     * @param MovieCollectionFactory $movieCollectionFactory
     * @param Session $customerSession
     * @param GroupRepositoryInterface $group
     * @param Config $config
     */
    public function __construct(
        Template\Context $context,
        MovieCollectionFactory $movieCollectionFactory,
        Session $customerSession,
        GroupRepositoryInterface $group,
        Config $config,
    ) {
        $this->movieCollectionFactory = $movieCollectionFactory;
        $this->_customerSession = $customerSession;
        $this->group = $group;
        $this->config = $config;
        parent::__construct($context);
    }

    /**
     * Method to create url for redirect to view movies.
     * @param int $movieId
     * @return string
     */
    public function getViewUrl($movieId)
    {
        return $this->getUrl('customermovie/movie/view', ['movie_id' => $movieId]);
    }

    /**
     * Method to get collection movies
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getMovies()
    {
        $customerData = $this->_customerSession->getCustomer();
        if (!$customerData) {
            return false;
        }
        try {
            $movieCollection = $this->movieCollectionFactory->create()->load()
                ->addFieldToSelect('*')
                ->setOrder(
                    'created_at',
                    'desc'
                );

            $dataReturn = [];
            foreach ($movieCollection as $movie) {
                if ($movie->getCustomerGroupId() == $customerData->getGroupId()) {
                    $dataReturn[] = [
                        "id" => $movie->getId(),
                        "customer_code" => $this->group->getById($movie->getCustomerGroupId())->getCode(),
                        "name" => $movie->getName(),
                        "description" => $movie->getDescription(),
                        "release_day" => $movie->getReleaseDay(),
                        "link_trailers" => $movie->getLinkTrailers()
                    ];
                }
            }
            return $dataReturn;
        }
        catch (\Exception $e) {
            $this->_logger->debug(__('Error: more details: ') . $e->getMessage());
            throw new LocalizedException(__("An error occurred contact support"));
        }
    }


    /**
     * Method to get if module is enable.
     * @return boolean
     * @throws \Exception
     */
    public function moduleIsEnable() {
        return $this->config->isModuleEnabled();
    }

}
