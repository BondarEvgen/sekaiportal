# Catalog Specification — MVP

This document records the agreed business rules for the initial store release.
Implementation details may evolve, but changes to these rules must be explicit.

## Market and Localization

- The business is registered in Spain.
- Delivery is limited to mainland Spain.
- The Balearic Islands, Canary Islands, Ceuta, and Melilla are excluded from MVP delivery.
- The store currency is EUR.
- Storefront prices include IVA.
- The storefront launches in Spanish.
- English storefront support is planned after MVP.
- The administration interface launches in English.
- Spanish administration support may be added later.

Tax treatment must support different product tax categories.
Applicable rates and rounding rules must be confirmed before launch.

## Assortment

### Manga

- New printed manga
- Individual volumes
- Manga sets
- Spanish and English editions
- Preorders

### Cosplay

- Ready-made costumes in standard sizes
- Wigs
- Footwear
- Accessories
- Preorders

### Merchandise

- Figures
- Posters
- Keychains

### Out of Scope

- Digital manga
- Used products
- Made-to-measure costumes
- Costume rentals
- Contact lenses

## Products and Variants

A product represents a catalog page and its shared information.

A product variant represents a purchasable item with its own SKU,
price, and inventory.

- Every sellable product has at least one variant.
- Products without customer-selectable options use a single default variant.
- Costumes may have size and color combinations.
- Variant prices may differ.
- Footwear uses EU sizes.
- The cart references variants rather than products alone.
- Each variant stores its full selling price, not a surcharge over a product price.
- Monetary amounts are stored as integer euro cents, never floating-point values.

The product's physical language is independent of the storefront language.
Spanish and English editions of the same manga volume are separate products.

## Manga Sets

- Manga sets are separate products with their own variants, SKUs, and prices.
- Each set variant has independent inventory.
- Purchasing a set does not reserve or deduct stock from individual volumes.
- The same physical books must not be counted as both set inventory and individual volume inventory.
- Virtual bundles and automated assembly from individual volume stock are outside MVP scope.


## Publication and Sales Mode

Publication status and sales mode are separate concerns.

Publication statuses:

- Draft
- Active
- Archived

Sales modes:

- Regular
- Preorder

A published product is not necessarily available to purchase.
Availability also depends on its sales mode and applicable sales rules.

## Regular Inventory

- Regular variants cannot be sold without available stock.
- Adding an item to the cart does not reserve inventory.
- Inventory is checked again when the order is created.
- Order creation reserves inventory atomically.
- Unpaid reservations expire after 30 minutes.
- Confirmed payment retains the reservation until shipment or cancellation.
- Physical stock is deducted when the item is shipped.
- Shipment deducts physical stock and releases the corresponding reservation atomically.
- Cancelling an unshipped order releases its reservation.
- Physical stock and reserved stock cannot be negative.
- Reserved stock cannot exceed physical stock.
- Physical stock changes are recorded in a stock movement journal.
- Reservation changes must remain traceable to their orders.
- The low-stock threshold is configured per variant.
- MVP does not include a configurable per-order quantity limit.

Available stock is calculated as:

available = on_hand - reserved

A payment confirmed after reservation expiration must not automatically
fulfill an order without rechecking stock availability.
The recovery and refund workflow will be specified during payment design.

## Preorders

- Preorders are paid in full at checkout.
- No overall preorder quantity cap is enforced.
- Regular items and preorder items cannot be purchased in the same order.
- Each preorder order contains exactly one product variant (SKU).
- Multiple units of that variant are allowed.
- Different preorder variants require separate orders, even when they belong to the same product.
- Quantity must be a positive integer.
- Preorders do not require available physical stock at checkout.
- Preorder commitments must be tracked separately from physical stock reservations.
- Received stock must be allocated before a preorder can be shipped.

## Preorder Scheduling and Fulfillment

### Sales Window

- The preorder start timestamp is optional.
- Without a start timestamp, preorder sales may begin immediately once the product is active and all other preorder requirements are satisfied.
- The preorder end timestamp is optional.
- Without an end timestamp, an administrator must close preorder sales manually.
- When both timestamps are provided, the end must be later than the start.
- New preorder orders are accepted at or after the start timestamp and strictly before the end timestamp, when those timestamps are set.
- Closing preorder sales does not cancel existing orders.

### Payment Window

- Each preorder order has a payment deadline of 30 minutes after creation.
- Closing preorder sales does not shorten the payment window of an existing order.
- An unpaid preorder order expires when its payment deadline is reached.
- Payments confirmed after expiration require a separate recovery or refund workflow.

### Expected Shipping Period

- An expected shipping date range is required before preorder sales open.
- The range describes expected dispatch to the customer, not publication, warehouse arrival, or delivery to the customer.
- The end date must be on or after the start date.
- The expected shipping period is shown before the customer places the order.
- The period is copied into the order as a snapshot.
- Editing the product's shipping estimate must not silently overwrite the original estimate recorded in existing orders.

### Transition to Regular Sales

- Reaching an expected date does not automatically change the product's sales mode.
- Actual stock receipt must be recorded.
- Received stock must be allocated to outstanding paid preorder commitments before it is offered for regular sales.
- An administrator switches the product to regular sales after receipt and allocation.
- Only unallocated stock is available to new regular orders.
- Changing a product's sales mode does not change the sales mode recorded in existing orders.


## Decisions Required Before Related Implementation

- Tax categories, rates, and rounding rules
