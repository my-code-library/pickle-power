Pickle Juice
Pickle Juice is a custom WordPress plugin powering the functionality behind organicpicklejuice.com. . It uses a modular and override‑safe architecture focused on performance, security, and a clean artist‑centric workflow.

Features
Pickle Juice is built around self‑contained modules that keep the plugin lightweight, maintainable, and easy to extend. The architecture avoids unnecessary dependencies and supports a clean, brand‑aligned user experience.

Project Structure
The plugin includes a main loader file, a directory for shared utilities, and a modules directory where each feature is isolated in its own folder. This structure keeps the system predictable and easy to evolve.

Installation
To install Pickle Juice, download or clone the repository and place it inside the WordPress plugins directory. After adding the plugin to the server, activate it through the WordPress admin interface.

Creating New Modules
New features can be added by creating a folder inside the modules directory and adding a PHP file that contains the module’s logic. Modules should remain self‑contained and use shared utilities from the includes directory when appropriate. Naming should be prefixed to avoid conflicts.

Development
Debugging can be enabled in the WordPress configuration file to assist with development. Monitoring the WordPress debug log provides insight into plugin behavior and helps ensure that new modules integrate smoothly with the existing architecture.

License
This project is currently unlicensed.

About
Pickle Juice is developed to support the creative and technical direction of the Organic Pickle Juice artist project. It provides a flexible, brand‑aligned foundation for custom WordPress functionality and continues to evolve alongside the project’s artistic vision.
