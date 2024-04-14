<div align="center">

# CSS-BANS
*Your CounterStrikeSharp Admin Web Panel*

</div>

PHP Web-based panel for managing bans, mutes, and more on Counter-Strike 2 servers using CounterStrikeSharp and CS2-SimpleAdmin

## Requirements
- PHP >= 8.1
- CounterStrikeSharp ([GitHub Repository](https://github.com/roflmuffin/CounterStrikeSharp))
- MySQL >= 5.7
- ServerPlayerListFix ([Release Tab](#))
- SimpleAdmin ([GitHub Repository](https://github.com/daffyyyy/CS2-SimpleAdmin))

## Installation

### Setup Game Server
- Before Proceeding with installation ensure SimpleAdmin is setup from [CS2SimpleAdmin](https://github.com/daffyyyy/CS2-SimpleAdmin) or you could use the SimpleAdmin CSS plugin attached in release tab (Recommended)
- Copy **addons** folder from **serverlistplayersfix_linux.zip/serverlistplayersfix_windows.zip** to your game server folder **csgo/addons**

### Setup Web

- Download the **panel.zip** from release tab and upload the contents to your web hosting.
- Visit your site and setup will guide for the Installation

## Features

- Manage Bans
- Mange Admins
- Mange Mutes
- List Servers
- View Server Players

## Upcoming Features

- Groups
- Live Bans/Mutes/Kick from panel
- Rcon Management

## Development

*Make CSS BANS Great Again!*

- Requires Node.js version >=17
- Requires Laravel Version >=10

```bash
## Install dependencies
composer install
## Build Assets   
npm run build
## setup .env 
