<?php
/**
 * Copyright © Juan Manuel Cinto All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace JMC\CustomContactPage\Rewrite\Magento\Contact\Controller\Index;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Contact\Controller\Index as ContactIndex;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\Controller\ResultFactory;

class Index extends ContactIndex implements HttpGetActionInterface
{

    /**
     * @var \Magento\Cms\Model\GetPageByIdentifier
     */
    protected $getPage;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @var \Magento\Framework\App\Config\ScopeConfigInterface
     */
    protected $scopeConfig;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Contact\Model\ConfigInterface $contactsConfig
     * @param \Magento\Cms\Model\GetPageByIdentifier $getPage
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     * @param \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Contact\Model\ConfigInterface $contactsConfig,
        \Magento\Cms\Model\GetPageByIdentifier $getPage,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\App\Config\ScopeConfigInterface $scopeConfig
    ) {
        $this->getPage = $getPage;
        $this->storeManager = $storeManager;
        $this->scopeConfig = $scopeConfig;
        parent::__construct($context, $contactsConfig);
    }

    /**
     * View CMS page action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        if (!$this->isEnabled()) {
            return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        }

        $page = $this->getPage->execute('contact_page', (int) $this->storeManager->getStore()->getId());
        $resultPage = $this->_objectManager->get(\Magento\Cms\Helper\Page::class)->prepareResultPage($this, $page->getId());
        if (!$resultPage) {
            return $this->resultFactory->create(ResultFactory::TYPE_PAGE);
        }

        return $resultPage;
    }

    /**
     * @return bool
     */
    public function isEnabled()
    {
        return $this->scopeConfig->isSetFlag('contact/contact/cms_page_enabled', ScopeInterface::SCOPE_STORE);
    }
}
