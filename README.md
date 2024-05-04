<div align="center">

# CSS-BANS
*Your CounterStrikeSharp Admin Web Panel*

</div>

PHP Web-based panel for managing bans, mutes, and more on Counter-Strike 2 servers using CounterStrikeSharp and CS2-SimpleAdmin
![image](https://github.com/counterstrikesharp-panel/css-bans/assets/11420858/0bb9c039-8ece-4a32-96ec-5fdd6b4a12a1)

## Discord (Support)
![Discord Banner 1](https://discordapp.com/api/guilds/1236186810405883904/widget.png?style=banner4)

## Features
- Manage Bans
- Mange Admins
- Mange Mutes
- List Servers
- View Server Players
- Live Bans/Mutes/Kicks 
- Manage Groups

## Requirements
- PHP >= 8.1
- CounterStrikeSharp ([GitHub Repository](https://github.com/roflmuffin/CounterStrikeSharp))
- MySQL >= 5.7
- ServerPlayerListFix ([Github Repository](https://github.com/Source2ZE/ServerListPlayersFix))
- SimpleAdmin version >= 1.4.0 ([GitHub Repository](https://github.com/daffyyyy/CS2-SimpleAdmin))
- Demo: [SITE DEMO](https://cssbans.online/)

# For installation visit https://docs.cssbans.online


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

