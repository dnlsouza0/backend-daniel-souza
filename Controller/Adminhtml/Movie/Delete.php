<?php

namespace Infobase\CustomerMovie\Controller\Adminhtml\Movie;

use Infobase\CustomerMovie\Model\MovieFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Psr\Log\LoggerInterface;

class Delete extends Action
{
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
     * @param MovieFactory $movieFactory
     * @param LoggerInterface $_logger
     */
    public function __construct(
        Context $context,
        MovieFactory $movieFactory,
        LoggerInterface $_logger
    ) {
        $this->movieFactory = $movieFactory;
        $this->_logger = $_logger;
        parent::__construct($context);
    }


    /**
     * @return ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $resultRedirect = $this->resultRedirectFactory->create();

        $id = $this->getRequest()->getParam('id');

        if($id) {
            try {
                $model = $this->movieFactory->create();
                $model->load($id);
                $model->delete();

                $this->messageManager->addSuccessMessage(__('Movie was deleted. '));

                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->_logger->debug(__('Error: more details: ') . $e->getMessage());
                $this->messageManager->addErrorMessage($e->getMessage());
                return $resultRedirect->setPath('*/*/edit', ['id' => $id]);
            }
        }
        $this->messageManager->addErrorMessage(__('Can\'t find a movie to delete.'));

        return $resultRedirect->setPath('*/*/');
    }
}
