<?php
namespace Magendoo\OrderStatusVisualizer\Controller\Adminhtml\Index;

use Magento\Backend\App\Action;
use Magento\Backend\App\Action\Context;

class Index extends Action
{
    const ADMIN_RESOURCE = 'Magendoo_OrderStatusVisualizer::main';

    public function __construct(Context $context)
    {
        parent::__construct($context);
    }

    public function execute()
    {
        $resultPage = $this->resultFactory->create(\Magento\Framework\Controller\ResultFactory::TYPE_PAGE);
        $resultPage->setActiveMenu('Magendoo_OrderStatusVisualizer::main');
        $resultPage->getConfig()->getTitle()->prepend(__('Order Status Diagram'));
        return $resultPage;
    }
}
