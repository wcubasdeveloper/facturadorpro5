# Plantilla PDF personalizada

---

- [Nombre de carpeta](#section-1)
- [Ruta de carpeta](#section-2)
- [Estructura de carpeta](#section-3)
- [Archivos principales](#section-4)

<a name="section-1"></a>
## Nombre de carpeta

se puede subir N cantidad de carpetas al servidor con nombre de personalizada\[\*\] por ejemplo:

* personalizada_1
* personalizada_cliente1
* personalizadaA

<a name="section-2"></a>
## Ruta de carpeta

La ruta a subir la carpeta es: `app/CoreFacturalo/Templates/pdf/[nombre_de_carpeta]`
<br>
debe subir la imagen referencial para la hoja A4  a: `public/templates/pdf/[nombre_de_carpeta]/image.png`

> {info} debe subir la imagen referencial para el ticket a: `public/templates/pdf/[nombre_de_carpeta]/ticket.png` para que al actualizar listado se muestre su nuevo formato

<a name="section-3"></a>
## Estructura de carpeta

los archivos que debe contener una plantilla que suban deben ser los siguientes, manteniendo los nombres de la siguiente imagen
<br>
![image](https://gitlab.com/carlomagno83/facturadorpro4/uploads/94fc5dd5f1ca589bfa59372bcebcec5d/image.png)

<a name="section-4"></a>
## Archivos principales

los archivos que debe contener una plantilla obligatoriamente son:

* carpeta partials y contenido
* style.css

> {info} el resto, que son archivos dependientes de cada tipo de documento no es obligatorio, pudiendo tener solamente por ejemplo; invoice_a4 o invoice_a5, manteniendo los formatos y los datos consultados