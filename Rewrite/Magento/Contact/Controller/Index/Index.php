<?php
/**
 * Copyright © Juan Manuel Cinto All rights reserved.
 * See COPYING.txt for license details.
 */
declare(strict_types=1);

namespace JMC\CustomContactPage\Rewrite\Magento\Contact\Controller\Index;

use Magento\Framework\App\Action\HttpPostActionInterface;
use Magento\Framework\App\Action\HttpGetActionInterface;
use Magento\Framework\App\Action\Action;

/**
 * Custom page for storefront. Needs to be accessible by POST because of the store switching.
 */
class Index extends Action implements HttpGetActionInterface, HttpPostActionInterface
{

    /**
     * @var \Magento\Framework\Controller\Result\ForwardFactory
     */
    protected $resultForwardFactory;

    /**
     * @var \Magento\Cms\Model\GetPageByIdentifier
     */
    protected $getPage;

    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param \Magento\Framework\App\Action\Context $context
     * @param \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory
     * @param \Magento\Cms\Model\GetPageByIdentifier $getPage
     * @param \Magento\Store\Model\StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Action\Context $context,
        \Magento\Framework\Controller\Result\ForwardFactory $resultForwardFactory,
        \Magento\Cms\Model\GetPageByIdentifier $getPage,
        \Magento\Store\Model\StoreManagerInterface $storeManager
    ) {
        $this->resultForwardFactory = $resultForwardFactory;
        $this->getPage = $getPage;
        $this->storeManager = $storeManager;
        parent::__construct($context);
    }

    /**
     * View CMS page action
     *
     * @return \Magento\Framework\Controller\ResultInterface
     */
    public function execute()
    {
        $page = $this->getPage->execute('contact_page', (int) $this->storeManager->getStore()->getId());
        $resultPage = $this->_objectManager->get(\Magento\Cms\Helper\Page::class)->prepareResultPage($this, $page->getId());
        if (!$resultPage) {
            $resultForward = $this->resultForwardFactory->create();
            return $resultForward->forward('noroute');
        }
        return $resultPage;
    }
}
