<div align="center">

![GitHub Releases](https://img.shields.io/github/downloads/counterstrikesharp-panel/css-bans/total)
![Build Status](https://github.com/counterstrikesharp-panel/css-bans/actions/workflows/main.yml/badge.svg)
![GitHub Stars](https://img.shields.io/github/stars/counterstrikesharp-panel/css-bans)
![GitHub Forks](https://img.shields.io/github/forks/counterstrikesharp-panel/css-bans)
![GitHub Issues](https://img.shields.io/github/issues/counterstrikesharp-panel/css-bans)

# CSS-BANS
*Your CounterStrikeSharp Admin Web Panel*

</div>

Admin web panel for CS2(counter-strike2) for managing bans, mutes, vip, skins, ranks and more on Counter-Strike 2 servers using CounterStrikeSharp

![image](https://github.com/counterstrikesharp-panel/css-bans/assets/11420858/3d45df8b-70d0-4072-b35f-949380a6978e)
![2](https://github.com/counterstrikesharp-panel/css-bans/assets/11420858/e0d9d5d0-f317-43ba-89a5-7dc1f593693d)
![1](https://github.com/counterstrikesharp-panel/css-bans/assets/11420858/761f6764-4f4e-4271-bb5c-3f685e873b7d)

## Discord (Support)
[![Discord](https://discordapp.com/api/guilds/1236186810405883904/widget.png?style=banner2)](https://discord.gg/fwg5DKZYqV)
# [Join Discord](https://discord.gg/fwg5DKZYqV)
# For installation GUIDE visit https://docs.cssbans.online

## Features
- Manage Bans
- Mange Admins
- Mange Mutes
- List Servers
- View Server Players
- Live Bans/Mutes/Kicks 
- Manage Groups
- Rcon Panel
- Skins

## Includes support for Modules
- Ranks (K4system) View/listing [https://github.com/K4ryuu/K4-System]
- VIP Core - Download from release TAB [FOLLOW DOCS GUIDE]
- Skins (Weapon Paints)

## Requirements
- PHP >= 8.1
- CounterStrikeSharp ([GitHub Repository](https://github.com/roflmuffin/CounterStrikeSharp))
- MySQL >= 5.7
- ServerPlayerListFix ([Github Repository](https://github.com/Source2ZE/ServerListPlayersFix))
- SimpleAdmin version >= 1.4.0 ([GitHub Repository](https://github.com/daffyyyy/CS2-SimpleAdmin))
- Demo: [SITE DEMO](https://cssbans.online/)

# For installation GUIDE visit https://docs.cssbans.online


## If you want to contribute for Development

*Make CSS BANS Great Again!*

- Requires Node.js version >=17
- Requires Laravel Version >=10

```bash
### Below steps is only for development purpose
cp .env.exampple .env
## Install dependencies
composer install
php artisan migrate
## Build Assets   
npm run build

