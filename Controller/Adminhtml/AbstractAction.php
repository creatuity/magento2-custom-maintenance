<?php

namespace Creatuity\CustomMaintenance\Controller\Adminhtml;

use Creatuity\CustomMaintenance\Model\Adminhtml\Error\Handler;
use Creatuity\CustomMaintenance\Model\Maintenance;
use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;
use Magento\Framework\Controller\Result\RawFactory;
use Magento\Framework\Message\ManagerInterface;
use Magento\Framework\View\Element\BlockFactory;

/**
 * @category       Creatuity
 * @package        Magento 2 Custom Maintenance
 * @copyright      Copyright (c) 2008-2017 Creatuity Corp. (http://www.creatuity.com)
 * @license        http://creatuity.com/license/
 */
abstract class AbstractAction extends Action
{
    /** @var Maintenance */
    protected $maintenance;

    /** @var Handler */
    protected $errorHandler;

    /** @var RawFactory */
    protected $resultRawFactory;

    /** @var ManagerInterface */
    protected $messageManager;

    /** @var BlockFactory */
    protected $blockFactory;

    public function __construct(
        Context $context,
        Maintenance $maintenance,
        Handler $errorHandler,
        RawFactory $resultRawFactory,
        BlockFactory $blockFactory,
        ManagerInterface $messageManager
    ) {
        parent::__construct($context);

        $this->maintenance = $maintenance;
        $this->errorHandler = $errorHandler;
        $this->resultRawFactory = $resultRawFactory;
        $this->messageManager = $messageManager;
        $this->blockFactory = $blockFactory;
    }

    protected function redirectReferralResponse()
    {
        return $this->resultRedirectFactory->create()
            ->setPath($this->_redirect->getRefererUrl());
    }
}
