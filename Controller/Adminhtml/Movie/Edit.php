<?php

namespace Infobase\CustomerMovie\Controller\Adminhtml\Movie;

use Infobase\CustomerMovie\Model\Movie;
use Infobase\CustomerMovie\Model\MovieFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Magento\Framework\Registry;
use Magento\Framework\View\Result\PageFactory;
use Psr\Log\LoggerInterface;

class Edit extends Action
{

    /**
     * @var PageFactory $resultPageFactory
     */
    public $resultPageFactory;

    /**
     * @var Registry $registry
     */
    protected $registry;

    /**
     * @var MovieFactory $movieFactory
     */
    protected $movieFactory;

    /**
     * @var LoggerInterface $_logger
     */
    protected $_logger;

    /**
     * @param Context $context
     * @param Registry $registry
     * @param MovieFactory $movieFactory
     * @param PageFactory $resultPageFactory
     * @param LoggerInterface $_logger
     */
    public function __construct(
        Context $context,
        Registry $registry,
        MovieFactory $movieFactory,
        PageFactory $resultPageFactory,
        LoggerInterface $_logger
    ) {
        $this->resultPageFactory = $resultPageFactory;
        $this->registry = $registry;
        $this->movieFactory = $movieFactory;
        $this->_logger = $_logger;
        parent::__construct($context);
    }

    /**
     * Method to verify if exists param in url to get movie by param.
     * @param $register
     * @return false|Movie
     */
    public function initMovie($register = false) {

        $movieId = (int) $this->getRequest()->getParam('id');

        $movie = $this->movieFactory->create();

        if($movieId) {
            try {
                $movie->load($movieId);
            } catch (\Exception $e) {
                $this->_logger->debug(__('Error: more details: ') . $e->getMessage());
                $this->messageManager->addErrorMessage($e->getMessage());
                return false;
            }

            if(!$movie->getId()) {
                $this->messageManager->addErrorMessage(__('This movie no longer exists.'));
                return false;
            }
        }

        if ($register) {
            $this->registry->register(Movie::REGISTRY_KEY, $movie);
        }

        return $movie;
    }

    /**
     * @return ResponseInterface|\Magento\Framework\Controller\ResultInterface|\Magento\Framework\View\Result\Page
     */
    public function execute()
    {
        $movie = $this->initMovie();

        if(!$movie) {
            $resultRedirect = $this->resultPageFactory->create();
            $resultRedirect->setPath('*');

            return $resultRedirect;
        }

        $this->registry->register(Movie::REGISTRY_KEY, $movie);

        $resultPage = $this->resultPageFactory->create();
        $resultPage->getConfig()->getTitle()->set(__('Movie Management'));

        if ($movie->getId()) {
            $pageLabel = __('Editing %1', $movie->getName() ? $movie->getName() : 'Movie ' . $movie->getId());
        }
        else {
            $pageLabel = __('New Movie');
        }

        $resultPage->getConfig()->getTitle()->prepend($pageLabel);

        return $resultPage;
    }

}
