<div align="center">

# CSS-BANS
*Your CounterStrikeSharp Admin Web Panel*

</div>

PHP Web-based panel for managing bans, mutes, and more on Counter-Strike 2 servers using CounterStrikeSharp and CS2-SimpleAdmin
![image](https://github.com/hobsRKM/css-bans/assets/11420858/a8742df5-21ba-4d38-98d4-f7e71a0cf003)

![image](https://github.com/hobsRKM/css-bans/assets/11420858/2ca220f8-ff50-40f4-8238-dbc44270574f)

![image](https://github.com/hobsRKM/css-bans/assets/11420858/7b7fb2e3-22d3-4398-b19c-9d169789f802)

![image](https://github.com/hobsRKM/css-bans/assets/11420858/84796cb7-31d6-48ef-895e-4af1331ad71c)

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

