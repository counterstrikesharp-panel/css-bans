<div align="center">

![GitHub Releases](https://img.shields.io/github/downloads/counterstrikesharp-panel/css-bans/total)
![Build Status](https://github.com/counterstrikesharp-panel/css-bans/actions/workflows/main.yml/badge.svg)
![GitHub Stars](https://img.shields.io/github/stars/counterstrikesharp-panel/css-bans)
![GitHub Forks](https://img.shields.io/github/forks/counterstrikesharp-panel/css-bans)
![GitHub Issues](https://img.shields.io/github/issues/counterstrikesharp-panel/css-bans)

# CSS-BANS
*Your CounterStrikeSharp Admin Web Panel*

</div>

CSS-BANS is an admin web panel for Counter-Strike 2, powered by CounterStrikeSharp. It allows for comprehensive management of bans, mutes, VIP statuses, skins, ranks, and more on your CS2 servers.

![image](https://github.com/counterstrikesharp-panel/css-bans/assets/11420858/fc32b224-ae87-46e3-9f21-1d3b9c5df515)
![image](https://github.com/counterstrikesharp-panel/css-bans/assets/11420858/693ad9a1-1972-40d1-9281-7733508fedf9)
![image](https://github.com/user-attachments/assets/8fd95f8c-58a1-4aee-873c-f49d05f5bb4e)
![1](https://github.com/counterstrikesharp-panel/css-bans/assets/11420858/761f6764-4f4e-4271-bb5c-3f685e873b7d)
![image](https://github.com/user-attachments/assets/edf0e8e4-d0d9-4684-b663-d2adc7aa4a57)
![image](https://github.com/user-attachments/assets/d3e0d234-58f4-46b6-bfbc-c6254fda2d90)


## Support Our Work

<div align="center">
<a href="https://buymeacoffee.com/hobsrkm">
<img src="https://img.buymeacoffee.com/button-api/?text=Support Me&emoji=☕&slug=hobsrkm&button_colour=FF5F5F&font_colour=ffffff&font_family=Inter&outline_colour=000000&coffee_colour=FFDD00" />
</a>
</div>

## Discord (Support)
[![Discord](https://discordapp.com/api/guilds/1236186810405883904/widget.png?style=banner2)](https://discord.gg/fwg5DKZYqV)
# [Join Discord](https://discord.gg/fwg5DKZYqV)

## Installation Guide
For detailed installation instructions, please visit our [Documentation](https://docs.cssbans.online).

## Features
- Manage Bans
- Manage Admins
- Manage Mutes
- List Servers
- View Server Players
- Live Bans/Mutes/Kicks
- Manage Groups
- Rcon Panel
- Skins
- Appeal System
- Report Player System
- Ranks (Multi Server Support)
- Discord Webhook
- Server Player Stats Chart
- Demos 

## Modules Support
- Ranks (K4system) (Multi Server Support) - View/listing [K4-System](https://github.com/KitsuneLab-Development/K4-Zenith)
- VIP Core - Download from the release tab (Follow the documentation guide)
- Skins (Weapon Paints) (Only version 297 for now https://github.com/Nereziel/cs2-WeaponPaints/releases/tag/build-297)

## Requirements
- PHP >= 8.3 (Recommended PHP 8.3)
- CounterStrikeSharp ([GitHub Repository](https://github.com/roflmuffin/CounterStrikeSharp))
- MySQL >= 5.7
- ServerPlayerListFix ([GitHub Repository](https://github.com/Source2ZE/ServerListPlayersFix))
- SimpleAdmin version >= 1.4.0 ([GitHub Repository](https://github.com/daffyyyy/CS2-SimpleAdmin))
- Demo: [Site Demo](https://cssbans.online/)

## Supports Majority of Web Hosting Platforms
- Apache
- Nginx
- Pterodactyl Egg (https://github.com/counterstrikesharp-panel/css-bans-egg)
- Vercel
- Shared Hosting
- cPanel
- Docker

## Contributing to Development

*Make CSS-BANS Great Again!*

To contribute to the development of CSS-BANS, you'll need:

- Node.js version >=17
- Laravel Version >=10

### Development Setup

```bash
# Copy the example environment file
cp .env.example .env

# Install dependencies
composer install

# Run database migrations (if this fails, reach out on Discord for the database dump)
php artisan migrate

# Build assets
npm run build
```

For further information, please visit our [Documentation](https://docs.cssbans.online) or join our [Discord](https://discord.gg/fwg5DKZYqV) for support.

![Alt](https://repobeats.axiom.co/api/embed/25f27b45de54ec2177e206ee8dfbbde4bd01dccf.svg "Repobeats analytics image")
