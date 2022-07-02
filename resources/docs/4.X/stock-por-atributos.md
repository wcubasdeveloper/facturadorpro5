# Atributos de items

---

- [Habilitar los atributos extra de items](#section-1)
- [Gestion de atributos](#section-2)
- [Stock por atributos](#section-3)
- [Producto - busqueda individual - Por atributos](#section-4)
- [Plantillas personalizadas](#section-5)

<a name="section-1"></a>
# Habilitar los atributos extra de items

Se debe ingresar a la configuracion, ubicar la seccion Avanzado en Empresa, y luego ir a la seccion visual<br>
![image](https://gitlab.com/carlomagno83/facturadorpro4/uploads/597b67c13db9c3b4957159e4829dd1ef/image.png) <br>
Aqui se procede a habilitar el item "Muestra campos opcionales para los Items a modo informativo " para que pueda mostrarse el menu extra.<br>
### Ubicacion de apartado _Datos extra de items_
Luego de habilitar los campos opcionales, es necesario dirigirse a crear los nuevos atributos. Para ello, se procede a abrir el menu lateral, expandir la seccion de inventario e identificar **Datos extra de items**  en el menu. Nos envia al apartado de listado de atributos<br> 
![image](https://gitlab.com/carlomagno83/facturadorpro4/uploads/f18a983b6f16eeae38b9ca24ba2bbb41/image.png) <br>
Estos atributos no deben considerarse para la base del sistema ya que son opcionales. 
<a name="section-2"></a>
# Gestion de atributos
Los atributos contaran solo con el nombre, Estos seran utilizados en la edicion del item para poder realizar la asociacion correspondiente.<br> 
![image](https://gitlab.com/carlomagno83/facturadorpro4/uploads/e85b43f046e8ac52be3bb6893851e1c7/image.png) <br>
### Seccion de Atributos del item
En el menu de productos, podremos relacionar los atributos a los items, esta habilitado para que un producto pueda tener multiples valores en los diversos atributos. Se debe tener en cuenta que solo se mostrarán los atributos seleccionados en esta seccion.
![image](https://gitlab.com/carlomagno83/facturadorpro4/uploads/5eb185ebe76c92713912ca8ae02ee5db/image.png) <br>
### Eligiendo atributos en Boleta/Factura
Al gestionar una compra, si seleccionamos un item que tenga atributos extra, podra seleccionarse, lo que ayudará a poder contablizar su stock. Se debe tomar en cuenta que si no se selcciona un atributo, este no podrá ser evaluado para el stock.<br> 
![image](https://gitlab.com/carlomagno83/facturadorpro4/uploads/bf612d70961329a058543c940ba2e3e5/image.png) <br>


### Eligiendo atributos en Compra
Para poder aumentar stock de un producto por sus atributos, es necesario llegar a comprar el producto con esos atributos. Por lo tanto se deberá crear un item por cada combinacion de color que tenga<br>
![image](https://gitlab.com/carlomagno83/facturadorpro4/uploads/b0212230f22e442d88211f45f79c5315/image.png) <br>

<a name="section-3"></a>
# Stock por atributos
Para poder visualizar el stock por los diversos atributos, es necesario ir a la seccion de productos, en mostrar/ocultar columnas, seleccionar **Stock por Datos Extra** lo que mostrará la columna respectiva.<br> 
Cuando un item cuente con los atributos, se mostrará un boton el cual mostrará el stock por los diversos atributos. 
<br>
![image](https://gitlab.com/carlomagno83/facturadorpro4/uploads/587d0745e7ddde796ff655573fecec8e/image.png) <br>

Este modal mostrará el total de stock en inventario y tambien, el detalle por cada atributo.<br>
![image](https://gitlab.com/carlomagno83/facturadorpro4/uploads/ac02fb1c381c0481d3678736922554fd/image.png) <br>
Si se realiza una compra con los atributos, estos aumentaran, como el siguiente ejemplo <br>
![image](https://gitlab.com/carlomagno83/facturadorpro4/uploads/9a42dc8ec40b59dbfcea42a5694ef4b7/image.png) <br>


<a name="section-4"></a>
# Producto - busqueda individual - Por atributos
Se ha destinado un reporte especifico de ventas para cuando el apartado de atributos este activo.<br> Este se encuentra en la seccion de reportes -> Ventas -> Producto - busqueda individual - Por atributos<br>
![image](https://gitlab.com/carlomagno83/facturadorpro4/uploads/3f7d3f83e8321c34e875309cfefcf18d/image.png) <br>
El cual mostrará los filtros dependiendo del producto<br>
![image](https://gitlab.com/carlomagno83/facturadorpro4/uploads/329446144aad6fe3c16d7123a52c64f3/image.png) <br>
Y es posible filtrar los movimientos por los atributos. Como ejemplo la siguiente imagen sin el filtro de colores.  <br>
![image](https://gitlab.com/carlomagno83/facturadorpro4/uploads/16bdae5b5577923541f5a44deaecb5bc/image.png) <br>

Es posible exportrarlo en excel.<br>


<a name="section-5"></a>
# Plantillas personalizadas
Es posible utilizar estos valores en una plantilla, si en la seccion de `->item` de la plantilla es llamado de este modo

`@foreach($document->items as $row)
@php
$extra_string = $row->getPrintExtraData(); @endphp`

Lo que mostrará un array similar a este<br>
![image](https://gitlab.com/carlomagno83/facturadorpro4/uploads/d286b818e94c584789feb60d0eb7eaef/image.png)
Actualmente solo esta implementado en Comprobante electrónico (Boleta o factura) y Compras exclusivamente, y solo se implementa color en la plantilla de boleta/factura `default3_596`

![image](https://gitlab.com/carlomagno83/facturadorpro4/uploads/5eb83d1e144ad8d06092d746f33a903e/image.png)
