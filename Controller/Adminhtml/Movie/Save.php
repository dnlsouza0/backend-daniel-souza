<?php

namespace Infobase\CustomerMovie\Controller\Adminhtml\Movie;

use Infobase\CustomerMovie\Model\MovieFactory;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\App\ResponseInterface;
use Psr\Log\LoggerInterface;

class Save extends Action
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
    ){
        $this->movieFactory = $movieFactory;
        $this->_logger = $_logger;
        parent::__construct($context);
    }

    /**
     * @return ResponseInterface|\Magento\Framework\Controller\Result\Redirect|\Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $data = $this->getRequest()->getPostValue();

        $resultRedirect = $this->resultRedirectFactory->create();

        if($data) {
            $id = $this->getRequest()->getParam('id');
            $movieModel = $this->movieFactory->create();

            if(empty($data['id'])) {
                $data['id'] = null;
            }

            if($id) {
                $movieModel->load($id);
            }

            $movieModel->setData($data);

            try {
                $movieModel->save();

                $this->messageManager->addSuccessMessage(__('Movie has been saved.'));

                return $resultRedirect->setPath('*/*/');
            } catch (\Exception $e) {
                $this->_logger->debug(__('Error: more details: ') . $e->getMessage());
                $this->messageManager->addErrorMessage($e->getMessage());
                $this->messageManager->addExceptionMessage($e, __('Something went wrong while saving the movie.'));
            }

            return $resultRedirect->setPath('*/*/edit',['id' => $this->getRequest()->getParam('id')]);
        }

        return $resultRedirect->setPath('*/*/');
    }

}
