# Módulo de Farmacia

---

- [Activación del módulo](#section-1)
- [Configuración de empresa](#section-2)
- [Creación de producto](#section-3)
- [Lista de productos](#section-4)
- [Importar Productos](#section-5)
- [Exportar lista para DIGEMID](#section-6)

<a name="section-1"></a>
## Activación del módulo

### Desde Admin

Es posible habilitar el modulo directamente desde la sección de módulos de usuario en el admin
<br><img src="/wiki/farma/image.png" ><br>

Al momento de darle permisos al usuario, la configuración para farmacia quedará habilitada. También si se le quita el acceso al usuario se desactivará.

### Desde la configuración

Es posible habilitar el modulo desde la configuración https://{{ $_SERVER['HTTP_HOST'] }}/advanced buscando el selector para "Habilita elementos de farmacia" en la pestaña visual de la configuración avanzada
<br><img src="/wiki/farma/image-1.png" ><br>

<a name="section-2"></a>
## Configuración de empresa

En los datos de empresa, para la dirección https://{{ $_SERVER['HTTP_HOST'] }}/companies/create. se habilita "Datos de farmacia" el cual corresponde a "Código de observación DIGEMID"

<br><img src="/wiki/farma/image-2.png" ><br>

Este datos se utilizará mas adelante.

<a name="section-3"></a>
## Creación de producto

cuando se crea un producto desde este apartado, se requerirá el código DIGEMID y el Registro sanitario.
<br><img src="/wiki/farma/image-3.png" ><br>

> {danger} debe registrar sus propios productos con la información correcta antes de continuar con los siguientes pasos

<a name="section-4"></a>
## Lista de productos DIGEMID

https://{{ $_SERVER['HTTP_HOST'] }}/digemid

<br><img src="/wiki/farma/image-4.png" ><br>

Se habilita del modulo de Farmacia, productos, para listar todos los productos de farmacia disponibles.
 
IMPORTANTE: EL PRODUCTO DEBE TENER EL CODIGO DIGEMID CORRECTO PARA HACER MATCH CON EL CODIGO DE PRODUCTO DEL CATALOGO


<a name="section-5"></a>
## Importar Productos para exportación

Se usa como base el archivo facilitado por DIGEMIDen http://opm.digemid.minsa.gob.pe/#/consulta-producto en la sección "Menu Principal" haciendo click en el "Catálogo de productos farmacéuticos"

<br><img src="/wiki/farma/image-5.png" ><br>

al descargar el archivo xlsx. se procede a importarlo en la plataforma, en la sección Importar

<br><img src="/wiki/farma/image-6.png" ><br>

Esto marca el producto para su futura exportación  de forma automática.

<br><img src="/wiki/farma/image-7.png" ><br>
<br>solo se considerará los productos registrados en el sistema

<a name="section-6"></a>
## Exportar lista para DIGEMID

<br><img src="/wiki/farma/image-8.png" ><br>

Se genera un archivo xls con la siguiente estructura. Solo se exportarán los productos que fueron seleccionados para este fin.


| CodEstab | CodProd | Precio 1 | Precio 2 |
|----------|---------|----------|----------|
| 0023986  | 48883   | 13,00    | 2,00     |


