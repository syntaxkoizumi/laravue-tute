## 環境構築
```
docker-compose up
docker-compose exec web php artisan serve --host 0.0.0.0 --port 8081
docker-compose exec web npm install
docker-compose exec web npm install -D vue vue-router
docker-compose exec web npm run watch
```

## 開発
```
docker-compose exec web php artisan serve --host 0.0.0.0 --port 8081
docker-compose exec web npm run watch
```