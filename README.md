<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

- Compatibility Date: 2025-05-30

## Cara Installasi

### Backend

- [ ] Install Dependencies

```
cp .env.example .env
composer install --optimize-autoloader --no-dev
php artisan key:generate
```

- [ ] Migrate Database

```
php artisan migrate --seed
```

- [ ] Install Dependencies Spatie Image

```
sudo apt install jpegoptim optipng pngquant gifsicle webp
npm install -g svgo
```

- [ ] Ubah Permission Folder & Storage Link

```
sudo chown -R $USER:www-data storage
sudo chown -R $USER:www-data bootstrap/cache
chmod -R 775 storage
chmod -R 775 bootstrap/cache
php artisan storage:link
```

- [ ] Generate API Docs

```
php artisan scribe:generate
```

### Frontend

- [ ] Install PM2

```
npm install pm2@latest -g
```

- [ ] Install & Start Service

```
cd frontend
cp .env.example .env
bun install
bun run build
pm2 start ecosystem.config.cjs
pm2 restart ecosystem.config.cjs
```

## Setting Supervisor Untuk Queue Job & Task Scheduler

- [ ] Konfigurasi Lengkap
  Supervisor [disini](https://www.zentao.pm/blog/use-Supervisor-to-manage-Laravel-queue-416.html).

```
sudo apt install supervisor
sudo nano /etc/supervisor/conf.d/laravel-worker.conf
```

```
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/Skeleton/artisan queue:work --queue=default --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=4
redirect_stderr=true
stdout_logfile=/var/www/Skeleton/storage/logs/worker.log
stopwaitsecs=3600

[program:laravel-pulse]
command=php /var/www/Skeleton/artisan pulse:check
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/var/www/Skeleton/storage/logs/worker.log
stopwaitsecs=3600

[program:laravel-reverb]
command=php /var/www/Skeleton/artisan reverb:start
autostart=true
autorestart=true
user=www-data
redirect_stderr=true
stdout_logfile=/var/www/Skeleton/storage/logs/worker.log
stopwaitsecs=3600
```

```
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laravel-worker:*
sudo supervisorctl restart laravel-worker:*
sudo supervisorctl start laravel-pulse
sudo supervisorctl restart laravel-pulse
sudo supervisorctl start laravel-reverb
sudo supervisorctl restart laravel-reverb
```

- [ ] Run Task Scheduler (Optional)

```
sudo crontab -e -u www-data
* * * * * cd /var/www/Skeleton && php artisan schedule:run >> /dev/null 2>&1
```

## Setting Nginx

- [ ] Nginx config

```
sudo ln -s /etc/nginx/sites-available/skeleton /etc/nginx/sites-enabled/
```

```
server {
	#listen 80;
	# SSL configuration
	listen 443 ssl http2;
	listen [::]:443 ssl http2;
	ssl_certificate         /etc/ssl/cert.pem;
	ssl_certificate_key     /etc/ssl/key.pem;
#	ssl_client_certificate  /etc/ssl/cloudflare.crt;
#	ssl_verify_client on;

	server_name skeleton.com;

	location / {
		proxy_pass http://localhost:3001;
		proxy_http_version 1.1;
		proxy_set_header Upgrade $http_upgrade;
		proxy_set_header Connection 'upgrade';
		proxy_set_header Host $host;
		proxy_set_header X-Forwarder-For $remote_addr;
		proxy_cache_bypass $http_upgrade;
	}

	location /api {
		alias /var/www/Skeleton/public;

		try_files $uri $uri/ @api;

		add_header X-Frame-Options "SAMEORIGIN";
		add_header X-XSS-Protection "1; mode=block";
		add_header X-Content-Type-Options "nosniff";

		location ~ \.php$ {
			include snippets/fastcgi-php.conf;
			fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
			fastcgi_param SCRIPT_FILENAME $request_filename;
			include fastcgi_params;
			fastcgi_hide_header X-Powered-By;
		}
	}
	location @api {
		rewrite /api/(.*)$ /api/index.php last;
	}

	location /livewire {
		alias /var/www/Skeleton/public;

		try_files $uri $uri/ @livewire;

		add_header X-Frame-Options "SAMEORIGIN";
		add_header X-XSS-Protection "1; mode=block";
		add_header X-Content-Type-Options "nosniff";

		location ~ \.php$ {
			include snippets/fastcgi-php.conf;
			fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
			fastcgi_param SCRIPT_FILENAME $request_filename;
			include fastcgi_params;
			fastcgi_hide_header X-Powered-By;
		}
	}
	location @livewire {
		rewrite /livewire/(.*)$ /api/index.php last;
	}

	# Laravel Reverb
	location ~ ^/app/(`?<reverbkey>.*) { # variable reverbkey
		proxy_pass http://localhost:8080/app/$reverbkey;
		proxy_http_version 1.1;
		proxy_set_header Upgrade $http_upgrade;
		proxy_set_header Connection 'upgrade';
		proxy_set_header Host $host;
		proxy_set_header X-Forwarder-For $remote_addr;
		proxy_cache_bypass $http_upgrade;
	}
	location ~ ^/apps/(?<reverbid>[^/]+)/events$ { # variable reverbid
		proxy_pass http://localhost:8080/apps/$reverbid/events;
		proxy_http_version 1.1;
		proxy_set_header Upgrade $http_upgrade;
		proxy_set_header Connection 'upgrade';
		proxy_set_header Host $host;
		proxy_set_header X-Forwarder-For $remote_addr;
		proxy_cache_bypass $http_upgrade;
	}

	location ~ /\.(?!well-known).* {
		deny all;
	}
}
```

## Clear Laravel Log

```
truncate -s 0 storage/logs/laravel.log

Windows:
echo "" > storage/logs/laravel.log
```
