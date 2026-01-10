# 🥒 Pickle Juice

## A Custom WordPress Plugin for OrganicPickleJuice.com

Pickle Juice is a modular, override‑safe WordPress plugin powering the custom functionality behind [organicpicklejuice.com](https://organicpicklejuice.com).

It’s built for performance, security, and a clean creative workflow — designed to support an artist‑centric web presence with minimal bloat and maximum control.

## 🚀 Features

- **Modular Architecture**  
Each feature lives in its own module for clarity, maintainability, and selective loading.

- **Override‑Safe Structure**  
Designed so customizations can evolve without breaking core functionality.

- **Lightweight & Fast**  
Pure PHP, no unnecessary dependencies, and optimized for production hosting.

- **Artist‑Focused UX**  
Tailored for the needs of the Organic Pickle Juice brand — clean, branded, and minimal.

## 📁 Project Structure

```
pickle-juice/
│
├── includes/        # Core helpers, shared logic, utilities
├── modules/         # Self-contained feature modules
└── picklejuice.php  # Main plugin loader/bootstrap
```

## 🔧 Installation

1. Download or clone the repository:
   ```
   git clone https://github.com/my-code-library/pickle-juice.git
   ```

2. Place the folder into:
   ```
   wp-content/plugins/pickle-juice
   ```

3.  Activate Pickle Juice from the WordPress Plugins admin screen.

## 🧩 Creating New Modules

Modules live in `/modules` and are automatically loaded by the plugin bootstrap.

A typical module structure:

```
modules/
└── example-module/
    ├── example-module.php
    └── assets/
```

## 🛡️ Security & Best Practices

- Sanitization and escaping follow WordPress standards
- No direct file access
- Modular loading prevents unnecessary code execution
- Ideal for production environments with custom branding needs

## 🧪 Development

Enable debugging in `wp-config.php`:

```
define( 'WP_DEBUG', true );
define( 'WP_DEBUG_LOG', true );
```
Then tail your logs while working:

```
tail -f wp-content/debug.log
```

## 📜 License

```
Pickle Juice – A modular WordPress plugin for branded login, security, and admin UX.
Copyright (C) 2026 Gold Coast Music/Pickle Juice

This program is free software; you can redistribute it and/or modify
it under the terms of the GNU General Public License as published by
the Free Software Foundation; either version 2 of the License, or
(at your option) any later version.

This program is distributed in the hope that it will be useful,
but WITHOUT ANY WARRANTY; without even the implied warranty of
MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
GNU General Public License for more details.

You should have received a copy of the GNU General Public License
along with this program; if not, write to the Free Software
Foundation, Inc., 51 Franklin Street, Fifth Floor, Boston, MA 02110-1301 USA.
```

## 💬 About

Pickle Juice is a custom WordPress plugin built to support the evolving digital identity of the [Pickle Juice Electronic Dance Music](https://organicpicklejuice.com/) artist project.

It is maintained by the artist via *my-code-library*.
