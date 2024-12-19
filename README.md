**Description:**  
The **Magendoo Order Workflow Visualizer** is a Magento 2 module that provides an interactive, dynamic flowchart of your store’s order states and statuses, leveraging Mermaid.js. It helps administrators quickly understand order progression, default and additional statuses for each state, and how orders move from one state to another. The visualization automatically updates as your configuration or custom statuses evolve, ensuring you always have an up-to-date map of your order workflow.

---

# Magendoo Order Workflow Visualizer

This Magento 2 module integrates with Mermaid.js to render a flowchart that illustrates your Magento store’s order lifecycle. It focuses on order **states** and their associated **default and additional statuses**, presenting them as a clearly defined workflow. The generated diagram enables merchants and administrators to quickly grasp the order fulfillment process, from `new` to `complete` (and every state in between).

## Features

- **Visualize Order Lifecycle:** Display all order states as subgraphs, showing their default and additional statuses.
- **Real-Time Updates:** Any changes to order statuses or states are reflected automatically in the diagram.
- **Mermaid.js Integration:** Utilizes Mermaid’s syntax for a clean, interactive flowchart.
- **Configurable Flow:** Customizable logic for defining transitions between states.
- **Admin-Friendly:** Accessible from the Magento Admin panel, with no impact on the storefront.

## Requirements

- Magento 2.4.x or later
- PHP 7.4+ or 8.x
- Composer for installation

## Installation

1. **Via Composer (Recommended):**
   ```bash
   composer require magendoo/order-workflow-visualizer
   bin/magento setup:upgrade
   bin/magento cache:flush
   ```

2. **Manual Installation:**
   - Download or clone the repository into `app/code/Magendoo/OrderStatusVisualizer`.
   - Run setup commands:
     ```bash
     bin/magento setup:upgrade
     bin/magento cache:flush
     ```
   
   Ensure the module is enabled:
   ```bash
   bin/magento module:enable Magendoo_OrderStatusVisualizer
   ```

## Usage

- Log in to the Magento Admin.
- Navigate to **Stores** > **Settings** > **Order Status Diagram** (or a similar location as defined in the module’s menu configuration).
- The page displays a flowchart of your order workflow.
  - Each state appears as a subgraph with "Default" and "Additional" statuses.
  - Arrows represent logical transitions between states (e.g., `new` → `processing` → `complete`).
- If desired, adjust the `$stateTransitions` array in the ViewModel to reflect your custom order transitions.

## Customization

- **Default Status Logic:**  
  The module attempts to identify the default status for each state from the database. If none is found, it falls back to `unknown` or the first status.
  
- **Transitions:**  
  Modify `$stateTransitions` within `ViewModel/Diagram.php` to define how states link together.

- **Styling & Tooltips:**  
  Mermaid.js supports customization (colors, tooltips, etc.). You can enhance the `getMermaidDefinition()` method or the template file to add interactive elements.

## Troubleshooting

- **Blank Diagram:**  
  Ensure the layout handle matches your frontName/controller/action and that the template and viewModel are correctly referenced.
  
- **No Default Status Found:**  
  The module will display "unknown" if no default is set. Check your order status/state configuration in Magento.
  
- **Phrase Instead of State Code:**  
  If you encounter Phrases in state codes, inspect other modules or custom configurations that may alter `orderConfig->getStates()` output.

## Contributing

Contributions are welcome! If you find issues or have ideas for improvements, please open an issue or submit a pull request.

## License

This project is licensed under the MIT License. See the [LICENSE](LICENSE) file for details.