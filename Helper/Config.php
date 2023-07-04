<?php

namespace Infobase\CustomerMovie\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Exception\LocalizedException;

class Config extends AbstractHelper
{
    const BASE_CONFIG = 'infobase_customer_movie';
    const GENERAL_GROUP = "/general";

    const MODULE_ENABLED = self::BASE_CONFIG . self::GENERAL_GROUP . '/active';

    /**
     * @var ScopeConfigInterface $scopeConfig
     */
    protected $scopeConfig;

    /**
     * @param Context $context
     * @param ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        Context $context,
        ScopeConfigInterface $scopeConfig
    ){
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context);
    }

    /**
     * Method to get config in admin, enable or disable
     * @param $storeId
     * @return boolean
     * @throws \Exception
     */
    public function isModuleEnabled($storeId = null)
    {
        try {

            return filter_var(
                $externalServiceKey = $this->scopeConfig->getValue(
                    self::MODULE_ENABLED,
                    ScopeInterface::SCOPE_STORE,
                    $storeId
                ),
                FILTER_VALIDATE_BOOLEAN
            );

        } catch (\Exception $e) {
            $this->_logger->debug(__('Error: more details: ') . $e->getMessage());
            throw new LocalizedException(__("An error occurred contact support"));
        }
    }

}
