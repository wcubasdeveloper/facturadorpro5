# Configuración de Nuevo Validador

---

- [Introducción](#section-1)
- [Configuración de SUNAT](#section-2)
- [Configuración en facturador](#section-3)
- [Uso del validador](#section-4)

<a name="section-1"></a>
## Introducción

EL validador ha sido actualizado en la primera semana de septiembre 2021, la actualización cuenta con la implementación de un servicio de API que ha habilitado SUNAT para las consultas de documentos, para utilizarlo debe tener su facturador actualizado a una fecha superior

<a name="section-2"></a>
## Configuración de SUNAT

Descargue el siguiente PDF y siga los pasos para obtener los datos necesarios de acceso:
<br>
<a href="https://cdn.www.gob.pe/uploads/document/file/536289/Manual_de_Consulta_Integrada_de_Validez_de_CdP_por_Servicio_WEB.pdf" target="_blank">PDF</a>

<a name="section-3"></a>
## Configuración en facturador

Se debe configurar el client_id y client_secret en Configuración/Empresa/Empresa
![image](https://gitlab.com/carlomagno83/facturadorpro4/uploads/4b07934a8ae60b3f7457e18c62df3c48/image.png)

<a name="section-4"></a>
## Uso de validador

Diríjase a menú Reportes/General/Validador de documentos, allí ubique el tipo de documento, serie y correlativo o rango de correlativo a consultar, una vez consultado tendrá la respuesta, si el estado sunat difiere del estado en facturador presione el botón rojo para regularizar
![image](https://gitlab.com/carlomagno83/facturadorpro4/uploads/8a8a621dd75cdcf86b31241e6a1e73f2/image.png)