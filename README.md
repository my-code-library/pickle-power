# Pickle Juice

A custom WordPress plugin powering the functionality behind organicpicklejuice.com.  
Built with a modular, override-safe architecture focused on performance, security, and a clean artist-centric experience.

---

## Features

- **Modular architecture**  
  Each feature lives in its own module for clarity and maintainability.

- **Override-safe structure**  
  Designed so customizations can evolve without breaking core functionality.

- **Lightweight and fast**  
  Pure PHP with no unnecessary dependencies.

- **Artist-focused UX**  
  Tailored for the Organic Pickle Juice brand.

---

## Project structure

```text
pickle-juice/
├── includes/        # Core helpers, shared logic, utilities
├── modules/         # Self-contained feature modules
└── pickle-juice.php # Main plugin loader/bootstrap
