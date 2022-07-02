# Migración de Servidor con Docker

---

- [Propósito](#section-1)
- [Requisitos](#section-2)
- [Servidor A](#section-3)
- [Servidor B](#section-4)
- [Envío de data](#section-5)
- [Despliegue](#section-6)

<a name="section-1"></a>
## Propósito

Migrar todos los datos del facturador de un servidor A a un servidor B

<a name="section-2"></a>
## Requisitos

- acceso ssh a ambos servidores
- misma versión de sistemas operativos en ambos servidores
- si posee archivo ssh como clave en formato .ppk convertir a formato .pem


<a name="section-3"></a>
## Servidor A

* Ingresar a servidor e instalar zip `apt-get install zip unzip`
* crear una carpeta scp `mkdir scp`
* copiar el contenido de las carpetas proxy y certs `cp -r proxy/ scp/` y `cp -r certs/ scp/`
* verificar el peso del facturador `du -sh facturador/`
  * si el peso es inferior a 5GB se recomienda usar `zip -r facturador.zip facturador/`
  * si es mayor usar `tar -cvf facturador.tar facturador/` para mayor seguridad de la data
* mover el archivo comprimido a scp `mv facturador.zip scp/`
* ir a la carpeta de volumenes de docker `cd /var/lib/docker/volumes/`
* verificar el peso del volumen de base de datos `du -sh facturador1_mysqldata_1`
  * si el peso es inferior a 5GB se recomienda usar `zip -r mysql.zip facturador1_mysqldata_1/`
  * si es mayor usar `tar -cvf mysql.tar facturador1_mysqldata_1/` para mayor seguridad de la data
* mover el archivo comprimido a scp `mv facturador.zip /ruta/scp/`


> {danger} si la instalación es muy antigua (versión PRO3 que ha venido actualizando) verificar la versión de mysql directamente en el contenedor de mariadb `docker exec -ti CONTENEDOR_MYSQL mysql --version`, dicha version debe asignarse en el archivo docker-compose.yml dentro del facturador


<a name="section-4"></a>
## Servidor B

* Ingresar a servidor e instalar zip `apt-get install zip unzip`
* instalar las dependencias en el servidor

```bash
apt-get -y update
apt-get -y install git-core
apt-get -y install apt-transport-https ca-certificates curl gnupg-agent software-properties-common
curl -fsSL https://download.docker.com/linux/ubuntu/gpg | sudo apt-key add -
add-apt-repository "deb [arch=amd64] https://download.docker.com/linux/ubuntu $(lsb_release -cs) stable"
apt-get -y update
apt-get -y install docker-ce
systemctl start docker
systemctl enable docker
curl -L "https://github.com/docker/compose/releases/download/1.23.2/docker-compose-$(uname -s)-$(uname -m)" -o /usr/local/bin/docker-compose
chmod +x /usr/local/bin/docker-compose
apt-get -y install letsencrypt
docker network create proxynet

```


<a name="section-5"></a>
## Envío de data

## Desde el servidor A hacia servidor B

* se envia la carpeta scp completa hacia el servidor B
* si el servidor B usa archivo de clave (.pem) para conectarse debe cargarlo previamente

```bash
# sintaxis
# scp -r -i [clave] [carpeta_local] [USUARIO]@[IP]:[/ruta/destino]
# ejemplo
scp -r -i clave.pem scp/ root@192.196.138.123:/root/

```

* si el servidor B no usa archivo de clave

```bash
# sintaxis
# scp -r [carpeta_local] [USUARIO]@[IP]:[/ruta/destino]
# ejemplo
scp -r scp/ root@192.196.138.123:/root/

```

<a name="section-6"></a>
## Despliegue

* ingresar a la carpeta scp y descomprimir los archivos `cd scp`
* si son archivos .zip usar `unzip facturador.zip` y `unzip mysql.zip`
* si son archivos .tar usa `tar -xvf facturador.tar`
* mover las carpetas a la ruta destino (suele ser /root o /home/usuario) `mv proxy /ruta_destino` `mv facturador /ruta_destino` y `mv certs /ruta_destino`
* mover la carpeta de mysql a la ruta de volumenes de docker `mv facturador1_mysqldata_1/ /var/lib/docker/volumes/`
* ingresar a la carpeta del facturador y levantar los servicios `docker-compose up -d`
* ingresar a la carpeta del proxy y levantar los servicios `docker-compose up -d`

Si todo esta correcto solo restará cambiar la IP a la que apunta el dominio

