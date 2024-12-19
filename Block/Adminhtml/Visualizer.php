<?php
namespace Magendoo\OrderStatusVisualizer\Block\Adminhtml;

use Magento\Backend\Block\Template\Context;
use Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory as StatusCollectionFactory;
use Magento\Framework\View\Element\Template;

class Visualizer extends Template
{
    protected $statusCollectionFactory;
    protected $stateCollection; // If needed

    public function __construct(
        Context $context,
        StatusCollectionFactory $statusCollectionFactory,
        array $data = []
    ) {
        $this->statusCollectionFactory = $statusCollectionFactory;
        parent::__construct($context, $data);
    }

    /**
     * Generate Mermaid diagram definition
     */
    public function getMermaidDefinition()
    {
        // Fetch order statuses and transitions from Magento
        // For simplicity, we’ll assume we have a method that returns data in a structured form.
        $transitionsData = $this->getStatusTransitions();
print_r($$transitionsData);exit;
        $diagram = "graph TD\n";
        
        foreach ($transitionsData['transitions'] as $fromStatus => $toStatuses) {
            foreach ($toStatuses as $toStatus) {
                $diagram .= "  {$fromStatus} --> {$toStatus}\n";
            }
        }

        return $diagram;
    }

    /**
     * Get structured data for statuses and transitions.
     * Ideally, this would be more complex: fetch from Magento’s config or DB.
     */
    protected function getStatusTransitions()
    {
        // Example logic:
        // Retrieve statuses from DB
        $statusCollection = $this->statusCollectionFactory->create();
        $statuses = [];
        foreach ($statusCollection as $status) {
            $statuses[$status->getStatus()] = $status->getLabel();
        }

        // In Magento, transitions might not be explicitly defined, 
        // but we might deduce them from configuration or states.
        // For demo purposes, hardcode or configure a small set:
        $transitions = [
            'pending' => ['processing', 'canceled'],
            'processing' => ['complete'],
            'complete' => [],
            'canceled' => []
        ];

        return [
            'statuses' => $statuses,
            'transitions' => $transitions
        ];
    }
}
