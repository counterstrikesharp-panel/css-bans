<div align="center">

# CSS-BANS
*Your CounterStrikeSharp Admin Web Panel*

</div>

PHP Web-based panel for managing bans, mutes, and more on Counter-Strike 2 servers using CounterStrikeSharp and CS2-SimpleAdmin
![image](https://github.com/hobsRKM/css-bans/assets/11420858/a8742df5-21ba-4d38-98d4-f7e71a0cf003)

![image](https://github.com/hobsRKM/css-bans/assets/11420858/2ca220f8-ff50-40f4-8238-dbc44270574f)

![image](https://github.com/hobsRKM/css-bans/assets/11420858/7b7fb2e3-22d3-4398-b19c-9d169789f802)

![image](https://github.com/hobsRKM/css-bans/assets/11420858/84796cb7-31d6-48ef-895e-4af1331ad71c)


## Requirements
- PHP >= 8.1
- CounterStrikeSharp ([GitHub Repository](https://github.com/roflmuffin/CounterStrikeSharp))
- MySQL >= 5.7
- ServerPlayerListFix ([Release Tab](#))
- SimpleAdmin ([GitHub Repository](https://github.com/daffyyyy/CS2-SimpleAdmin))
- Demo: [SITE DEMO](https://demo-css-bans.matchclub.xyz/)

# Docs Moved to https://docs.cssbans.online
## Installation

### Setup Game Server
- Before Proceeding with installation ensure SimpleAdmin is setup from [CS2SimpleAdmin](https://github.com/daffyyyy/CS2-SimpleAdmin) or you could use the SimpleAdmin CSS plugin attached in release tab (Recommended)
- Copy **addons** folder from **serverlistplayersfix_linux.zip/serverlistplayersfix_windows.zip** to your game server folder **csgo/addons**

### Setup Web

- 1. Download the panel **[css.bans.tar.gz](https://github.com/hobsRKM/css-bans/releases)** from release tab and upload the contents to your web hosting 
- 2. SKIP the below step if you are hosting it on site root space i.e mysite.com/
  - If you are hosting under a folder for example **mysite.com/bans** then follow the below steps
  - ```
    # Go to your site subfolder i.e mysite.com/bans and open .env file
    # Edit VITE_SITE_DIR=/bans (Your subfolder in this case)
    # Edit ASSET_URL=https://mysite.com/bans 
    # Save
    # Now visit mysite.com/bans and continue with the installation
    ```
- 3. Ignore this step if you already followed step 2. Visit your site i.e mysite.com/ and setup will guide for the Installation
- 4. The setup will check the requirements before proceeding, ensure all the requirements are met from the list.
- 5. Panel Management is allowed only for the user having **"@css/root"** permission. Please ensure that you do not give this permission to any other palyer other than the panel owner.
- 6. After successful setup, login with the same steam profile that you used while setting up the panel to manage Admins!

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

## If you want to contribute for Development

*Make CSS BANS Great Again!*

- Requires Node.js version >=17
- Requires Laravel Version >=10

```bash
cp .env.exampple .env
## Install dependencies
composer install
php artisan migrate
## Build Assets   
npm run build

