# Sekai Portal — Roadmap and Development Log

This document tracks the project's progress, architectural decisions, and current development tasks.

---

## Technology Stack

- **Backend:** Laravel 13 and PHP 8.5
- **Docker environment:** Laravel Sail
- **Database:** MySQL 8.4
- **Cache, queues, and sessions:** database drivers currently; Redis is available for future configuration
- **Local email testing:** Mailpit
- **Frontend:** Blade, Laravel Livewire 4, and Alpine.js
- **Styling:** Tailwind CSS 4
- **Frontend build tool:** Vite 8
- **Administration panel:** Filament; the version will be selected after verifying compatibility with Laravel 13 and Livewire 4
- **Localization:** Spanish storefront in MVP; English storefront later; administration initially in English
- **Payments:** an internal fake payment gateway first, followed by integration with a real payment provider

---

## Development Checklist

### Phase 1: Project Initialization and Docker — Completed

- [x] Initialize the Laravel 13 application
- [x] Configure Laravel Sail with MySQL 8.4, Redis, and Mailpit
- [x] Isolate frontend dependencies in a dedicated Docker volume
- [x] Verify Tailwind CSS 4 and the Vite 8 production build
- [x] Verify the Livewire 4 installation
- [x] Run the initial database migrations
- [x] Run the initial test suite
- [x] Verify PHP formatting with Laravel Pint
- [x] Resolve known Composer and npm dependency vulnerabilities

### Phase 2: Product Catalog Database

- [ ] Finalize the catalog domain model
- [ ] Create the `categories` migration and model
- [ ] Create the `products` migration and model
- [ ] Create the `product_variants` migration and model
- [ ] Create the product attribute migrations and models
- [ ] Create the `product_images` migration and model
- [ ] Create the inventory migrations and models
- [ ] Add database indexes and constraints
- [ ] Create model factories
- [ ] Create realistic development seeders
- [ ] Add tests for catalog relationships and constraints

### Phase 3: Administration Panel

- [ ] Verify compatibility and install the appropriate Filament version
- [ ] Configure administrator authentication and authorization
- [ ] Create category management resources
- [ ] Create product management resources
- [ ] Create product variant and attribute management resources
- [ ] Create image management functionality
- [ ] Create inventory management functionality
- [ ] Localize the administration interface

### Phase 4: Localization and Storefront Layout

- [ ] Define the localization strategy for interface and product content
- [ ] Implement locale detection and switching
- [ ] Create the main Blade layout
- [ ] Create the header and navigation
- [ ] Create the footer
- [ ] Create the mobile navigation
- [ ] Add accessible loading, error, and empty states

### Phase 5: Catalog Storefront

- [ ] Create the catalog page
- [ ] Add category navigation
- [ ] Add filters for price, availability, size, and other attributes
- [ ] Add sorting
- [ ] Store filter state in the URL query string
- [ ] Create product cards
- [ ] Create the product details page
- [ ] Add a product image gallery
- [ ] Add product variant selection
- [ ] Add basic catalog search
- [ ] Add catalog and product page tests

### Phase 6: Authentication and Customer Account

- [ ] Implement registration and login
- [ ] Implement email verification
- [ ] Implement password reset
- [ ] Add rate limiting for authentication
- [ ] Create customer profile management
- [ ] Create customer address management
- [ ] Create the order history page
- [ ] Add authorization policies and tests

### Phase 7: Shopping Cart

- [ ] Design the persistent cart model
- [ ] Implement a guest shopping cart
- [ ] Implement an authenticated customer cart
- [ ] Merge guest and customer carts after login
- [ ] Implement server-side cart calculations
- [ ] Validate current prices and stock
- [ ] Add shopping cart tests

### Phase 8: Checkout and Orders

- [ ] Design the order state model
- [ ] Create order and order item migrations
- [ ] Store immutable product and address snapshots in orders
- [ ] Implement checkout validation
- [ ] Implement transactional order creation
- [ ] Implement inventory reservation
- [ ] Add order status history
- [ ] Send order confirmation notifications
- [ ] Add checkout and order tests

### Phase 9: Payments

- [ ] Define a payment gateway contract
- [ ] Implement a fake payment gateway
- [ ] Create payment and refund models
- [ ] Implement idempotent payment processing
- [ ] Select and integrate a real payment provider
- [ ] Implement and verify payment webhooks
- [ ] Implement payment expiration and inventory release
- [ ] Add payment integration tests

### Phase 10: Shipping

- [ ] Define a shipping provider contract
- [ ] Implement basic shipping methods
- [ ] Add shipping cost calculation
- [ ] Create shipment records
- [ ] Add tracking number support
- [ ] Integrate a real shipping provider if required
- [ ] Add shipping tests

### Phase 11: Marketing Features

- [ ] Implement promotions
- [ ] Implement coupon codes
- [ ] Implement wishlists
- [ ] Implement verified product reviews
- [ ] Implement back-in-stock subscriptions
- [ ] Add related product collections

### Phase 12: SEO, Performance, and Accessibility

- [ ] Add localized metadata
- [ ] Add canonical URLs
- [ ] Add XML sitemaps
- [ ] Add structured product data
- [ ] Optimize and resize product images
- [ ] Add application and query caching
- [ ] Review database indexes and N+1 queries
- [ ] Perform an accessibility review
- [ ] Perform mobile and performance testing

### Phase 13: Production Readiness

- [ ] Configure the production environment
- [ ] Configure queue workers and the scheduler
- [ ] Configure object storage
- [ ] Configure transactional email
- [ ] Configure application monitoring
- [ ] Configure backups and test restoration
- [ ] Configure the deployment pipeline
- [ ] Perform a security review
- [ ] Run the complete test suite
- [ ] Perform production smoke testing

---

## Development Log

### May 31, 2026

- **Event:** The project was initialized.
- **Decision:** The TALL-oriented stack was selected: Laravel, Livewire, Alpine.js, and Tailwind CSS.
- **Implementation:** The Laravel project was created inside the existing Git repository.

### June 1, 2026

- **Event:** The Docker development environment was configured.
- **Infrastructure:** Laravel Sail was configured with MySQL, Redis, and Mailpit.
- **Issue resolved:** The initial database-backed session error was resolved by running the Laravel migrations.
- **Result:** The Laravel application and Mailpit were successfully started.

### September 1, 2026

- **Event:** The initial project baseline was completed.
- **Environment:** Laravel 13.12, PHP 8.5, Livewire 4.3, MySQL 8.4, Redis, and Mailpit were verified.
- **Decision:** PHP, Composer, Artisan, Node.js, and npm project commands are executed through Laravel Sail.
- **Isolation:** `node_modules` was moved to a dedicated Docker volume to prevent mixing native macOS and Linux dependencies.
- **Security:** Vulnerable Composer and npm dependencies were updated; both dependency audits pass without advisories.
- **Validation:** The initial tests, Laravel Pint, and the Vite production build pass successfully.

