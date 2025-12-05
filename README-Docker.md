<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

## Cara Installasi

### Frontend

- [ ] Copy env

```
cd frontend
cp .env.example .env
```

- [ ] Sesuaikan file env

### Backend

- [ ] Copy env

```
cp .env.example .env
```

- [ ] Sesuaikan File env dan ubah DOCKER_CONTAINER_NAME
- [ ] Build docker menggunakan docker compose (Tambahkan `--build` untuk build ulang)

```
docker compose up -d
```

- [ ] Generate Key (Optional)

```
docker compose exec backend php artisan key:generate
```

- [ ] Migrate Database (Optional)

```
docker compose exec backend php artisan migrate --seed
```

- [ ] Generate API Docs

```
docker compose exec backend php artisan scribe:generate
```

## Setting Supervisor Untuk Queue Job & Task Scheduler

- [ ] Sesuaikan config file `docker/laravel-worker.conf`

## Clear Laravel Log

```
truncate -s 0 storage/logs/laravel.log

Windows:
echo "" > storage/logs/laravel.log
```
