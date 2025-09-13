# Armazém 357 - Web Root Setup Guide

## Current Location
Your project is currently at: `/Users/lmedeiros/www/armazem357`

## Option 1: Keep Current Location (Recommended)
Your current setup is actually perfect for local development. The `/Users/lmedeiros/www/` directory is a common pattern for local web development.

### Advantages:
- ✅ Already properly organized
- ✅ Easy to backup and version control
- ✅ No permission issues
- ✅ Easy to access from your user directory

## Option 2: Move to System Web Root
If you prefer a more traditional Apache/Nginx web root setup:

### Create and Copy to /usr/local/var/www
```bash
# Create the web root directory
sudo mkdir -p /usr/local/var/www

# Copy the project
sudo cp -r /Users/lmedeiros/www/armazem357 /usr/local/var/www/

# Fix permissions
sudo chown -R $(whoami):staff /usr/local/var/www/armazem357
```

### Or Create and Copy to /var/www
```bash
# Create the web root directory
sudo mkdir -p /var/www

# Copy the project
sudo cp -r /Users/lmedeiros/www/armazem357 /var/www/

# Fix permissions
sudo chown -R $(whoami):staff /var/www/armazem357
```

## Option 3: Create Home Web Root
```bash
# Create a www directory in your home folder (if not exists)
mkdir -p ~/www

# Copy the project (if moving from current location)
cp -r /Users/lmedeiros/www/armazem357 ~/www/

# Or create a symlink
ln -s /Users/lmedeiros/www/armazem357 ~/www/armazem357
```

## Recommended Setup Commands

Since your project is already in a good location, I recommend staying with the current setup and just making sure everything is properly configured:

```bash
# Navigate to your project
cd /Users/lmedeiros/www/armazem357

# Make setup script executable
chmod +x setup-local.sh

# Run the setup script
./setup-local.sh
```

## Web Server Configuration

If you want to serve this through Apache or Nginx instead of the built-in servers:

### Apache Virtual Host Example
```apache
<VirtualHost *:80>
    ServerName armazem357.local
    DocumentRoot /Users/lmedeiros/www/armazem357/backend/public
    
    <Directory /Users/lmedeiros/www/armazem357/backend/public>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### Nginx Configuration Example
```nginx
server {
    listen 80;
    server_name armazem357.local;
    root /Users/lmedeiros/www/armazem357/backend/public;
    
    index index.php index.html index.htm;
    
    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }
    
    location ~ \.php$ {
        fastcgi_pass 127.0.0.1:9000;
        fastcgi_index index.php;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;
        include fastcgi_params;
    }
}
```

## Current Status: Ready to Use! ✅

Your project is already properly located and configured. You can start development immediately:

1. **Backend**: `cd /Users/lmedeiros/www/armazem357/backend && php artisan serve`
2. **Frontend**: `cd /Users/lmedeiros/www/armazem357/frontend && npm run dev`

The current location is actually ideal for modern web development workflow!