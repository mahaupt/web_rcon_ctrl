# Web based rcon controller
## Description
- Web based rcon controller - Lets the web control your gameserver  
- Executes commands via rcon on a remote server  
- Cooldown timer to avoid overuse  
- OAuth2 Twitch support to display user names  
- Rcon script from https://github.com/thedudeguy/PHP-Minecraft-Rcon

## Installation
1. Put the files on your web server  
2. Create a mysql database and create tables according to DB.md
3. edit config.inc.php
4. Get a twitch client_id, secret and specify your redirect_url
5. Put in your rcon credentials inside config.inc.php
6. Create items in your database table. The cmd field should contain your executed command.
