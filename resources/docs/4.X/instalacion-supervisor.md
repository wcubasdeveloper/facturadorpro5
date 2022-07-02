
# Instalación de Supervisor

---

- [Descripción](#section-1)
- [Requerimientos](#section-2)
- [Pasos](#section-3)

<a name="section-1"></a>
## Descripción

Supervisor es un monitor de procesos comúnmente utilizado en entornos Linux, este se encarga de detectar las tareas solicitadas y asignarlas en una cola para que se ejecuten, la implementación de este servicio en el facturador es con la intención de que las tareas complejas se ejecuten en segundo plano de manera organizada mediante las colas para no colapsar los recursos de los servidores ni de la aplicación.


<a name="section-2"></a>
## Requerimientos previos

* Tener una instalación realizada con los scripts de Docker
* Conocer los comandos básicos de docker y el uso de los contenedores del facturador
* Conocer los comandos básicos de laravel para la eliminación de caché
* Conocer la edición de archivos en sistemas linux

> {info} Para instalaciones en entornos LAMP debe recurrir a los manuales en la web del framework https://laravel.com/docs/5.7/queues#supervisor-configuration

<a name="section-3"></a>
## Pasos

1. conectarse vía SSH a su servidor
2. dirigirse a la carpeta del proyecto, mayormente esta en /root/facturadorpro41/  **la ruta le es entregada en la instalación por su canal de slack si no coincide con la anterior**
```bash
cd /root/facturadorpro41
```
3. detener los servicios de docker; no tendrá acceso al facturador desde este paso
```bash
docker-compose down
```
4. editar el archivo docker-compose.yml
```bash
nano docker-compose.yml
```
5. obtener el número de proyecto mayormente **1**, si tiene más de un facturador en su servidor ubicarlo en los nombres dentro del archivo en edición, por ejemplo:
```bash
nginx1
fpm1
## el numero al final es el que usará en los siguientes pasos
```
6. debe tomar en cuenta los espacios y saltos de línea para añadir el siguiente extracto reemplazando [número_proyecto] por su el número obtenido en el paso anterior, además debe añadirlo antes de la línea que menciona **"networks:"**
```yaml
    supervisor[numero_proyecto]:
        image: rash07/php7.4-supervisor
        working_dir: /var/www/html
        volumes:
            - ./:/var/www/html
            - ./supervisor.conf:/etc/supervisor/conf.d/supervisor.conf
        restart: always
```
resultando como el siguiente ejemplo:
```yml
version: '3'
    services:
        nginx1:
            image: rash07/nginx
            working_dir: /var/www/html
            environment:
                VIRTUAL_HOST: facturalo.pro, *.facturalo.pro
            volumes:
                - ./:/var/www/html
                - $PATH_INSTALL/proxy/fpms/$DIR:/etc/nginx/sites-available
            restart: always
        fpm1:
            image: rash07/php-fpm:2.0
            working_dir: /var/www/html
            volumes:
                - ./ssh:/root/.ssh
                - ./ssh:/var/www/.ssh
                - ./:/var/www/html
            restart: always
        mariadb1:
            image: mariadb:10.5.6
            environment:
                - MYSQL_USER=\${MYSQL_USER}
                - MYSQL_PASSWORD=\${MYSQL_PASSWORD}
                - MYSQL_DATABASE=\${MYSQL_DATABASE}
                - MYSQL_ROOT_PASSWORD=\${MYSQL_ROOT_PASSWORD}
                - MYSQL_PORT_HOST=\${MYSQL_PORT_HOST}
            volumes:
                - mysqldata1:/var/lib/mysql
            ports:
                - "\${MYSQL_PORT_HOST}:3306"
            restart: always
        redis1:
            image: redis:alpine
            volumes:
                - redisdata1:/data
            restart: always
        scheduling1:
            image: rash07/scheduling
            working_dir: /var/www/html
            volumes:
                - ./:/var/www/html
            restart: always
        supervisor1:
            image: rash07/php7.4-supervisor
            working_dir: /var/www/html
            volumes:
                - ./:/var/www/html
                - ./supervisor.conf:/etc/supervisor/conf.d/supervisor.conf
            restart: always
    networks:
        default:
            external:
                name: proxynet
    volumes:
        redisdata1:
            driver: "local"
        mysqldata1:
            driver: "local"
```
7. una vez agregado el extracto guardar y cerrar el archivo en edición
8. proceder con la creación de un nuevo archivo (en este paso todavía estamos dentro de la carpeta del proyecto, no salir de ella si no ha sido mencionado en algún paso)
```bash
nano supervisor.conf
```
9. añadir el siguiente extracto (de conocer las instrucciones puede editarlas en este paso)
```ASCII
[program:laravel-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/html/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=root
numprocs=8
redirect_stderr=true
stdout_logfile=/var/www/html/storage/logs/supervisor.log
stopwaitsecs=3600
```
10. una vez finalizado, guardar y cerrar el archivo
11. editar el archivo .env
```bash
nano .env
```
12. dentro del archivo ubicar el parametro de conexion de colas y asignarle el valor de "database"
```bash
QUEUE_CONNECTION=database
```
13. una vez finalizado, guardar y cerrar el archivo
14. iniciar los servicios de docker, si hay error en el proceso algo ha estado mal, por favor comunicar por su canal de slack para mayor atención
```bash
docker-compose up -d
```
15. una vez levantado los servicios correctamente, se debe ingresar al contenedor php-fpm, de no conocer como hacerlo, ubicar el manual de [actualización](https://docs.google.com/document/d/1ekGySBjGHspbPEE3OLkMGlWwjLvudmyLKo9Et-Cxejk/edit?usp=sharing)
16. dentro del contenedor, ejecutar el siguiente comando para eliminar la caché y que el proyecto tome los cambios antes realizados
```bash
php artisan config:cache ; php artisan cache:clear
```
17. si todo esta correcto, salir con **exit** del contenedor
18. ingresar al contenedor supervisor, de la forma similar en que ingresó al contenedor de php
19. ejecutar los siguientes comandos para que sean leídos los archivos agregados
```bash
service supervisor start
supervisorctl reread
supervisorctl update
supervisorctl start all
```
20. si todo esta correcto, salir con **exit** del contenedor y habrá finalizado el proceso

> {info} en caso de realizar modificaciones en el archivo supervisor.conf utilizar posteriormente los comandos del pto. 20