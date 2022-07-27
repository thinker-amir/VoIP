# Asterisk VoIP server (PBX) with SIPp and Laravel
---
### INTRODUCTION 
A Dockeriezed Asterisk server (PBX) with SIPp and Laravel, It implements Asterisk RealTime (config, cdr) and work with MariaDB. It uses SIPp to run stress tests and Laravel to Design the structure.

### RQUIREMENTS
* Docker 20.10 or higher
* docker-compose 2.7 or higher
* Firewall Configuration

**Open required ports**
You must open these ports on the docker server host. Because Asterisk service in docker-compose.yml uses Host network and docker can't open a large number of ports in bridge mode yet.
```bash
sudo ufw allow 5060,5061/tcp
sudo ufw allow 5060,5061/udp
sudo ufw allow 10000:20000/udp
```

### INSTALLATION FROM SOURCE
Clone Repository and move to laravel directory
```bash
git clone https://github.com/thinker-amir/VoIP
cd VoIP/laravel
```
Install Compose Dependencies
```bash
./install_composer_dependencies
```
Create .env File
```bash
cp .env.example .env
```
Config your .env File (example)
```bash
vi .env
```
Set Some Import Variables
> HOST_ADDRESS=192.168.8.117  # Change it with your real docker host ip or domain
> ...
> DB_HOST=mysql           
> DB_PORT=3306            
> DB_DATABASE=asterisk    
> DB_USERNAME=asterisk    
> DB_PASSWORD=asterisk    
> FORWARD_DB_PORT=33060   

`Warning:` It dose not prepare for production environment. Use it only on testing environment.

### RUN THE APPLICATION
```bash
./vendor/bin/sail up -d
```
Insert Defaults to Database
```bash
./vendor/bin/sail exec laravel php artisan db:seed
```

### RUN Stress Testing with SIPp
```bash
sail exec sipp sipp -sn uac -s 1 192.168.8.117 -d 100000 -l 1800 -m 1800 -r 20
```
**Change 192.168.8.117 to your docker host address**
See [SIPp cheatsheet](https://tomeko.net/other/sipp/sipp_cheatsheet.php?lang=en) for more information.

### Reference Performance Tuning
Tune performance according to Asterisk's article guide
See [this link](https://wiki.asterisk.org/wiki/display/AST/Performance+Tuning) for more information.