# Hola Mundo · Yii2 (framework real)

Portal del framework **Yii2** (creado con `composer create-project yiisoft/yii2-app-basic`),
parte de una serie de 9 portales "Hola Mundo" con un **cuadro comparativo** común.

> Tipo: **framework real**. Controlador (`controllers/SiteController.php`), vista
> (`views/site/portal.php`), acción `site/api` (JSON) y validación reales. 5 funciones
> (mezcla) + tabla comparativa de los 9.

## Local
```bash
composer install
php yii serve
```
## Docker
```bash
docker build -t php-yii2 . && docker run -p 8080:80 php-yii2
```
Coolify: Build Pack **Dockerfile**, puerto **80**.
