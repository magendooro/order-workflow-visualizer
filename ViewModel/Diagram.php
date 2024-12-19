<?php
namespace Magendoo\OrderStatusVisualizer\ViewModel;

use Magento\Framework\View\Element\Block\ArgumentInterface;
use Magento\Sales\Model\Order\Config as OrderConfig;
use Magento\Sales\Model\ResourceModel\Order\Status\CollectionFactory as StatusCollectionFactory;

class Diagram implements ArgumentInterface
{
    /**
     * @var OrderConfig
     */
    protected $orderConfig;

    /**
     * @var StatusCollectionFactory
     */
    protected $statusCollectionFactory;

    /**
     * @param OrderConfig           $orderConfig
     * @param StatusCollectionFactory $statusCollectionFactory
     */
    public function __construct(
        OrderConfig $orderConfig,
        StatusCollectionFactory $statusCollectionFactory
    ) {
        $this->orderConfig = $orderConfig;
        $this->statusCollectionFactory = $statusCollectionFactory;
    }

    /**
     * Generate the Mermaid diagram definition based on order states and statuses.
     *
     * @return string
     */
    public function getMermaidDefinition(): string
    {
        // getStates() returns something like:
        // [
        //   'new' => Phrase("Pending"),
        //   'processing' => Phrase("Processing"),
        //   ...
        // ]
        $statesConfig = $this->orderConfig->getStates();
        $stateCodes = array_keys($statesConfig);
    
        $flow = $this->getStatesWithStatuses($stateCodes, $statesConfig);
    
        // Define logical transitions (this is example logic, adjust as needed)
        $stateTransitions = [
            'new'             => ['pending_payment', 'payment_review'],
            'pending_payment' => ['processing', 'canceled'],
            'payment_review'  => ['processing', 'canceled'],
            'processing'      => ['complete', 'canceled', 'holded'],
            'holded'          => ['processing'],
            'complete'        => [],
            'canceled'        => [],
            'closed'          => []
        ];
    
        $diagram = "flowchart TD\n\n";
    
        foreach ($flow as $stateCode => $data) {
            // Use the label from $statesConfig
            $stateLabel = (string)$statesConfig[$stateCode]; 
            $diagram .= "subgraph {$stateCode}[" . $stateLabel . " State]\n";
            $diagram .= "  direction TB\n";
            $diagram .= "  {$stateCode}_default[\"Default: {$data['default']}\"]\n";
    
            foreach ($data['additional'] as $status) {
                $statusNode = $this->sanitizeNodeName($status);
                $diagram .= "  {$stateCode}_{$statusNode}[\"Additional: {$status}\"]\n";
            }
            $diagram .= "end\n\n";
        }
    
        // Add transitions
        foreach ($stateTransitions as $fromState => $toStates) {
            foreach ($toStates as $toState) {
                // Only add transitions if these states exist in our map
                if (array_key_exists($fromState, $flow) && array_key_exists($toState, $flow)) {
                    $diagram .= "{$fromState} --> {$toState}\n";
                }
            }
        }
    
        return $diagram;
    }    

    /**
     * Build a structure of states with default and additional statuses.
     *
     * @param array $allStates
     * @return array
     */
    protected function getStatesWithStatuses(array $stateCodes, array $statesConfig): array
    {
        $flow = [];
    
        foreach ($stateCodes as $stateCode) {
            $statuses = $this->orderConfig->getStateStatuses($stateCode);
    
            $defaultStatus = $this->getDefaultStatusForState($stateCode, $statuses);
            // If there's no default and no statuses, return unknown
            if ($defaultStatus === 'unknown' && empty($statuses)) {
                // no statuses associated, default is unknown
            } elseif ($defaultStatus === 'unknown' && !empty($statuses)) {
                // If no default flagged, take the first one from $statuses
                $defaultStatus = reset($statuses);
            }
    
            $additionalStatuses = array_diff($statuses, [$defaultStatus]);
    
            $flow[$stateCode] = [
                'default'    => $defaultStatus ?: 'unknown',
                'additional' => $additionalStatuses
            ];
        }
    
        return $flow;
    }    

    /**
     * Determine the default status for a given state using is_default flag.
     *
     * @param string $state
     * @param array  $statuses
     * @return string
     */
    protected function getDefaultStatusForState(string $state, array $statuses): string
    {
        if (empty($statuses)) {
            return 'unknown';
        }
    
        $collection = $this->statusCollectionFactory->create();
        $collection->addStateFilter($state);
        $collection->addFieldToFilter('main_table.status', ['in' => $statuses]);
        $collection->addFieldToFilter('state_table.is_default', 1);
    
        $defaultItem = $collection->getFirstItem();
        if ($defaultItem && in_array($defaultItem->getStatus(), $statuses)) {
            return $defaultItem->getStatus();
        }
    
        return 'unknown';
    }
    

    /**
     * Sanitize a string to be used as a node ID in Mermaid.
     *
     * @param string $name
     * @return string
     */
    protected function sanitizeNodeName(string $name): string
    {
        return preg_replace('/[^a-zA-Z0-9_]/', '_', $name);
    }
}
