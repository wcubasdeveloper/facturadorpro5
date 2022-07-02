# Migración de documento individual

---

- [Propósito](#section-1)
- [Herramientas](#section-2)
- [Proceso](#section-3)
- [Tablas de interés](#section-4)
- [Scripts utilizados](#section-5)
- [Resultado](#section-6)

<a name="section-1"></a>
## Propósito

Crear un registro que pertenece a una versión anterior del facturador en la versión actual para gestionar otros procesos como nota de credito/debito

<a name="section-2"></a>
## Herramientas

se utilizó Navicat para las pruebas

> {info} más adelante se indican las consultas utilizadas de manera que puedan ser usadas en otros gestores de base de datos

<a name="section-3"></a>
## Proceso

* los datos se enviaron desde un facturador PRO2 a un facturador PRO4
* ambos facturadores cuentan con los mismos datos de empresa cliente
* conectarse a la base de datos del cliente de ambos facturadores
* en el pro2 ubicar la tabla y el registro, se selecciona toda la linea y en el menú superior (3 barras) se copia a modo de inserción

![image](https://gitlab.com/carlomagno83/facturadorpro4/uploads/7a21e76852f975c507309596104b3c67/image.png)

* en el pro4 en la pestaña query se crea una nueva

![image](https://gitlab.com/carlomagno83/facturadorpro4/uploads/c9abbad826dbabcfb56de1f1751e7b5a/image.png)<br>

* en el editor se pega lo obtenido del pro2 y se alteran los datos que correspondan <br>

![image](https://gitlab.com/carlomagno83/facturadorpro4/uploads/813769a66dab105d0751f99b96029b92/image.png)<br>

* para correr el script se pulsa el boton Run, verificar en la tabla destino si el dato esta correcto

<a name="section-4"></a>
## Tablas de interés

### tabla documents

* ambas bd deben contener los mismos usuarios, de no tenerlos asignar el id del usuario
* eliminar campo ID ya que es autoincrementable y no se debe duplicar
* customer_id debe coincidir con el ID de la tabla persons
* al insertar no se valida si el numero de documento es único, por lo que puede ocasionar duplicidad

### tabla invoice

* se debe importar el documento junto con invoice de tener relacion, asignando el id de documents en document_id correspondiente
* eliminar campo ID

### tabla document_items

* previamente debería haberse llenado items
* eliminar campo ID
* si hay items ya registrados entonces el campo item_id debe cambiarse al que tomó el item importado
* campo is_set; agregar al campo item(json): `, \"is_set\": 0,`
* pdf obtenido de una migración de pro2

## Clientes

### tabla persons

* eliminar campo ID
* id optenido es el que debe usarse en customer_id

## Productos

### tabla items

* agregar campos \`name\` y `second_name`
* eliminar campo ID

### tabla item_unit_types (lista de precios)

* coincidir item_id con la tabla items
* eliminar campo ID

<a name="section-5"></a>
## Scripts utilizados

[document_items.txt](https://gitlab.com/carlomagno83/facturadorpro4/uploads/1e472a356248907822f8263597a24785/document_items.txt)

[documents.txt](https://gitlab.com/carlomagno83/facturadorpro4/uploads/7c23b2ca6c7c9dc7994a40f15dd6e6e3/documents.txt)

[invoices.txt](https://gitlab.com/carlomagno83/facturadorpro4/uploads/54f19ca76a8b8c5a6065abb50ca7378e/invoices.txt)

[item_unit_types.txt](https://gitlab.com/carlomagno83/facturadorpro4/uploads/69cd34ee84985e2fd430bfd4a0df1d16/item_unit_types.txt)

[items.txt](https://gitlab.com/carlomagno83/facturadorpro4/uploads/4d5d6e1f67da8146e5cfa37e3de602b6/items.txt)

[persons.txt](https://gitlab.com/carlomagno83/facturadorpro4/uploads/8e7c38d054f588a333391bcdcb9a48d9/persons.txt)

<a name="section-6"></a>
## Resultado

![image](https://gitlab.com/carlomagno83/facturadorpro4/-/wikis/uploads/032cf46e21785080f48c097318e67a24/image.png)

![image](https://gitlab.com/carlomagno83/facturadorpro4/-/wikis/uploads/484e9718d0ae1e66f2fb922d4579965d/image.png)