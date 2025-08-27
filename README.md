# Chat com Laravel e VueJS

## Para criar usuários:

php artisan tinker

User::factory(6)->create();

## Comandos execução
```bash
php artisan serve
```

```bash
npm run dev
```

```bash
php artisan reverb:start --debug
```

## Instalação Laravel Echo no VueJS:
```bash
npm install --save-dev --force laravel-echo pusher-js @laravel/echo-vue && npm run build
```
