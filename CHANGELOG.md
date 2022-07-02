## 5.0.0

### docs
2022-03-22 : docs | update changelog<br>


### fixed
2022-05-23 : fixed | #1688 | Validar propiedad prepayments no disponible en notas sin external id<br>
2022-05-20 : fixed | #1684 | Ajustes en querys para error en reporte productos - caja chica<br>
2022-05-19 : fixed | #1663 | Se agrega descripcion para configuracion prod de una ubicacion<br>
2022-05-19 : fixed | #1670 | se elimina clg<br>
2022-05-19 : fixed | #1670 | Se agrupa totales por producto y tipo de unidad para ventas individuales y por presentaciones - Consolidado de items, ajuste al genera guia, suma de cantidad<br>
2022-05-17 : fixed | #1394 | Se agrega propiedad para que cambio de moneda solo afecte en model invoice<br>
2022-05-16 : fixed | #1497 | Se agrega notas de credito a cuentas por pagar/pagos cpe<br>
2022-05-16 : fixed | #1519 | Ajuste redondeo total compra, error relacionado al calculo<br>
2022-05-16 : fixed | #1519 | Ajuste para modificar la funcion de redondeo en reportes general de productos, compras y  ventas / reporte compras<br>
2022-05-13 : fixed | #1654 | Mostrar notificaciones de forma separada - se agrega indice en bd para busqueda de cpe por rectificar<br>
2022-05-13 : fixed | #1665 | Ajuste f vencimiento en notas venta, edicion y formatos pdf<br>
2022-05-12 : fixed | #1480 | Ajustes para formato ventas, aplicar conversion al t/c - se modifica a codigo de moneda<br>
2022-05-12 : fixed | #1611 | Se agrega precio de compra / calculos a packs para calcular utilidades<br>
2022-05-12 : fixed | #1660 | Ajuste para asignar item correctamente al agregar producto en pos - error de asignacion lista precio<br>
2022-05-11 : fixed | temas de colores ocultos y arreglos en los que se muestran - iconos de cantidades en admin<br>
2022-05-10 : fixed | tema dark<br>
2022-05-06 : fixed | #1632 | Ajuste para mostrar mensaje en reporte movimientos<br>
2022-05-06 : fixed | #1632 | Se modifica ProcessMovementsReport a cola por defecto<br>
2022-05-06 : fixed | #1537 | Ajuste para error al enviar correo cuando el cpe no tiene cdr<br>
2022-05-06 : fixed | #1631 | mejoras en lista de banco para a4 y altura de pagina en guia, plantilla default3_new_unit_value<br>
2022-05-05 : fixed | #1636 | Ajuste para asignar igv al editam - se elimina asignacion del total_base_igv a precio unitario - cotizaciones<br>
2022-05-05 : fixed | #1634 | Ajuste para registros duplicados - resumen de boletas<br>
2022-05-04 : fixed | #1623 | Ajuste para mostrar productos correspondientes al almacen que tiene ingreso<br>
2022-05-03 : fixed | #1593 | Se elimina propiedad de configuracion no relacionado a permisos pagos<br>
2022-05-02 : fixed | #1478 | Ajuste para filtros productos inhabilitados en transferencia masiva inventario - Problemas de acceso a propiedad en busqueda items<br>
2022-05-02 : fixed | #1575 | corregir espaciado entre columnas en cotizacion, nota de venta, contrato, pedidos<br>
2022-05-02 : fixed | #1570 | bug multiopcion mostrar/ocultar columnas en modulo notas de venta<br>
2022-04-28 : fixed | #1565 | Se agrega codigo m pago api - pago credito<br>
2022-04-28 : fixed | #1605 | Ajuste lotes al generar cpe desde pedido - ajustes en formato defaul end date<br>
2022-04-27 : fixed | #1605 | Ajuste lotes al generar cpe desde pedido<br>
2022-04-26 : fixed | #1406 | Se agrega validacion para montos mayores a 0, p unitario - nuevo cpe<br>
2022-04-26 : fixed | #1507 | Ajustes para compras en usd, asignacion correcta moneda, error por acceso a prop al cambiar de moneda<br>
2022-04-26 : fixed | #1608 | Ajuste para mostrar estado de pago anulado en n venta<br>
2022-04-26 : fixed | #1401 | Se elimina propiedad sin uso<br>
2022-04-25 : fixed | #1570 | bug multiopcion mostrar/ocultar columnas en modulo notas de venta<br>
2022-04-25 : fixed | #1401 | Recuperando cambios obsoletos del issue #1401 que quedaron sin culminar<br>
2022-04-22 : fixed | #1574 | Obtener precios desde documento relacionado pedido al generar cpe desde guia (configurable)<br>
2022-04-22 : fixed | #1574 - #1619 | Obtener precios desde documento relacionado (cot, nv) al generar cpe desde guia (configurable) - ajuste para relacionar nota de venta a guia<br>
2022-04-22 : fixed | #1598 | logo de sucursal modulo configuracion<br>
2022-04-22 : fixed | #1543 | Modificar tipo de dato campo obs nota de venta<br>
2022-04-21 : fixed | #1613 | Ajustes para error al ingresar compras de lotes por presentacion<br>
2022-04-21 : fixed | #1589 | Ajustes para descuento de lotes por presentacion - anulacion/rechazo cpe, registro n venta<br>
2022-04-21 : fixed | #1575 | corregir espaciado entre columnas en cotizacion, nota de venta, contrato, pedidos<br>
2022-04-20 : fixed | #1589 | Correccion descuento de stock de lotes por presentacion - ajustes em ventas y n credito<br>
2022-04-20 : fixed | #1602 | bug nombre de cliente desde modulo hotel<br>
2022-04-20 : fixed | #1506 | Ajustes a filtros cat/marca reporte inventario - formatos / ajustes bandeja de descargas<br>
2022-04-19 : fixed | #1506 | Ajustes filtros categoria/marca en vista - reporte inventario<br>
2022-04-19 : fixed | #1519 | P3 - Se agrega configuracion para agregar igv en edicion de compras<br>
2022-04-19 : fixed | #1595 | Ajuste para mostrar precio unitario con decimales/entero - productos/servicios<br>
2022-04-18 : fixed | #1313 | Se agrega vueltos a pdf default notas de venta<br>
2022-04-18 : fixed | #1591 | Se agrega control de lotes a n venta generada desde ecommerce<br>
2022-04-11 : fixed | #1535 | Ajustes para eliminar cpe, nv, cot en entorno demo<br>
2022-04-11 : fixed | #1583 | Ajustes para manejar el nombre producto pdf al generar guia y contrato desde cotizacion<br>
2022-04-11 : fixed | #1571 | Ajustes para pago por defecto - pos rapido<br>
2022-04-08 : fixed | #1571 | Modificando componente pagos pos rapido<br>
2022-04-07 : fixed | #1564 | Ajuste para error al duplicar n venta - se duplica con misma relacion al cpe y no se puede generar uno nuevo<br>
2022-04-07 : fixed | #947 | Ajuste para error certificado, sobreescribe valor nulo al guardar datos de empresa<br>
2022-04-07 : fixed | #1391 | Ajustes campo unico filename en notas de venta - filtros pos rapido<br>
2022-04-07 : fixed | restaurante | change route api<br>
2022-04-06 : fixed | restaurate | get users relations<br>
2022-04-06 : fixed | #1391 | Se agrega campo unico para restriccione en n ventas<br>
2022-04-06 : fixed | #1344 | Ajustes para filtro serie al cambiar cliente - filtrar serie al iniciar componente pago (pos rapido)<br>
2022-04-05 : fixed | #1459 | Se modifica componente btn para bloquearlo al generar venta (registros duplicados) - venta rapida pos<br>
2022-04-05 : fixed | #1284 | Ajustes en formato y totales, reporte utilidades detallado<br>
2022-04-05 : fixed | #1284 | Ajustes para mostrar el detalle del producto - reporte detallado de utilidades<br>
2022-04-01 : fixed | #1562 | Modificacion de flujo para calcular cotizaciones que solo tengan pagos - caja pos<br>
2022-04-01 : fixed | #1357 | fecha de pago a credito no debe ser inferior o igual a la fecha de emision (nota a cpe)<br>
2022-03-25 : fixed | #1360 | Ajustes para error al actualizar vendedor - nuevo cpe, nc, cot<br>
2022-03-24 : fixed | #1356 | Ajustes para reporte ventas por producto (muestra totales incorrectos) - dashboard<br>
2022-03-24 : fixed | #1476 | Ajustes para calcular totales por linea y globales usando decimales sin redondeo, pos<br>
2022-03-23 : fixed | #1449 | Ajuste de error en utilidades cuando se genera nv con dscto - dashboard<br>
2022-03-23 : fixed | #1514 | error htmlspecialchars en pedidos creados desde ecommerce al generarse la nota de venta - tiene que ver con fecha de vencimiento de productos con lote<br>
2022-03-21 : fixed | #1536 | Ajuste a condicion para agregar tag LineExtensionAmount cuando el valor es 0, para nc tipo 03<br>


### feature
2022-05-23 : feature | #1666 | Se agrega funcionalidad para mostrar decimales de p unitario en fact/boletas para template default - configurable<br>
2022-05-20 : feature | #1622 | Se agrega reporte de caja por metodo de pago efectivo con destino caja, para cpe, nv, cot, s tecnico, compras, gastos - se consideran anulados<br>
2022-05-20 : feature | #888-2 | reporte caja chica efectivo<br>
2022-05-19 : feature | #1526 | guia de remision añadir campo descripcion de motivo<br>
2022-05-19 : feature | #1675 | Se agregan ejemplos de listado marcas/categorias - actualizar item<br>
2022-05-19 : feature | #1675 | Se agregan propiedad a listado de productos por api, lista de categorias y marcas, nuevo servicio para actualizar campos disponibles en items<br>
2022-05-19 : feature | #1526 | agregar serie a guia de remision<br>
2022-05-18 : feature | #1678 | Se agrega componente para busqueda avanzada de items en reporte kardex<br>
2022-05-18 : feature | #1517 | historial de ventas productos<br>
2022-05-18 : feature | #1517 | historial de ventas productos<br>
2022-05-18 : feature | #1517 | historial de ventas productos<br>
2022-05-17 : feature | #1436 | Se agrega busqueda avanzada por coincidencias a listado de productos/servicios/movimientos configurable<br>
2022-05-16 : feature | #1394 | Validacion de propiedad al mostrar p unitario para cambiar moneda en item<br>
2022-05-13 : feature | #1394 | Se agrega cambio de moneda al agregar producto, configurable en nuevo cpe<br>
2022-05-13 : feature | #1554 | modulo hotel - actualizacion de estado pendiente a pagado<br>
2022-05-13 : feature | #1659 | Se agrega funcionalidad para agregar leyenda 2001 al xml, configurable<br>
2022-05-11 : feature | cambio de imagen de fondo de png a svg<br>
2022-05-11 : feature | Se agrega generador de links de pago, otros<br>
2022-05-11 : feature | - | Integracion m pago desde cpe, ajuste en form m pago, eliminar link de pago de pruebas, demo<br>
2022-05-11 : feature | icono de editar menu alineado en tema light - imagen de logo en crear documento<br>
2022-05-11 : feature | #1614 | filtro de categoria en pestaña de producto y servicio<br>
2022-05-10 : feature | data por defecto seteada al crear tenant<br>
2022-05-10 : feature | - | Integrando mercado pago, configuraciones, validaciones<br>
2022-05-10 : feature | #888-2 | reporte efectivo modulo caja chica<br>
2022-05-10 : feature | - | Ajuste configuracion, se agrega email, wp a m pago, t/c en vista publica<br>
2022-05-10 : feature | - | adicionales pagos yape - subida de archivos, etc<br>
2022-05-09 : feature | valores por default en menu header - modo noche en panel derecho - color uniforme en panel derecho - boton descargar css - errores en colores de labels en temas especificos - imagen de fondo login<br>
2022-05-09 : feature | - | Agregando links de pago<br>
2022-05-08 : feature | visual para enlaces de pago en listado de documentos<br>
2022-05-08 : feature | cambios visuales - temas - menu de accesos directos - iconos visuales - menu y titulos en administrador<br>
2022-05-08 : feature | - | Compile js<br>
2022-05-06 : feature | #1607 | mostrar sumatoria de notas de venta<br>
2022-05-06 : feature | #1547 | Se agrega reporte de ventas grifo - contabilidad<br>
2022-05-06 : feature | #1555 | completada funcionalidad de plantilla en a5 y ticket<br>
2022-05-05 : feature | #1540 | Se agrega campo para diferenciar tipo de entorno y eliminar data de prueba - productos fabricados, indumos, embalaje<br>
2022-05-05 : feature | gestionar skins 100%<br>
2022-05-04 : feature | upload css - registro del mismo en bd para su listado en configuracion visual<br>
2022-05-04 : feature | #1540 | Se agrega decimales en insumos - agrega empleados a produccion<br>
2022-05-04 : feature | #1604 | Se agrega op externa al generar guia desde cpe, pedidos - ajuste en columna reporte guia<br>
2022-05-04 : feature | termino de colores en sidebar - notificacion azul para descarga de documentos<br>
2022-05-03 : feature | cambio de css general (no hay modulo para cargar css personalizado)<br>
2022-05-03 : feature | #1604 | Se agrega orden de pedido a reporte consolidado de guias<br>
2022-05-03 : feature | #1548 | Se agrega reporte venta rapida - caja, ventas grifo<br>
2022-05-02 : feature | edicion de menu de acceso de directo<br>
2022-05-02 : feature | #1633 | Se agrega distrito 250307<br>
2022-05-02 : feature | #888-2 | reporte efectivo modulo caja chica<br>
2022-05-02 : feature | #1585 | Se agrega total efectivo cpe, nv reporte caja / ajuste para reporte propina, estado del documento<br>
2022-05-02 : feature | #1607 | mostrar sumatoria de notas de venta<br>
2022-04-30 : feature | grupos de seleccion en modulos y empresa al registrar cliente en admin<br>
2022-04-30 : feature | icono de soporte se configura desde admin<br>
2022-04-30 : feature | change numbers<br>
2022-04-30 : feature | #888-2 | reporte efectivo modulo caja chica<br>
2022-04-29 : feature | #1585 | Se agrega propinas en pos configurable / reportes / total en reporte caja<br>
2022-04-29 : feature | #1601 | Se agrega actualizacion de precios masiva por excel - productos<br>
2022-04-29 : feature | #1604 | Validacion campo op externo<br>
2022-04-28 : feature | #1604 | Se agrega modal para visualizar stock por producto / campo para o pedido externo - guias<br>
2022-04-27 : feature | #1198 | Se agrega validacion para el plazo de envio de comunicaciones de baja - configurable<br>
2022-04-26 : feature | #1401 | Se agrega busqueda por cod barra presentacion - pos/nuevo cpe<br>
2022-04-25 : feature | #1401 | Agregando filtros por cod barra en presentaciones - pos/nuevo cpe<br>
2022-04-25 : feature | #1569 | ajustes formato 1x1 para plantilla a 80mm<br>
2022-04-22 : feature | #1568 | Se agrega filtro para ordenar listado - modifica campo m traslado opcional, reporte movimientos<br>
2022-04-22 : feature | #1483 | reporte de productos campo observacion<br>
2022-04-22 : feature | #1495 | nombre pdf productos<br>
2022-04-22 : feature | #1579-2 | sale note template default brand<br>
2022-04-21 : feature | #1486 | Se agrega cargos a notas de venta<br>
2022-04-21 : feature | #1483 | reporte de productos campo observacion<br>
2022-04-20 : feature | #1483 | reporte de productios campo observacion<br>
2022-04-20 : feature | #1325 | Se agrega configuracion en productos para detraccion - mostrar alerta de producto sujeto a detraccion en nuevo cpe<br>
2022-04-19 : feature | #1569 | nuevos modelos de plantillas para etiquetas por producto<br>
2022-04-19 : feature | #1215 | Se agrega json de deduccion de anticipos exonerados - api<br>
2022-04-19 : feature | #1519 | Se agrega configuracion para asignar moneda global a los items por defecto - p4<br>
2022-04-19 : feature | #1505 | imagen para plantilla<br>
2022-04-19 : feature | #1505 | new template ticket_internal_code<br>
2022-04-18 : feature | #1593 | Se agregan permisos agregar/eliminar para pagos cpe, desde usuarios<br>
2022-04-13 : feature | #1519 | Se agrega configuracion para conversion a soles en reportes: Ventas - Reporte general de productos, Compras - Reporte general de productos, Compras - Compras totales<br>
2022-04-12 : feature | #1519 | p3 - Actualizar precio unitario y aplicar igv global en compra - configurable, p6 - Agrega fecha cpe relacionado en report, p7- agrega nv relacionada en reporte cpe<br>
2022-04-12 : feature | #1554 | modulo hotel - actualizacion de estado pendiente a pagado<br>
2022-04-08 : feature | #1546 | Se agrega total pagado a reporte de caja<br>
2022-04-08 : feature | #1312 | Se agrega limite de dias cpe no enviados - validacion limite de envio por api<br>
2022-04-07 : feature | #1586 | busqueda de clientes por CE<br>
2022-04-07 : feature | #1491-2 | cambios cotiza<br>
2022-04-07 : feature | #1495 | nombre pdf productos<br>
2022-04-07 : feature | #1495 | nombre pdf productos<br>
2022-04-06 : feature | restaurant | roles asignados a usuarios activos en pro4<br>
2022-04-06 : feature | #1483 | reporte productos añadir campo observaciones y vendedor asignado<br>
2022-04-05 : feature | restaurate | ampliando datos de partners<br>
2022-04-05 : feature | #1526 | guia de remision series<br>
2022-04-05 : feature | #1526 | guia de remision series<br>
2022-04-04 : feature | restaurant | habilitar opciones de menu de comanda en configuracion de restaurante<br>
2022-04-04 : feature | #1551 | template para ticket con nombre de empresa y nombre comercial<br>
2022-04-04 : feature | #1563 | filtro placa en busqueda de listado de comprobantes<br>
2022-03-31 : feature | #1513 | pos - venta rapida en el administrador<br>
2022-03-31 : feature | #1509 | modulo compras - gastos diversos añadir estado al reporte<br>
2022-03-31 : feature | #1511 | revision reporte caja chica<br>
2022-03-31 : feature | #1554 | modulo hotel - actualizacion de estado pendiente a pagado<br>
2022-03-30 : feature | #1027 | Agregar exportar excel pedidos - cambios<br>
2022-03-30 : feature | #1510 | buscar y crear cliente por codigo de barras - hotel<br>
2022-03-29 : feature | restaurant| actualizacion de menu, configuracion de cantidad de mesas<br>
2022-03-28 : feature | #1485 | guia de remision - reporte exportable - datos del transportista<br>
2022-03-25 : feature | #1513 | pos - venta rapida en el administrador<br>
2022-03-25 : feature | #1271 | Se agrega validacion para que el p compra no sea superior al de venta (configurable) - nv/cpe<br>
2022-03-25 : feature | #1389 | Se agrega filtro por vendedor para clientes - lista clientes / nuevo cpe<br>
2022-03-25 : feature | #1027 | Agregar exportar excel pedidos - cambios<br>
2022-03-25 : feature | #1510 | buscar y crear cliente por codigo de barras - hotel<br>
2022-03-25 : feature | #1435 | pdf tickets<br>
2022-03-25 : feature | #1496 |inconveniente con el formato ticket para notas de venta<br>
2022-03-24 : feature | #1485 | guia de remision - reporte exportable - datos del transportista<br>
2022-03-24 : feature | #1027 | Agregar exportar excel pedidos - cambios<br>
2022-03-24 : feature | #869 | Se agrega descuento por % en pos<br>
2022-03-24 : feature | #1496 |inconveniente con el formato ticket para notas de venta<br>
2022-03-24 : feature | #1496 |inconveniente con el formato ticket para notas de venta<br>
2022-03-23 : feature | #1496 |inconveniente con el formato ticket para notas de venta<br>
2022-03-23 : feature | #1027 | Agregar exportar excel pedidos - cambios<br>
2022-03-23 : feature | #1496 |inconveniente con el formato ticket para notas de venta<br>
2022-03-23 : feature | #1491 | reporte ventas con direccion de cliente - username<br>
2022-03-21 : feature | #1496 |inconveniente con el formato ticket para notas de venta<br>
2022-03-14 : feature | #1027 | Agregar exportar excel pedidos<br>
2022-03-11 : feature | #1496 |inconveniente con el formato ticket para notas de venta<br>
2022-03-11 : feature | #1027 | Agregar exportar excel pedidos - cambios<br>
2022-03-07 : feature | #1027 | Agregar exportar excel pedidos<br>
2022-03-05 : feature | #1027 | Agregar exportar excel pedidos<br>
2022-03-03 : feature | #1027 | Agregar exportar excel pedidos<br>


## 4.2.0

### docs
2022-03-15 : docs | wiki para instalacion de supervisor<br>
2021-12-20 : docs | update changelog<br>


### fixed
2022-03-21 : fixed | r30 | response data - habilitando servicio api<br>
2022-03-18 : fixed | #1518 | Ajuste para op. gravadas gratuitas - convertir nv a cpe<br>
2022-03-17 : fixed | restaurant | validacion<br>
2022-03-17 : fixed | restaurante | object to array<br>
2022-03-16 : fixed | #1471 | Mostrar guia - ajustes retencion, formato default3 new unit value<br>
2022-03-16 : fixed | #1468 | configuracion no se enviaba desde pedidos a notas de venta - no mostraba condiciones para tickets<br>
2022-03-15 : fixed | #1499 | visualizacion responsive<br>
2022-03-15 : fixed | #1499 | arreglos de url en botones, visualizacion condicionada de botones segun pantalla y segun configuracion avanzada en PDF<br>
2022-03-13 : fixed | #1499 | alternativas de botones de impresion en pos<br>
2022-03-10 : fixed | #1461 | Ajustes para validar fecha emision - se agrega configuracion de dias<br>
2022-03-08 : fixed | #1466 | Ajuste para acumulacion de pagos - pos<br>
2022-03-01 : fixed | #1469 | Ajuste para montos en nc anulada - reporte documentos<br>
2022-03-01 : fixed | #1477 | menu sidebar pos activo cuando corresponde<br>
2022-02-25 : fixed | #1460 | Se modifica condicion para tag LineExtensionAmount nota credito tipo 13<br>
2022-02-18 : fixed | #1454 | Ajustes icbper op gratuitas - pos<br>
2022-02-17 : fixed | #1454 | Ajuste icbper op. gravadas gratuitas - form nuevo cpe<br>
2022-02-15 : fixed | #1454 | Ajustes para icbper en POS<br>
2022-02-14 : fixed | #1382 | Ajustes para ICBPER formulario nuevo cpe<br>
2022-02-14 : fixed | #1442 | dependencia de modulo restaurante con farmacia eliminada<br>
2022-02-14 : fixed | #1427 | observacion de compras en A4<br>
2022-02-09 : fixed | ticket 1 mozo| slug categorias restaurate<br>
2022-02-09 : fixed | #1306 | se agrega promociones pack a tienda virtual<br>
2022-02-08 : fixed | #1357 | validacion de fechas en pagos a creditos - error fecha de pago o cuotas no puede ser anterior a fecha de emision<br>
2022-02-05 : git commit -m " fixed | #1344 | se valida placa en factura"<br>
2022-01-28 : fixed | #1378 | Se agrega campos adicionales para guia exportacion<br>
2022-01-25 : fixed | css para nueva vista de restaurante<br>
2022-01-20 : fixed | #1331 | Ajuste para evitar duplicidad (campo unique)<br>
2022-01-17 : fixed | #1338 | pos - buscador de productos queda debajo del encabezado<br>
2022-01-17 : fixed | #1335 | Ajuste para error al seleccionar multiples direcciones<br>
2022-01-17 : fixed | #1260 | Regulariza anticipo cuando se genera nc tipo 01<br>
2022-01-14 : fixed | #1345 | Se agrega codigo interno a n venta - default3<br>
2022-01-14 : fixed | #1311 | Ajuste al editar cpe - detraccion<br>
2022-01-14 : fixed | #1335 | Ajuste para error al guardar multiples direcciones del cliente | editar cpe (75%)<br>
2022-01-14 : fixed | #1295 | Ajuste al redondear total exonerado - invoice<br>
2022-01-14 : fixed | #1259 | Ajuste al mostrar n venta en kardex (generar cpe multiples nv), filtro por item<br>
2022-01-13 : fixed | #1259 | Ajustes inventario, doble descuento al generar cpe desde multiples n venta<br>
2022-01-13 : fixed | #1330 | Ajustes decimales json cargo por item<br>
2022-01-12 : fixed | #1322 | Se agrega condicion de pago al generar cpe desde guia - ajustes en flujo<br>
2022-01-12 : fixed | #1330 | Se agrega json ejemplo - factura con cargo por item<br>
2022-01-12 : fixed | #1330 | Ajustes template default - cargos por item api<br>
2022-01-10 : fixed | #1311 | Ajuste de error al editar cpe generado desde nv - tipo op. detracciones<br>
2022-01-07 : fixed | #1241 | Se agrega monto pendiente de pago en template default<br>
2022-01-07 : fixed | - | Ajuste comentario<br>
2022-01-07 : fixed | #1069 | Ajuste limitacion de documentos en base a los planes<br>
2022-01-06 : fixed | #1069 | Ajustes limitacion documentos (avance)<br>
2021-12-30 : fixed | diseño header<br>
2021-12-22 : fixed | #1310 | error de numero de referencia<br>
2021-12-22 : fixed | #1310 | error de numero de referencia<br>


### feature
2022-03-22 : feature | #1422  | reporte excel cuenta con cargos y total<br>
2022-03-22 : feature | #1422 | cargos en excel reportes/ventas/documentos<br>
2022-03-21 : feature | #1355 | job reporte movimiebtos ingresos egresos<br>
2022-03-21 : feature | restaurant | config visual<br>
2022-03-18 : feature | #1491 | reporte ventas con direccion de cliente - username<br>
2022-03-18 : feature | #1457 | balance por moneda<br>
2022-03-17 : feature | restaurant | registro de notas de venta relacionadas con caja<br>
2022-03-16 : feature | #1531 | Se agrega funcion para añadir retencion al generar cpe desde cotizaciones<br>
2022-03-16 : feature | #1474 | notificaciones agrupadas<br>
2022-03-15 : feature | orden de cajas en restaurante<br>
2022-03-15 : feature | orden de cajas en restaurante<br>
2022-03-15 : feature | #1455 | Pruebas dscto - se agrega opcion para actualizar dscto desde admin<br>
2022-03-14 : feature | #1455 | Agregando descuento global configurable - ventas/nuevo cpe<br>
2022-03-13 : feature | restaurate | estructura de menu<br>
2022-03-11 : feature | mozo#26 | partners<br>
2022-03-11 : feature | #1491 | reporte ventas con direccion de cliente - username<br>
2022-03-09 : feature | mozo#24 | caja vista restaurant<br>
2022-03-08 : feature | #1485 | guia de remision - reporte exportable<br>
2022-03-08 : feature | #1492 | Se agrega guias para flujo pse<br>
2022-03-08 : feature | mozo#22 | endpoint cash document<br>
2022-03-08 : feature | mozo#22 | endpoint cash restaurant<br>
2022-03-07 : feature | #1491 | reporte ventas con direccion de cliente<br>
2022-03-06 : feature | mozo | data customers api<br>
2022-03-04 : feature | #1458 | mantener numeracion de categorias - hotel<br>
2022-03-04 : feature | #1455 | Se agrega descuento global que afecta la bi en pos<br>
2022-03-04 : feature | #1372 | se agrega cotizaciona documentos pagos caja pos<br>
2022-03-03 : feature | #1455 | Agregando configuracion tipo dscto pos<br>
2022-03-03 : feature | #1370 | salidas form<br>
2022-03-03 : feature | #1437 | paginate reporte inventory, jobs create report pdf excel<br>
2022-03-02 : feature | #1437 | paginate reporte job<br>
2022-03-02 : feature | #1437 | paginate reporte vista<br>
2022-03-02 : feature | #1102 | mejora de letra y seleccion en modulo POS<br>
2022-03-02 : feature | #1437 | jjobs pruebas<br>
2022-03-02 : feature | #1437 | jobs chunks eloquent<br>
2022-03-02 : feature | #1437 | jobs chunks eloquent<br>
2022-03-01 : feature | #1437 | jobs cursor eloquent<br>
2022-03-01 : feature | #1437 | jobs<br>
2022-03-01 : feature | #1437 | jobs debug pruebas<br>
2022-03-01 : feature | #1437 | jobs debug pruebas<br>
2022-03-01 : feature | #1477 | cambios visuales en nueva venta rapida<br>
2022-02-28 : feature | #1471 | Se agrega nueo formato pdf default3_new_unit_value - ajustes<br>
2022-02-28 : feature | #1437 | jobs queue prueba memory<br>
2022-02-28 : feature | #1474 | rediseño de notificaciones en header<br>
2022-02-27 : feature  | #1370 | movimientos inventario, filtros y campos adcionales<br>
2022-02-26 : feature  | #1437 | jobs asincronos para procesos de reportes<br>
2022-02-24 : feature | #1392 | Se agrega isc a compras - reporte general de productos/compras<br>
2022-02-23 : feature | #1102 | mejora de letra y seleccion en modulo POS<br>
2022-02-22 : feature | #1419 | Se agrega flujo de anulacion de facturas pse gior<br>
2022-02-21 : feature | #1445 | Se agregan datos al reporte general de products compras/ventas<br>
2022-02-20 : feature  | #129 | avance en pedf logo por sucursal<br>
2022-02-19 : feature | #1282 | eliminacion de nombre comercial en ticket_b<br>
2022-02-17 : feature | #1422 | muestra de cargos en resumen - modal ampliado y tabla con arreglos en responsive - seleccion de campos para mostrar<br>
2022-02-16 : feature  | #129 | templates pdf render logo sede<br>
2022-02-12 : feature  | #1412 | reporte documentos pdf simple<br>
2022-02-11 : feature | #1329 | mostrar precio ultima venta<br>
2022-02-11 : feature | #1446 | informacion de retencion en plantilla font_swz<br>
2022-02-11 : feature | #1434 | filtros fecha expense table<br>
2022-02-10 : feature | restaurante | se trabajo en vista carrito e item detalle, funcionalidad compra<br>
2022-02-10 : feature  | #1422 | avance - mostrando cargos en listado de comprobantes y reporte de ventas<br>
2022-02-10 : feature | restaurant | promociones<br>
2022-02-09 : feature | #1306 | se agrega promociones pack en restaurante<br>
2022-02-09 : feature | #1282 | aumento de tamaño de fuente en ticket_b<br>
2022-02-09 : feature | #1421 | nuevo icono wsapp para el icommerce, se agrega si es configurado el numero previamente<br>
2022-02-08 : feature | se agrega vista pedidos restaurante<br>
2022-02-08 : feature | #1226 | api cotizaciones permite listar y registrar<br>
2022-02-07 : feature | #1358 | pedidos, lista precios color seleccionable<br>
2022-02-07 : feature | issue mozo #1 | se agrega avance views y data viewcomposer restaurant<br>
2022-02-05 : feature | #1301 | se agrega manejo de lotes en pedido venta<br>
2022-02-02 : feature | #1368 | switch de retencion al generar un documento a partir de una guia de remision<br>
2022-01-30 : feature | api restaurant partners<br>
2022-01-29 : feature | #1342 | default3_new implementa retencion,detraccion,condicion de pago,guia de remision,texto 18%,altura de items,descripcion de unidades en vez de codigo<br>
2022-01-28 : feature: collecion items restaurant<br>
2022-01-28 : feature | restaurante | unificada vista de index con filtro de categoria - estilo a categoria activa<br>
2022-01-27 : feature | restaurante - categorias listada y filtrada en nueva vista<br>
2022-01-27 : feature : se agrega api restaurant<br>
2022-01-25 : feature | añadiendo vista para habilitar productos a restaurante, vista publica para ver productos de restaurantes<br>
2022-01-25 : feature | avance vistas restaurant<br>
2022-01-20 : feature | #1332 | plantilla custom_gasolucion - factura,boleta,nota,guia y cotizacion con marca de agua y pie de pagina<br>
2022-01-18 : feature | #1309 | permitir mostrar terminos y condiciones desde pos mediante configuracion avanzada<br>
2022-01-13 : feature | #1304 | Se agrega configuracion para redondear monto de detraccion a entero<br>
2022-01-12 : feature | - | pruebas pse<br>
2022-01-11 : feature | - | Se agrega funcionalidad para enviar xml a servicio externo y agregar firma digital - envio de cdr (PSE) - Configuracion en empresa<br>
2022-01-10 : feature | #1315 | Se agrega atributo placa al listado de documentos (global/items)<br>
2021-12-30 : feature | cambios visuales en configuracion avanzada<br>
2021-12-23 : feature | modulo apiperudev | cambios y nueva validacion en modulo<br>
2021-12-23 : feature | #417 | cambio de posicion en menu para pos y venta rapida<br>
2021-12-20 : feature | permitir migraciones en menu de auto-update<br>
2021-10-30 : feature | #417 | vista funcional de pagos y acoplada a lado derecho del pos - falta llevar montos a cero, mostrar validaciones y mejoras visuales minimas<br>
2021-08-26 : feature | #417 | optimizacion de vista rapida de pos (falta funcionalidad)<br>
2021-08-25 : feature | #417 | avances visuales en unificacion de vistas de pago con lista en pos<br>
2021-06-24 : feature | #417 | en proceso... nueva vista para venta rápida, arreglos en sidebar (no compilado)<br>
2021-06-07 : feature | #417 | submenu venta rapida<br>


## 4.1.8

### docs
2021-11-23 : docs | update changelog<br>


### fixed
2021-12-10 : fixed | #1100 | Se agrega configuracion para seleccionar tipo de afectacion al rentar habitacion<br>
2021-12-10 : fixed | #1263 | no puede generar orden de pedido<br>
2021-12-10 : fixed | #1119 | Ajustes visuales campo subtotal - descuento que no afecta a la bi<br>
2021-12-09 : fixed | #1278 | CcK | no carga el medoto de pago al editar<br>
2021-12-02 : fixed | #1203 | margen reducido, fuente cambiada en ticket de reporte de caja<br>
2021-11-29 : fixed | #1200 | Ajuste cantidad allowed_items<br>
2021-11-29 : fixed | #1200 | Agregar cuotas formato pdf default3_new_account<br>
2021-11-25 : fixed | #612 | Ajustes para calculos totales a nivel global / se agregan variables para controlar valores sin redondeo - form invoice<br>
2021-11-24 : fixed | #196 | Ajuste para recalcular montos a 0 cuando se usa tipo nc 03<br>
2021-11-24 : fixed | #1136 | Ajuste texto periodo - validacion error nv recurrente al generar cpe<br>
2021-11-23 : fixed | #1212 | Mostrar op. gravada para nota credito tipo 13<br>


### feature
2021-12-19 : feature | app-42 | metodo de pago en nota de venta<br>
2021-12-14 : feature | #1262 | nueva plantilla para mostrar serie y marca en guias de remision - la serie se muestra si hay relacion con una factura o boleta<br>
2021-12-13 : feature | #1234 | Ajuste envio resumen estado 2 - pruebas<br>
2021-12-13 : feature | #1274 | ampliacion de vista de ventas por producto a vista independiente<br>
2021-12-12 : feature | #1234 | Agregando resumenes con estado 2 (avance2)<br>
2021-12-12 : feature | #1234 | Agregando resumenes con estado 2 (avance)<br>
2021-12-10 : feature | #1100 | Se agrega funcionalidad para generar cpe con op. exoneradas en modulo hotel<br>
2021-12-10 : feature | #1254 | ajuste template blank<br>
2021-12-10 : feature | #1279 | plantilla items_desc - agregado metodos de pago y cuotas<br>
2021-12-05 : feature | buho | accion api para reseller<br>
2021-12-03 : feature | #929 | plantilla para guia en template default3_929<br>
2021-11-29 : feature | #1203 | habilitando boton para descarga de reporte de caja chica en formato de 58mm<br>
2021-11-29 : feature | #1203 | habilitando url para permitir formatos de ticket mas reducidos o amplios en reporte de caja chica<br>
2021-11-29 : feature | #1207 | ampliacion de logo en pdf para ticket<br>
2021-11-29 : feature | #730 | enlace para descargar qz e implementar impresion automatica en pos<br>
2021-11-26 : feature | #1192 | plantilla distpach_farmacy con lote y vencimiento en el detalle - no existe qr para guias de remision<br>
2021-11-26 : feature | #1225 | Se agrega json ejemplo factura exonerada/inafecta - descuento global<br>
2021-11-25 : feature | autoprint | configuracion independiente por empresa para activar o desactivar la impresion automatica en POS<br>
2021-11-25 : feature | 929 | qr agregado a la plantilla pdf default3_929<br>
2021-11-24 : feature | autoprint | integracion de librerias y variables para impresion automatica desde el modulo de pos<br>


## 4.1.7

### docs
2021-10-27 : docs | update changelog<br>


### fixed
2021-11-16 : fixed | #1076 | Ajuste afectacion inafecto - gratuito - generar cpe desde pedido<br>
2021-11-15 : fixed | #1092 | Ajustes campos isc/icbper/estado reporte ventas formato sunat 14.1<br>
2021-11-15 : fixed | #1147 | Monto igv incorrecto reporte general de productos<br>
2021-11-11 : fixed | #1159 | arreglo en header para pantallas de tablet<br>
2021-11-10 : fixed | - | Ajuste para decimales en factor - descuento pos<br>
2021-11-10 : fixed | #1005 | Ajustes descuento global form nuevo cpe<br>
2021-11-05 : fixed | #1029 | Ajustes reporte comisiones vendedor<br>
2021-11-05 : fixed | #1048 | Guias: Agregar igv a los items dependiendo de configuracion del producto al generar cpe<br>
2021-11-04 : fixed | #1099 | Formato contabilidad compras - ajuste decimales tipo de cambio<br>
2021-11-03 : fixed | #1105 | Asignar tipo de operacion para filtrar afectaciones form item - notas<br>
2021-11-03 : fixed | #1110 | Se regulariza codigos de errores/observaciones - core facturalo<br>
2021-11-03 : fixed | #1103 | Ajuste decimales al agregar item - guia remision<br>
2021-11-02 : fixed | #1122 | apiperu consulta ubigeo<br>
2021-11-02 : fixed | #1115 | altura de plantilla custom_multisaba<br>
2021-10-29 : fixed | Ajuste ejemplo json descuento lineal afecta a la base<br>
2021-10-29 : fixed | #1107 | Validar tarifa en modulo hoteles<br>
2021-10-28 : fixed | #1078 | Ajustes al generar n credito desde cpe con descuentos<br>
2021-10-28 : fixed | #1088 | posicion de url de ose en configuracion de empresa<br>
2021-10-27 : fixed | #1072  | Habilitar ventas con fechas atrasadas para boletas<br>
2021-10-27 : fixed | #1057 | Nota de venta: Ajustes descuentos por item al generar cpe<br>


### feature
2021-11-23 : feature | #1033 | plantilla custom_multisaba, cambios en cotizacion y guias<br>
2021-11-23 : feature | #1157 | logo login aumentado y centrado<br>
2021-11-16 : feature | #1041 | Se agrega reporte movimientos<br>
2021-11-12 : feature | #999 | Habilitar recrear documento configurable por usuario<br>
2021-11-11 : feature | #999 | Agregando configuracion recrear documento en form usuarios<br>
2021-11-11 : feature | #985 | Se agrega condiciones de pago en compras<br>
2021-11-09 : feature | #951 | Se agrega configuraciones para nv por defacto y busqueda codigo de barras<br>
2021-11-09 : feature | #1115 | disminuyendo altura de comprobantes custom_multisaba<br>
2021-11-08 : feature | #204 | Se agrega total isc reporte documentos - ajuste de columnas desordenadas<br>
2021-11-03 : feature | #1105 | Se agrega json ejemplo - nota credito - factura exportacion<br>
2021-11-03 : feature | #204 | Se agrega ejemplos isc - api<br>
2021-11-02 : feature | #204 | Se agrega isc a pos/nuevo cpe<br>
2021-11-02 : feature | #1073 | no repetir placa en plantilla header_image_full..<br>
2021-11-01 : feature | #204 | Agregando calculos isc a formulario pos<br>
2021-11-01 : feature | #204 | Agregando parametros configuracion isc a items - casuisticas api<br>
2021-10-29 : feature | #982 | Se agrega nombre producto pdf en xml factura/boleta - configurable<br>
2021-10-29 : feature | #1111 | deshabilitando servidor alterno de sunat<br>
2021-10-28 : feature | #982 | Agregando nombre producto xml - nuevo cpe<br>
2021-10-28 : feature | #1087 | metodo de pago en reporte de items general<br>
2021-10-27 : feature | #1073 | añadiendo metodos y condicion de pago segun plantilla default a plantilla header_image_full_width<br>


## 4.1.6

### docs
2021-10-18 : fixed | #981 | S. Tecnico - agregar pagos al generar docs - validar documento asociado<br>
2021-09-24 : docs | correciones en manual de migracion de server<br>
2021-09-23 : docs | wiki | migracion de servidor con docker<br>
2021-09-21 : docs | update changelog<br>


### fixed
2021-10-26 : fixed | #1082 | Ajustes valor nuull en item_code y barcode - busqueda items api<br>
2021-10-26 : fixed | #1028 | Ajuste para mostrar decimales en form items - nota venta<br>
2021-10-25 : fixed | #1053 | reconfigurando muestra de validadores en listado de comprobantes<br>
2021-10-25 : fixed | #1053 | error en merge sobre consulta ruc en administrador<br>
2021-10-25 : fixed | #1006 | Ajustes error descuentos por item en cotizacion al generar cpe<br>
2021-10-22 : fixed | #1089 | Ajustes moneda xml detraccion transporte<br>
2021-10-22 : fixed | #1039 | Ajustes al vender productos con promociones cpe/nv - ecommerce<br>
2021-10-22 : fixed | #1082 | Ajustes campos categoria, marca, cod barra - busqueda de items api<br>
2021-10-22 : fixed | #972 | Se agregan 2 motivos de traslado guias a reporte kardex valorizado<br>
2021-10-22 : fixed | #972 | Se agregan 2 motivos de traslado guias a reporte kardex valorizado<br>
2021-10-21 : fixed | #1084 | Se regulariza pagos, retenciones, m pendiente en plantilla blank<br>
2021-10-21 : fixed | delete trash file<br>
2021-10-20 : fixed | #1049 | Ajustes opcion generar cpe desde nota venta<br>
2021-10-20 : fixed | #956 | Se agrega detraccion transporte por api<br>
2021-10-20 : fixed | #1033 | plantilla pdf, arreglos de valor sin igv unitario y margen superior de logo<br>
2021-10-19 : fixed | #956 | Ajustes api - cpe con detraccion transporte<br>
2021-10-19 : fixed | #996 | Ajustes nuevo cpe - calculo de montos/pagos con detraccion<br>
2021-10-18 : fixed | - | Consulta de documentos: Devuelve el nombre del vendedor correctamente.<br>
2021-10-18 : fixed | #1086 | Reporte de comisiones: ajuste para filtrar correctamente el rango de fechas. Los status de documento deben estar en `state_type_id` IN ( '01', '03', '05', '07', '13' )<br>
2021-10-18 : fixed | #816 | Reporte Consolidado de ventas por vendedor: ajuste para filtrar correctamente el vendedor<br>
2021-10-18 : fixed | #816 | Reporte Consolidado de ventas por vendedor: ajuste para filtrar correctamente el vendedor<br>
2021-10-18 : fixed | #574 | Envio Email de Comprobantes: Cuando son varios destinatarios, se envia un solo correo para no replicar los anexos<br>
2021-10-18 : fixed | #1021 | Template: Citec/Datetime/Internal_code: Ajuste para mostrar condicion de pago y formas de pago<br>
2021-10-18 : fixed | #981 | S. Tecnico - agregar pagos al generar docs - validar documento asociado<br>
2021-10-18 : fixed | #824 | Ajuste para mostrar condicion de pago y detalles de pagos en CITEC<br>
2021-10-18 : fixed | - | Template Citec/Wsc: Ajuste para obtener la plantila del establecimiento correctamente<br>
2021-10-18 : fixed | #1046 | Migracion: Ajuste de standar<br>
2021-10-18 : fixed | #1021 | Template InternalCode: Ajuste para no mostrar hora en fecha de vencimiento<br>
2021-10-18 : fixed | #1046 | Contratos: Ajuste para buscar item correctamente<br>
2021-10-14 : fixed | resources | slepp al guardar para evitar duplicidad de registro<br>
2021-10-14 : fixed | resources | validacion por minuto sobre registro de recursos en comando<br>
2021-10-14 : fixed | resources | en servidor se repite el comando y guarda multiples registros en un solo minuto<br>
2021-10-14 : fixed | resources | error en asignar frecuencia de comando<br>
2021-10-14 : fixed | tiempo de espera para consulta de tasa de cambio<br>
2021-10-14 : fixed | tiempo de espera para consulta de tasa de cambio<br>
2021-10-12 : fixed | #927 | Ajustes reporte kardex valorizado - montos promedios - n credito<br>
2021-10-11 : fixed | #1007 | Ajustes para generar nota de venta desde hoteles<br>
2021-10-11 : fixed | #1036 | Se agrega campo reference date en reporte nv<br>
2021-10-07 : fixed | #1007 | Ajustes iniciales nv - hoteles<br>
2021-10-07 : fixed | #1015 | Ajuste al buscar productos inhabilitados<br>
2021-10-07 : fixed | #977 | Ajustes errores al pago credito - metodo de pago a X dias<br>
2021-10-06 : fixed | #1010 | Ajustes pedidos op gratuitas - convertir cpe<br>
2021-10-06 : fixed | #1010 | Regularizacion igv free al generar cpe desde cotizacion<br>
2021-10-05 : fixed | #1010 | Ajustando calculos op gratuita registrar/editar cotizacion<br>
2021-10-05 : fixed | #931 | Ajuste campo factor - descuento anticipos<br>
2021-10-05 : fixed | #973 | Campos opcionales en guia - validaciones<br>
2021-10-04 : fixed | #973 | Ajustes seleccion lista precios - validacion tgs xml obligatorios guia<br>
2021-10-04 : fixed | #1008 | Ajuste al agregar pago sin datos requeridos - edicion cpe<br>
2021-10-01 : fixed | #1008 | Edicion cpe: Ajustes para editar cpe con detracciones<br>
2021-10-01 : fixed | #954 | Superposicion de textos en plantilla de cotizaciones - tyc<br>
2021-09-30 : fixed | #949 | Descarga masiva: Se mantiene la plantilla por defecto en configuracion para ticket<br>
2021-09-30 : fixed | #949 | Descarga masiva: Ajuste para colocar el template, para el del establecimiento del usuario.<br>
2021-09-28 : fixed | - | Config: show_pdf_name por default false -  COMPILE JS<br>
2021-09-28 : fixed | - | Config: show_pdf_name por default false<br>
2021-09-28 : fixed | #988 | Nota de venta: Stock muestra el mismo que CPE<br>
2021-09-28 : fixed | #965 | Deshabilitar cambio de tipo de documento al editar cpe<br>
2021-09-28 : fixed | #885 | Compile JS<br>
2021-09-28 : fixed | #885 | Servicio tecnico: Convertir a factura requiere descuentos por linea<br>
2021-09-27 : fixed | #885 | Servicio tecnico: Convertir a factura requiere descuentos por linea<br>
2021-09-27 : fixed | #885 | Servicio tecnico: Convertir a factura requiere descuentos por linea<br>
2021-09-27 : fixed | #950 | Template SWZ: Variable $payments definida<br>
2021-09-27 : fixed | #995 | Busqueda de items: Filtro por alamcen de usuario<br>
2021-09-27 : fixed | - | Template Boleta/Factura  A4 CITEC : Devolviendo CITEC invoice.blade.php<br>
2021-09-27 : fixed | - | Template Boleta/Factura  A4 CITEC : Validacion de existencia de archivo<br>
2021-09-27 : fixed | - | Migraciones: Se deber validar compatilibidad con Mysql 5.7, Windows debe ser base de prueba<br>
2021-09-27 : fixed | #945 | Ajustes validador por cron<br>
2021-09-27 : fixed | #945 | Ajustes validador por cron<br>
2021-09-24 : fixed | #975 | Configuracion: Moneda predeterminada guarda correctamente<br>
2021-09-24 : fixed | #994 | Items: No limitar productos cuando se realiza una busqueda (pueden salir 5000)<br>
2021-09-24 : fixed | #945 | Validador: Se agrega consulta integrada al validador por cron<br>
2021-09-24 : fixed | - | ajuste para indice indefinido<br>
2021-09-24 : fixed | - | Nota de venta: Envio a servidor: Previniendo error de objeto<br>
2021-09-21 : fixed | #966 | Ajustes op. gravada retiro por premio - gratuita<br>


### feature
2021-10-27 : feature | #1060 | tasa de cambio - consulta a sunat y alternativa a consulta apiperu - importante: modules.Services.ServiceController<br>
2021-10-26 : feature | #930 | Ajustes nota credito tipo 13, pruebas<br>
2021-10-25 : feature | #930 | Agregando nota credito tipo 13<br>
2021-10-25 : feature | #1083 | Se agrega descuento al reporte excel - ventas/documentos<br>
2021-10-21 : feature | #1042 | Se agrega json de ejemplo - factura retencion api<br>
2021-10-21 : feature | #1042 | Se agrega retencion en nuevo cpe<br>
2021-10-20 : feature | #1042 | Se agrega cpe con retencion por api<br>
2021-10-20 : feature | #956 | Se agrega casuisticas detraccion api - postam collection<br>
2021-10-19 : feature | #1033 | plantilla personalizada custom_multisaba para cliente<br>
2021-10-19 : feature | #934 | Se agrega descripcion lista precios pos - agrega lista precios busqueda items api<br>
2021-10-18 : feature | #1053 | avance de consulta ruc con soporte para apiperu.dev y apiperu.net.pe<br>
2021-10-18 : feature | #938 | se agrega json liquidacion de compra - Update README.md<br>
2021-10-18 : feature | #938 | Se agrega liquidacion de compra - api<br>
2021-10-15 : feature | #596 | Movimiento de item: Suspendiendo modelos no observados<br>
2021-10-15 : feature | #596 | Compile JS<br>
2021-10-15 : feature | #596 | Template Factura: Generando Miniatura y adicion de comentarios extra al manual feature | #596 | Items: Atributos extra: Mostrar el stock en la seccion del producto feature | #596 | Items: Atributos extra: Posibilidad de generar un reporte de venta por items. feature | #596 | Inventario por atributos: Se almacenan los datos de cada proceso en una tabla para poder filtrar el resultado, es necesario evaluar los status y nota de credito/debito<br>
2021-10-14 : feature | resources | compile js<br>
2021-10-14 : feature | resources | mostrando graficas en admin/informacion<br>
2021-10-14 : feature | resources | comando para obtener recursos de cpu y memoria con cron que almacena en bd<br>
2021-10-14 : feature | resources | recursos de cpu y memoria ram mostrandose por urls<br>
2021-10-13 : feature | #1046 | Items: Busqueda de items: Configuracion para mostrar solo productos del almacen del usuario.<br>
2021-10-07 : feature | #1024 | Ejemplo json descuento lineal no afecta a la bi - productos exonerados update README.md<br>
2021-10-07 : feature | #596 | Missing migration<br>
2021-10-07 : feature | #596 | Compile JS<br>
2021-10-07 : feature | #596 | Compras: Añadir guias a compra<br>
2021-10-06 : feature | #1026 | avance menu en dropdwon para mostrar botones de accion en listado de documentos<br>
2021-10-06 : feature | #596 | Compras: Añadir guias a compra<br>
2021-10-06 : feature | #1036 | Se agrega caampo reference data en reporte general de prductos<br>
2021-10-06 : feature | #1014 | Agrega campo observacion reporte general de productos<br>
2021-10-05 : feature | #990 | Configuracion: Limitando acceso de los modulos Empresa, Visual y Avanzado.<br>
2021-10-05 : feature | #1026 | avances en sidebar, hover<br>
2021-10-05 : feature | #1026 | avances en sidebar<br>
2021-10-04 : feature | - | Compile JS<br>
2021-10-04 : feature | - | Configuracion: Cambio de nombre a la pagina<br>
2021-10-04 : feature | - | Lista de precios en pedidos<br>
2021-10-01 : feature | #969 #885 | Compile js<br>
2021-10-01 : feature | #969 | Pedidos: Enfoque en el campo descripcion al añadir item. Cantidad por defaul 1<br>
2021-10-01 : feature | #885  Servicio Tecnico: Bajo ciertas circunstancias, additional_information al insertarse se mantiene como array, esto modifica el valor para que sea string<br>
2021-10-01 : feature | - | Servicio Tecnico: Evitando NaN en precio unitario<br>
2021-09-30 : feature | #916 | Asignar permisos de edicion cpe desde configuracion y usuario<br>
2021-09-30 : feature | #991 | Minor: Añadiendo valor de venta para tipo decambio y ajusets de estilo<br>
2021-09-30 : feature | #986 | orden de actualizacion estado<br>
2021-09-30 : feature | #986 | Se agrega nv a ecommerce<br>
2021-09-30 : feature | #885 #969 | Compile JS<br>
2021-09-30 : feature | #969 | Guia de Remision: Mostrar Agregar en lina para pantallas y mantener el agregar para moviles<br>
2021-09-29 : feature | #984 | Se define por defecto la direccion registrada en establecimiento como direccion de partida - guia<br>
2021-09-29 : feature | #986 | Agregando nv a ecommerce - v inicial<br>
2021-09-29 : feature | #969 #974 | Compile JS<br>
2021-09-29 : feature | #969 | Dispatches: Cliente: Añadiendo busqueda por CE<br>
2021-09-29 : feature | #969 | Dispatches: Direccion de destino: Permite colocar la direccion en texto.<br>
2021-09-29 : feature | #969 | Dispatches: Cliente: Busqueda de clientes remoto<br>
2021-09-29 : feature | - | Controller: Metodo de busqueda de cliente generica para todos los controllers<br>
2021-09-29 : feature | #974 | Reporte de inventario: Ajuste para filtrar por fecha de vencimiento<br>
2021-09-29 : feature | #983 | Pedidos. Añadir direccion de cliente automatica<br>
2021-09-28 : feature | #908 | Cotizacion: Ajuste para validar correctamente name_product_pdf<br>
2021-09-28 : feature | #983 | se agrega autocompletado de la direccion del cliente al generar pedido<br>
2021-09-28 : feature | - | Removiendo seller_id de los documentos y nota de venta para funcion seller()<br>
2021-09-28 : feature | #927 | se agregar kardex valorizado, formato sunat 13.1<br>
2021-09-27 : feature | #927 | avance kardex formato sunat<br>
2021-09-27 : feature | #885 | Servicio Tecnico: Unidad de item a ZZ<br>
2021-09-27 : feature | #885 | Servicio Tecnico: Unidad de item a ZZ<br>
2021-09-27 : feature | #908 | Compile JS<br>
2021-09-27 : feature | #908 | Cotizacion: Nombre del pdf en la vista<br>
2021-09-24 : feature | #961 | Compile JS<br>
2021-09-24 : feature | #961 | Reporte de comision: Filtro por vendedor<br>
2021-09-24 : feature | #908 | Compile JS<br>
2021-09-24 : feature | #908 | Factura/Boleta: Mostrar el nombre del PDF en el listado de items<br>
2021-09-24 : feature | #950 | Template font_swz: Añadiendo placa y condicion de pago a factura/boleta<br>
2021-09-23 : feature | #950 | Template font_swz: Añadiendo placa y condicion de pago a factura/boleta<br>
2021-09-23 : feature | - | Compile JS<br>
2021-09-23 : feature | #823 | Items: Homologación de búsqueda de ítems a un controlador<br>
2021-09-23 : feature | #885 | Compile JS.<br>
2021-09-23 : feature | #885 | Servicio técnico: Posibilidad de añadir Productos a Servicio Técnico - See merge request carlomagno83/facturadorpro4!334<br>
2021-09-22 : feature | #392 | ver plantilla muestra detalle de plantilla seleccionada<br>
2021-09-21 : feature | #932 | union de plantillas para facturas y guias, una sola vista para su seleccion<br>
2021-09-21 : feature | #911 | Template default3_911: Ajuste para calculo de producto sin igv. Imagen de fondo mas amplia<br>
2021-09-21 : feature | #911 | Template default3_911: Ajuste para calculo de producto sin igv. Imagen de fondo mas amplia<br>
2021-09-17 : feature | #927 | Avance formato kardex sunat 13.1<br>


## 4.1.5

### docs
2021-09-21 : docs | configuracion nuevo validador<br>
2021-09-15 : docs | wiki funcional - similar a wiki en gitlab del proyecto<br>
2021-09-14 : feature | wiki-docs | añadiendo vistas en base a la wiki que se posee en gitlab - rutas: dominio/docs subdominio/docs<br>
2021-09-14 : docs | add package larecipe<br>
2021-09-06 : docs | changelog update<br>


### fixed
2021-09-17 : fixed | - | Validando almacenes<br>
2021-09-17 : fixed | #953 #960 #962 | busqueda de items<br>
2021-09-17 : fixed | #958 | Ajustes calculos para operaciones gravadas gratuitas<br>
2021-09-17 : fixed | #885 | Balance: Ajuste de textos e icon os<br>
2021-09-17 : fixed | #885 | Balance: Transferencia entre cuentas y caja<br>
2021-09-17 : fixed | #960 | Nota de venta: Pasando request para cuando se realzia una busqueda de item<br>
2021-09-17 : fixed | #885 | Geneerando modal para transferencias entre cuentas bancarias<br>
2021-09-17 : fixed | #953 | Unficando #823 en SearchItemController.<br>
2021-09-16 : fixed | #926 | Ajustes error de calculo, op. exoneradas - gratuitas<br>
2021-09-16 : fixed | #933 | Ajuste al mostrar series seleccionadas - edicion item nuevo cpe<br>
2021-09-15 : fixed | #922 | Ajuste pdf moneda detraccion usd<br>
2021-09-15 : fixed | #931 | Ajustes anticipos op, exonerada e inafecta<br>
2021-09-14 : fixed | #931 | Ajustes anticipos gravados<br>
2021-09-14 : fixed | #823 | Busqueda por Lote: Si se tiene activada la busqueda por lote y al momento de buscar, se encuentra el registro, se devuelve el elemento.<br>
2021-09-14 : fixed | - | Quitando logs<br>
2021-09-14 : fixed | - | Añadiendo log<br>
2021-09-14 : fixed | - | Ajustes de controlador<br>
2021-09-14 : fixed | #823 #900 | Compile JS<br>
2021-09-14 : fixed | #921 | Agrega condicion de pago en plantilla default3<br>
2021-09-13 : fixed | #918 | Ajustes op. gratuita - api<br>
2021-09-13 : fixed | #704 | Pedidos: Añadiendo filtrado en direccion. Ajuste de minimo para 0.1.<br>
2021-09-13 : fixed | #574 | Quitando Logs<br>
2021-09-13 : fixed | #574 #704 #900 | Compile JS<br>
2021-09-13 : fixed | #900 | Items: Codigo de Barras: Exportar Codiugo de barras en formato 5cm x 2.5cm, desde seccion de items.<br>
2021-09-13 : fixed | - | PDF: Estandarizacion de codigo para generacion de un pdf a partir de varios<br>
2021-09-13 : fixed | #900 | Codigo de barras: Generar el PDF para codigod e barras de 5 cm x 2.5 cm.<br>
2021-09-13 : fixed | #903 | Se agrega cuotas - pagos plantilla multiples logos<br>
2021-09-13 : fixed | - | Minor: Ajuste de estilo<br>
2021-09-13 : fixed | #914 | validar propiedad discounts - dscto por item<br>
2021-09-10 : fixed | #914 | Ajustes descuentos no afectan a la bi, global - item<br>
2021-09-10 : fixed | #704 | Dispaches: Ajuste en obtencion de direcciones.<br>
2021-09-10 : fixed | #574 | Correos: Guardando eventos de envio de correo en la tabla correspondiente<br>
2021-09-10 : fixed | #574 | Correos: Centralizando el codigo de envio de correo para evitar duplicidad de codigo.<br>
2021-09-09 : fixed | #914 | ajuste validacion dscto por item no afecta a la bi<br>
2021-09-09 : fixed | #914 | Ajustes descuento global afecta bi<br>
2021-09-09 : fixed | #622 | Testing: Añadiendo logs<br>
2021-09-09 : fixed | #622 | Testing: Añadiendo logs<br>
2021-09-09 : fixed | #622 | Testing: Añadiendo logs<br>
2021-09-09 : fixed | #622 | Testing: Añadiendo logs<br>
2021-09-09 : fixed | #622 | Compile JS<br>
2021-09-09 : fixed | #622 | Migrar NV a otro server: Cambio de curl a GuzzleHttp<br>
2021-09-09 : fixed | #914 | test variable payable<br>
2021-09-08 : fixed | #914 | Modificaciones para descuento global que no afecta a la bi<br>
2021-09-07 : fixed | #889 | Se agrega n! ruc en plantilla header_image_full_width<br>
2021-09-07 : fixed | #813 | Reporte Masivo: Ajuste de bordes derecho e izquierdo a 1<br>
2021-09-07 : fixed | #907 | Ajustes descuentos por item que no afectan a la bi - por % dscto<br>
2021-09-07 : fixed | #704 | Compile JS<br>
2021-09-06 : fixed | #704 | Compile JS<br>
2021-09-06 : fixed | #704 | Guias de Remision: Ajuste para seleccion de serie si existe.<br>


### feature
2021-09-21 : feature | #940 | default_brand_as_lab: Factura/Boleta, Nota, Nota de venta: Ajuste para Marca como Laboratorio (Brand as Lab)<br>
2021-09-21 : feature | - | Añadiendo Ignore a marcas de agua<br>
2021-09-20 : feature |#929 | Ajuste en plantilla 929 Boleta/Factura:<br>
2021-09-20 : feature |#936 | Reporte de Guia: Cambio de ID por Number<br>
2021-09-20 : feature |#936 | Reporte de Guia: Cambio de ID por Number<br>
2021-09-20 : feature | #813 #900 #936 | Compile JS<br>
2021-09-20 : feature | #936 | Guias: Consulta de guías por producto: Se añade filtro con rango de número de comprobante<br>
2021-09-20 : feature | #936 | Minor: Ajuste de formato de codigo<br>
2021-09-20 : feature | #900 | Código de barras: Añadiendo rango de id minimo y maximo por edicion del usuario.<br>
2021-09-20 : feature | #813 | Descarga masiva de comprobantes: Añadiendo filtro por number .<br>
2021-09-20 : feature | #911 | Template: Factura/Boleta A4: Ajustes de cuentas bancarias<br>
2021-09-20 : feature | #929 | Template: Factura/Boleta A4: Ajustes para direccion y % ded esciuento<br>
2021-09-20 : feature | #911 | Template: Factura/Cotizacion A4: Añadiendo imagen de visualziacion<br>
2021-09-20 : feature | #911 | Template: Factura/Cotizacion A4: Imagen de fondo en public\watermark\item_brand.jpg como marca de agua. Valor unitario (precio sin iva) en cotizacion y factura formato A4.<br>
2021-09-17 : feature | #885 | Balance: Transferencia: Al momento de realizar la transferencia, el modal debe desaparecer<br>
2021-09-17 : feature | - | Quitando log<br>
2021-09-17 : feature | - | añadiendo log<br>
2021-09-17 : feature | #885 |  Ajuste para objeto id<br>
2021-09-17 : feature | #885 |  Compile JS<br>
2021-09-17 : feature | #871 | url asignada en admin para apk es listada en menu apps de cliente<br>
2021-09-16 : feature | #871 | url configurada desde admin (menu configuracion) - falta mostrar en tenant<br>
2021-09-16 : feature | #885 | Compile JS<br>
2021-09-16 : feature | #871 | ocultar menu apps desde admin<br>
2021-09-16 : feature | #885 | Compras: Cambio de has_igv a purchase_has_igv: Tambien previene que pueda cambiarse el global igv al momento de ingresar items<br>
2021-09-16 : feature | #924 | Clientes: Importar: Añadiendo tipo de cliente.<br>
2021-09-16 : feature | #929 | Template: Boleta/Factura 929: Ajuste para 25 items<br>
2021-09-16 : feature | #929 | Template: Boleta/Factura 929: Ajuste para 35 items<br>
2021-09-16 : feature | #929 | Template: Boleta/Factura 929<br>
2021-09-16 : feature | #925 | Se agrega costo total, precio venta - reporte inventario<br>
2021-09-15 : feature | #925 | se agrega suma total precio venta y compra - vista reporte inventario<br>
2021-09-15 : feature | #862 | Cotizaciones: modificacion longitud campo obs, pdf, ajustes en vista<br>
2021-09-15 : feature | #929 | Minor: Ajuste de estilo y responsivo<br>
2021-09-15 : feature | #885 | Compile JS<br>
2021-09-15 : feature | #885 | Compras / Configuracion: Mejorando textos<br>
2021-09-15 : feature | #885 | Compras: Ajuste para establecer IGV de compras globalmente para los items.<br>
2021-09-15 : feature | wiki-doc | vista por defecto incial<br>
2021-09-14 : feature | wiki-docs | añadiendo vistas en base a la wiki que se posee en gitlab - rutas: dominio/docs subdominio/docs<br>
2021-09-13 : feature | #574  | Compile JS<br>
2021-09-08 : feature | #574 | Compile JS<br>
2021-09-08 : feature | #574 | Clientes/Person: Añadiendo palabra opcionales<br>
2021-09-08 : feature | #574 | Clientes/Person: Envio correcto para multiples direcciones separadas con , a demas, de guardar correctamente el dato<br>
2021-09-08 : feature | #574 | Mails/Emails: Añadiendo la posiblidad de enviar a multiples direcciones cuando estas, se separan con coma (,)<br>
2021-09-08 : feature | #574 | Clientes/Person: Añadiendo optional_email para editarlo y optional_email_send que deberia ser usado para enviar correos<br>
2021-09-08 : feature | #574 | Clientes/Person: Ajuste de vue para añadir correos opcionales.<br>
2021-09-08 : feature | - | Minor: Ajuste de estilo de codigo<br>
2021-09-08 : feature | #705 | Compile JS<br>
2021-09-08 : feature | #813 | Reporte Masivo: Formato 80 MM correcto al imprimir<br>
2021-09-08 : feature | #705 | Compras: Cambio de nomemclatura de "Fecha de Ven Gen" a "Fec. Vencimiento"<br>
2021-09-07 : feature | #898 | Admin: se agrega notificacion cpe por anular<br>
2021-09-07 : feature | #705 | Compras: Ajuste para establecer date_of_due en items de forma general, solo para aquellos que no tengan lotes.<br>
2021-09-07 : feature | #744 | Compile JS<br>
2021-09-07 : feature | #744 | Factura / Cotizacion / Nota de venta: Unificando estructura basado en resources/js/views/tenant/documents/partials/item.vue<br>
2021-09-07 : feature | #744 | Factura: Item: ajustes graficos de botones, Se puede mejorar evitando duplicar codigo para movil. Atencion en @todo<br>
2021-09-07 : feature | #744 | Factura: Se duplica codigo para el resumen de pagos y poder sacarlo de la tabla. Asi ordenarse correctamente en dispositvivos moviles. Se puede plantear hacer modificaciones como elemento para evitar la duplicidad de codigo. Atencion en @todo<br>


## 4.1.4

### docs
2021-08-27 : docs | Update README.md - postman collection - api (icbper)<br>
2021-08-27 : feature | #877 | Validador: Se modifica funcion para regularizar docs con nueva consulta integrada - ajustes respuesta<br>
2021-08-13 : feature | #715 | Admin: Se agrega notificiaciones (docs pendientes de envio y rectificacion) - cambio texto a url<br>


### fixed
2021-09-06 : fixed | #907 | CPE: Ajuste para descuentos por item que no afecta a la bi - dscto por monto<br>
2021-09-06 : fixed | #894 | Pos: Ajuste busqueda productos inhabilitados<br>
2021-09-06 : fixed | #903 | Se agrega condicion de pago pdf multiple logo<br>
2021-09-03 : fixed | #837 | Pedidos: No modificar vendedor al actualizar registro<br>
2021-09-03 : fixed | #860 | Api: Actualizar descripcion de productos registrados<br>
2021-09-03 : fixed | #185 | Ajuste de teclas lista precios<br>
2021-09-01 : fixed | - | Compile JS<br>
2021-09-01 : fixed | ajustes cargos - descuentos globales - api<br>
2021-08-31 : fixed | - | fixed config url<br>
2021-08-27 : fixed | #873 | CPE: Ajuste retorno de inventario al anular cpe que salio de diferente almacen principal<br>
2021-08-27 : fixed | #886 | Ajuste afectacion gravado retiro por donacion<br>
2021-08-27 : fixed  | - | remove testing PurchaseController.php<br>
2021-08-27 : fixed  | - | testing PurchaseController.php<br>
2021-08-27 : fixed  | #778 | Compile JS<br>
2021-08-27 : fixed | #882 | Core: Se agrega monto icbper al crear items por api<br>
2021-08-27 : fixed  | #778 | POS: Ajuste para mostrar PAGAR y ocultar los pagos rapido en efectivo cuando el pago es por condicion de credito<br>
2021-08-27 : fixed  | #778 | POS: Ajuste para separar condiciones de pago en POS<br>
2021-08-27 : fixed  | #881 | Compras: Ajuste para identificar el row->id<br>
2021-08-26 : fixed | #861 | Cotizacion: Al editar una cotizacion donde el cliente no existe. este lo busca y lo añade<br>
2021-08-26 : fixed | #883 | Item: Ajuste para formatear item units type correctamente<br>
2021-08-26 : fixed | #883 | Minor: Formato de codigo<br>
2021-08-26 : fixed | #841 | Compile JS<br>
2021-08-26 : fixed | #841 | Compras: Ajuste para mostrar los descuentos correctamente<br>
2021-08-26 : fixed | Cotizacion: Se elimina funcion para modificar fecha cuando se modifica termino pago<br>
2021-08-26 : fixed | #881 | Compras: Validando indices en el guardado<br>
2021-08-26 : fixed | #881 | Compras: OfflineTrait no se encontraba regstrado correctamente<br>
2021-08-26 : fixed | - | Removiendo config.include_igv<br>
2021-08-25 : fixed | #502 #872 | Compile JS<br>
2021-08-25 : fixed | #872 | Cotizacion: Ajuste para mostrar la lista de precios correctamente<br>
2021-08-24 : fixed | #878 | Notificaciones: Ajustes validacion de fecha por tipo documento - modificacion texto notificacion<br>
2021-08-24 : fixed | #822 | formato a5 al regenerar pdf de factura en la creacion de una guia<br>
2021-08-24 : fixed | #827 | url  configuracion de pago en nuevo menu<br>
2021-08-24 : fixed | #773 | Reporte de ventas: Ajuste para cantidad<br>
2021-08-23 : fixed | #596 | Inventario: Datos extra: Ajuste para mostrar correctamente los permisos del modulo<br>
2021-08-23 : fixed | #863 | Se habilita edicion de fecha de emision al generar cpe desde pedido<br>
2021-08-23 : fixed | #826 | Pedidos: Ajustes calculos afectacion gravado - bonificaciones<br>
2021-08-23 : fixed | #872 | Compile JS<br>
2021-08-23 : fixed | #872 | Compile JS<br>
2021-08-23 : fixed | #872 | Compile JS<br>
2021-08-23 : fixed | #872 | Compile js<br>
2021-08-23 : fixed | #872 | Cotizacion: Ajuste para obtener is_client<br>
2021-08-20 : fixed | #717 | Pos: Ajuste error n. venta generada desde el pos, no agrega placa al generar cpe<br>
2021-08-20 : fixed | #858 | Ajuste al editar cpe - descuadre de stock - ajustes reporte kardex<br>
2021-08-20 : fixed | #842 | informacion se desborda en pdf<br>
2021-08-20 : fixed | #551 | Ajustes visualizacion de stock al generar cpe desde cotizacion - seleccion almacen<br>
2021-08-19 : fixed | #551 | Ajustes vista stock cotizacion<br>
2021-08-19 : fixed | #852 | se rearmará la coleccion de item para enviar otros datos necesarios<br>
2021-08-18 : fixed | #692 | Ajustes descuento global - pos<br>
2021-08-18 : fixed | #847 | reporte kardex - se comenta un cambio del 281 temporalmente<br>
2021-08-18 : fixed | #853 | error 500 en notas desde la app movil<br>
2021-08-17 : fixed | #827 | mensaje de comunicacion al admin para habilitar app<br>
2021-08-17 : fixed | #827 | mensaje de comunicacion al admin para habilitar app<br>
2021-08-17 : fixed | ajustes descuento global pos<br>
2021-08-16 : fixed | #845 | Validacion propiedad tipo de afectacion<br>
2021-08-16 : fixed | - | Item: Ajuste para utilizar vuex en la configuracion con config<br>
2021-08-16 : fixed | #806 | Conexion sunat/ose: Se agrega opcion de contexto ssl verify_peer_name<br>
2021-08-16 : fixed | #697 | Reporte consolidado de items: Ocultar cpe y notas de venta anuladas<br>
2021-08-13 : fixed | #625 | Facturacion: Ajustes al validar codigos de errores para determinar estado de cpe<br>
2021-08-13 : fixed | #596 | Minor: Ajuste de estilo en codigo<br>
2021-08-13 : fixed | #818 #596 | Compile JS<br>
2021-08-13 : fixed | #596 | Inventario: Añadiendo sumatoria de Ganancia y Ganancia total<br>
2021-08-13 : fixed | #818 | Cotizacion: Eliminado logs de debug<br>
2021-08-13 : fixed | #818 | Cotizacion: Ajuste para estadarizar el añadir item. Ajuste para no recargar igv en los productos al editar<br>
2021-08-12 : fixed | #838 | Ajuste edicion nota de venta - registro duplicado<br>
2021-08-12 : fixed | #830 | validacion propiedad attributes en form items<br>
2021-08-12 : fixed | #830 | Se modifica condicion para visualizar boton lotes en ventas - ajustes al clonar productos con lotes<br>
2021-08-12 : fixed | #833 | Si el almacen existe, se ajusta, sino queda en null<br>
2021-08-11 : fixed | - | Ajuste para rango de fechas en GlobalPayment.php<br>
2021-08-11 : fixed | #185 | Se agrega seleccion lista precio en listado productos - pos<br>
2021-08-10 : fixed | !249 | Compile JS<br>
2021-08-10 : fixed | !249 | Compile JS<br>
2021-08-10 : fixed | #596 | KARDEX VALORIZADO: Ajuste para stock en el excel.<br>
2021-08-10 : fixed | #281 | KARDEX VALORIZADO: Ajuste para filtrar por establecimientos correctamente<br>
2021-08-10 : fixed | #773 | Ajuste de error ortografico en plataforma<br>
2021-08-09 : fixed | - | Formato de codigo y validacion de descuentos y cargos con row.XX.length > 0<br>
2021-08-10 : fixed | #656 | Ajuste propiedad attributes en productos - error atributos nulos en cpe<br>
2021-08-10 : fixed | #735 | Notas de venta: ajuste nombre de archivo recurrencia nv<br>
2021-08-10 : fixed | #809 | Nuevo cpe: Ajuste para busqueda por codigo de barras<br>
2021-08-09 : fixed | #772 | Productos: Restriccion historial de productos a administradores<br>


### feature
2021-09-06 : feature | - | retornando external id para uso en app movil<br>
2021-09-03 : feature | #813 | Compile JS<br>
2021-09-03 : feature | #901 | Compras: Se evaula si es array para devolver el dato.<br>
2021-09-03 : feature | #704 | Guias: Añadiendo UBIGEO a la direccion. Ruta para buscar Clientes en dispatchs<br>
2021-09-03 : feature | #704 | Guias: Establecimiento automatico al del usuario. Serie si se encuentra por defecto asignada. Unidad de medida a Kilos, Peso total y Numero de paquete a 1. Cliente Al asignado por defecto en la empresa<br>
2021-09-03 : feature | - | Unificacion para busqueda de Clientes (Customers) por id.<br>
2021-09-03 : feature | - | Minor: Ajuste de estilo de codigo<br>
2021-09-03 : feature | #750 | Listado de CPE: Mostrar generar guia cuando el documento este registrado.<br>
2021-09-03 : feature | #813 | Reporte Masivo: Ajuste de salida como vista, no como descarga de archivo<br>
2021-09-03 : feature | #813 | Compile JS<br>
2021-09-02 : feature | #813 | Compile JS<br>
2021-09-02 : feature | #185 | Pos: Se agrega seleccion lista de precios mediante atajo teclas (qwsd)<br>
2021-09-01 : feature | #701 | compile js<br>
2021-09-01 : feature | #701 | Compras: Añadiendo Stock y almacen por defecto<br>
2021-09-01 : feature | #873 | Reporte inventario: se agrego filtro para productos habilitados/inhabilitados<br>
2021-08-31 : feature | #880 | APK: Solo añadir url del apk para env, de forma general<br>
2021-08-31 : feature | #880 | Aplicaciones Extra: Añadiendo wiki para farmacia. Ajuste para Caambiar automaticamente farmacia al habilitar el modulo desde admin<br>
2021-08-28 : feature | #778 | Revirtiendo POS / Condicion de pago eliminada<br>
2021-08-27 : feature | #877 | Validador: Se modifica funcion para regularizar docs con nueva consulta integrada - ajustes respuesta<br>
2021-08-26 : feature | #877 | Validador: integracion consulta integrada - ajuste en vista<br>
2021-08-26 : feature | - | minor: Ajuste de estilo<br>
2021-08-26 : feature | - | Documentos, Cotizacion, Nota de venta: Ajuste para validar los atributos del item.<br>
2021-08-25 : feature | #877 | Validador documentos: consulta integrada autenticacion<br>
2021-08-25 : feature | #289 | Funcionalidad para regularizar/cancelar resumen<br>
2021-08-25 : feature | #502 | Compras: Añadiendo lista de Precios por almacen.<br>
2021-08-24 : feature | #289 | Resumenes: Agregando funcion para regularizar cpe<br>
2021-08-24 : feature | #502 | Compile JS<br>
2021-08-24 : feature | #502 | Compras: Lista de precios: Se puede actualizar la lista de precios al seleccionar "Editar precio de venta"<br>
2021-08-24 : feature | #502 | Minor: Ajuste de estilo<br>
2021-08-24 : feature | #849 | Compile JS<br>
2021-08-24 : feature | #849 | Compile JS<br>
2021-08-24 : feature | #849 | Documentos / Cotizaciones / Nota de venta: Unificacion de elemento Agregar/editar item. Cotizacion difiere en unit_price_value y unit_price. Listado de precios: Se valida is_client = false para mostrarlo  OfflineConfiguration::firstOrFail()->is_client<br>
2021-08-24 : feature | #849 | ItemSlotTooltip; Para normalizar todos los tooltip de item. ItemOptionDescription: Para normalizar la descripcion de items. Aplica en agregar / editar item de document / quotations / sale_notes<br>
2021-08-24 : feature | #849 | item_tables: Añadiendo $operation_types y $is_client para crear un standar en la seleccion de items<br>
2021-08-23 : feature | #874 | Se agrega columna hora a reporte de notas de venta<br>
2021-08-23 : feature | #719 | Se agrega configuracion y funcionalidad para generar cpe desde multiples notas, sin que los items se agrupen<br>
2021-08-20 : feature | #596 | Items: Configuracion para mostrar los datos extra.<br>
2021-08-20 : feature | #596 | Items: Configuracion para mostrar los datos extra.<br>
2021-08-20 : feature | #596 | Items: Generando codigo para utilizarlo el extra data en cotizacion<br>
2021-08-20 : feature | #596 | Items: Guardado de caracteristicas opcionales y forma de implementacion de ser necesario<br>
2021-08-20 : feature | #596 | Items: Añadiendo              'colors',              'CatItemMoldCavity',              'CatItemMoldProperty',              'CatItemUnitBusiness',              'CatItemStatus',              'CatItemPackageMeasurement',              'CatItemProductFamily',              'CatItemUnitsPerPackage' a las caracteristicas del item en CPE<br>
2021-08-19 : feature | #827 | apps que estaran proximamente en listado de apps<br>
2021-08-19 : feature | #827 | apps que estaran proximamente en listado de apps<br>
2021-08-18 : feature | #596 | Items: Añadiendo              'colors',              'CatItemMoldCavity',              'CatItemMoldProperty',              'CatItemUnitBusiness',              'CatItemStatus',              'CatItemPackageMeasurement',              'CatItemProductFamily',              'CatItemUnitsPerPackage' a las caracteristicas del item en CPE<br>
2021-08-18 : feature | #596 | Items: Añadiendo              'colors',              'CatItemMoldCavity',              'CatItemMoldProperty',              'CatItemUnitBusiness',              'CatItemStatus',              'CatItemPackageMeasurement',              'CatItemProductFamily',              'CatItemUnitsPerPackage' a las caracteristicas del item en edicion/creacion de item<br>
2021-08-17 : feature | #852 | attributos en items de cotizaciones<br>
2021-08-16 : feature | #596 | Items: Añadiendo color a las caracteristicas del item<br>
2021-08-13 : feature | #715 | Admin: Se agrega notificiaciones (docs pendientes de envio y rectificacion) - cambio texto a url<br>
2021-08-13 : feature | #596 | Compile jS<br>
2021-08-13 : feature | #737 | Guias: Se modifica componente datatable - agrega campos y filtros<br>
2021-08-13 : feature | #596 | Pedidos: Ajuste para mostrar Despachado pedidos con guia de remision<br>
2021-08-13 : feature | #827 | menu pagos, menu apps en tenant y system<br>
2021-08-12 : feature | #740 | Nuevo cpe: Se agrega informacion de pasajeros a pdf default<br>
2021-08-11 : feature |  | Compile JS<br>
2021-08-11 : feature | #773 | Reporte de inventario: Ajuste de prefijo (pck) para items en el pack<br>
2021-08-11 : feature | #596 | Reporte de inventario: Ganacia total en el reporte de inventario<br>
2021-08-10 : feature | #596 | Reportes de kardex: Añadiendo valores por almacen de los items<br>
2021-08-09 : feature | - | Ajuste para moneda automatica<br>
2021-08-09 : feature | - | Ajuste de nomenclatura de rutas.<br>
2021-08-09 : feature | #570 | CPE: Ajuste para seleccionar moneda automaticamente.<br>
2021-08-09 : feature | #570 | Nota de venta: Ajuste para seleccionar moneda automaticamente.<br>
2021-08-09 : feature | #570 | Configuracion: Añadiendo Moneda por defecto.<br>
2021-08-10 : feature | #711 | mas espacio para bancos en plantilla default3_banks<br>
2021-08-09 : feature | #831 | div genera salto de linea innecesario en detalle de empresa, nuevo comprobante<br>
2021-08-09 : feature | #185 | Pos: se agrega opcion para seleccionar precio de listado de precios al agregar producto - configuracion<br>
2021-08-09 : feature | #828 | modo claro/oscuro afecta al sidebar<br>
2021-08-06 : feature | #772 | Se agrega historial ventas/compras en productos - stock y series por productos - seleccion y configuracion automatica de afectacion gratuita en nuevo cpe - ajustes xml calculos afectacion gratuita<br>


## 4.1.3

### docs
2021-07-20 : docs | changelog update<br>


### fixed
2021-08-06 : fixed | version php causa error sintaxis en consulta de series de notas de venta por api<br>
2021-08-06 : fixed | #446 | Removiendo debug<br>
2021-08-06 : fixed | #446 | Finanzas: Cuentas por cobrar: Ajuste de orden para orden de compra y plataforma<br>
2021-08-06 : fixed | #446 | Finanzas: Cuentas por cobrar: Ajuste de orden para orden de compra y plataforma<br>
2021-08-06 : fixed | #446 | Platform por Plataforma<br>
2021-08-06 : fixed | #446 | Ajuste de columnas en reporte general de productos<br>
2021-08-06 : fixed | - | Hoteles: Habitaciones: Ajuste para no seleccionar almacenes en habitaciones<br>
2021-08-05 : fixed | #777 | Notas: Ajuste cargos globales en nota credito y debito<br>
2021-08-04 : fixed | - | Formato de codigo y simplificacion de querys<br>
2021-08-04 : fixed | - | Formato de codigo<br>
2021-08-04 : fixed | - | Problemas de short tag en php,<br>
2021-08-04 : fixed | - | validando retorno para null en configuracion<br>
2021-08-02 : fixed | #787 |Compile JS<br>
2021-08-02 : fixed | #787 | Guia: Añadiendo posiblidad de anular la guia, si el documento relacionado se encuentra anlulado (se tomará en cuenta el ultimo documento relacionado)<br>
2021-08-02 : fixed | - | El reporte de venta, se le quita la extension xlsx para pdf, ya que no es necesaria<br>
2021-08-02 : fixed | - | S extra en sale notes<br>
2021-07-30 : fixed | #808 | Reporte General de Productos: Ajuste para SaleNoteItem<br>
2021-07-30 : fixed | #787 | Compile JS<br>
2021-07-30 : fixed | - | Añadiendo Log como Log::channel('facturalo')->debug('X'); para log especificos. Quitando log de error de bank en global payment<br>
2021-07-30 : fixed | - | Propiedad Bank no se encuentra<br>
2021-07-30 : fixed | #787 | Lista de Documentos: Aádido Pedidos a la tabla de documentos<br>
2021-07-27 : fixed | #757 | Documentos: Ajustes observaciones descuentos por linea<br>
2021-07-27 : fixed | #787 | Compile JS<br>
2021-07-27 : fixed | #787 | Colecciones devuelven Nota de venta para Documentos<br>
2021-07-27 : fixed | #787 | Documentos: En el listado de documentos, se añade nota de venta para cuando esta cuente con las relaciones. Tambien se añade el estado de las mismas<br>
2021-07-27 : fixed | - | Minor: Ajuste de estilo<br>
2021-07-27 : fixed | #656 | Comprobante: Se extiende la exoneracion de igv para los items:<br>
2021-07-27 : fixed | #656 | Compile JS<br>
2021-07-27 : fixed | #656 | Nota de venta : Se extiende la exoneracion de igv para los items:<br>
2021-07-27 : fixed | #656 | Nota de venta / invoice: Cuando se generan notas de venta y pasan a factura, estas no generan correctamente el IGV, se ajusta para que se obtenga el IGV del item en DB<br>
2021-07-26 : fixed | #446 | Compile JS<br>
2021-07-26 : fixed | #446 | Reporte de general de venta: Se añade la columna plataforma. Reporte de nota de ventas: Se añade Status del pago.<br>
2021-07-26 : fixed | #745 | Reporte de Venta: Ajuste para buscar correctamente purchase_item<br>
2021-07-26 : fixed | - | testingPrice en reporte - venta AÑADIENDO LOG<br>
2021-07-26 : fixed | - | testingPrice en reporte - venta AÑADIENDO LOG<br>
2021-07-26 : fixed | - | testingPrice en reporte - venta<br>
2021-07-26 : fixed | - | testingPrice en reporte - venta<br>
2021-07-26 : fixed | - | testingPrice en reporte - venta<br>
2021-07-23 : fixed | #773 | Compile JS<br>
2021-07-23 : fixed | #773 | Reporte de ventas general : Añadiento campos de modelo y orden de compra<br>
2021-07-23 : fixed | #745 | Ajuste para evaluar el recurso cuando es llamado desde el reporte<br>
2021-07-23 : fixed | #773 | Ajuste para evaluar el recurso cuadno es llamado desde el reporte<br>
2021-07-23 : fixed | #711 | encabezado de tabla de banccos sobre plantilla default3_banks<br>
2021-07-23 : fixed | #743 | Compile JS<br>
2021-07-23 : fixed | #743 | Items Compuestos: Modelo pasa a la ultima columna<br>
2021-07-23 : fixed | #743 | Items Compuestos: Añadiendo modelo a la edicion y al ejempo de xlsx para importar<br>
2021-07-23 : fixed | #790 | Compile JS<br>
2021-07-23 : fixed | #790 | Devolucion de Item: Se ajusta inventory warehouse y kardex para el item devuelto al editarlo.<br>
2021-07-23 : fixed | #790 | Devolucion de Item: Se ajusta inventory warehouse y kardex para el item devuelto al editarlo.<br>
2021-07-21 : fixed | #745 | Reportes: Reporte general de productos: Ajuste para validar los totales de ganancia en USD y PEN<br>
2021-07-21 : fixed | #613 | Reporte de Venta: Ajuste para mostrar impuesto.<br>
2021-07-21 : fixed | #718 | Nota de venta: Generar CPE: Titulo ajustado correctamente a su nota de venta<br>
2021-07-21 : fixed | | Minor: Ajuste de array $attributes<br>
2021-07-21 : fixed | | fixed error array_exist in  /Requests/Inputs/DocumentInput.php:221<br>
2021-07-21 : fixed | | fixed error array_exist in  /Requests/Inputs/DocumentInput.php:221<br>


### feature
2021-08-06 : feature | #596 | Compile JS<br>
2021-08-06 : feature | #596 | Cotizacion/Pedido: Pedido desde cotizacion, solo es posible por el admin y si no se tiene un pedido.<br>
2021-08-05 : feature | #596 | Cotizacion/Pedido: Habilita generar pedido desde cotizaciones.<br>
2021-08-05 : feature | #772 | Se agrega importacion series en compras - seleccion automatica de serie en ventas nuevo cpe<br>
2021-08-05 : feature | #446 | Compile JS<br>
2021-08-05 : feature | #446 | Finanzas: Cuentas por cobrar: Reporte de Todos. Añadiendo plataforma y orden de compra<br>
2021-08-04 : feature | #446 | Finanzas: Cuentas por cobrar: Reporte Formas de pago (dias)<br>
2021-08-04 : feature | #446 | Finanzas: Cuentas por cobrar: Exportar Pdf<br>
2021-08-04 : feature | #446 | Finanzas: Cuentas por cobrar: Exportar Excel<br>
2021-08-04 : feature | #446 | Finanzas: Cuentas por cobrar: Ajuste para componentes reactivos<br>
2021-08-04 : feature | #446 | Finanzas: Cuentas por cobrar: Añadiendo plataforma y orden de compra.<br>
2021-08-04 : feature | #446 | Añadiendo web_platforms a cuentas por cobrar<br>
2021-08-04 : feature | #446 | Reporte Nota de venta: Añadiendo v-if="row.web_platforms !== undefined" en caso que la vista sea consultada desde otro modulo<br>
2021-08-04 : feature | #446 | Reporte Documentos: Añadido plataforma antes de orden de compra<br>
2021-08-04 : feature | #446 | Reporte Nota de Venta: Ajsute para añadir plataforma antes de orden de compra.<br>
2021-08-04 : feature | #446 | Reporte Nota de Venta: Ajsute para añadir plataforma. Se origina desde los items, por lo que pueden haber mas de una plataforma<br>
2021-08-04 : feature | #622 | Compile JS<br>
2021-08-04 : feature | #622 | Nota de venta: Mostrar la url de destino de la consulta<br>
2021-08-03 : feature | #622 | Nota de venta: Ajuste para mensajes de comunicacion e insercion.<br>
2021-08-03 : feature | #622 | Nota de venta: Ajuste para mensajes de comunicacion e insercion.<br>
2021-08-03 : feature | #622 | Nota de venta: Ajuste para mensajes de comunicacion e insercion.<br>
2021-08-03 : feature | #622 | Nota de venta: Enviando Datos al servidor B<br>
2021-08-03 : feature | #622 | Configuracion: Añadiendo web, token y habilitar por configuracion para el envio de nota de venta.<br>
2021-08-03 : feature | #622 | Guarda la nota de venta en otro servidor mediante api. Si el item no se encuentra, se crea.<br>
2021-06-24 : feature | #622 | Obliga al api a crear clientes.<br>
2021-06-24 : feature | #622 | Exportar Nota de venta a otra plataforma Facturalo<br>
2021-08-02 : feature | #773 | $web_platform por $platform<br>
2021-08-02 : feature | #773 | Reporte General de Productos: Añadiendo items de pack en el reporte excel / pdf<br>
2021-07-30 : feature | #794 | Compile JS<br>
2021-07-30 : feature | #794 | Usuarios: Generando cambio de token<br>
2021-07-27 : feature | - | Añadiendo filtrado a tipo de afectacion en el dialogo de item<br>
2021-07-27 : feature | - | Añadiendo constantes para affectation_igv_types_exonerated_unaffected = '20', '21', '30', '31', '32', '33', '34', '35', '36', '37' en la coleccion de configuracion<br>
2021-07-27 : feature | #800 | Reporte de Documentos: Añadiendo Distrito, provincia y departamento para descarga en PDF y XLSX<br>


## 4.1.2

### docs
2021-07-07 : docs | changelog<br>


### fixed
2021-07-20 : fixed | #770 #789 | Compile JS<br>
2021-07-20 : fixed | #770 #789 | Nota de venta y CPE: Ajuste para igv en en exonerado al añadir item<br>
2021-07-20 : fixed | #601 | consulta campo json en mariabd no soportada<br>
2021-07-19 : fixed | #613 | Contabilidad: Exportar Reporte: Venta: Validando condicion para tipo de documento y status del documento<br>
2021-07-16 : fixed | slack | problema al descargar reporte de items para wordpress<br>
2021-07-16 : fixed | #782 | Nota de venta: Modal de pagos: Cambio de number fil de identifier a getNumberFullAttribute()<br>
2021-07-15 : fixed | #761 | Guia de Remision: Ajuste para retirar el item de la guia.<br>
2021-07-15 : fixed | #702 | $type_movement leido correctamente en el reporte excel y pdf<br>
2021-07-15 : fixed | #613 | Llamado correcto al modelo en ReportItemController.php<br>
2021-07-15 : fixed | #613 | Contabilidad: Exportar Reporte: Ajuste para mostrar la moneda de los movimientos en el reporte<br>
2021-07-15 : fixed | #746 | Fixed index<br>
2021-07-14 : fixed | #761 #775 | Compile JS<br>
2021-07-14 : fixed | #761 | Comprobantes Avanzados: Guias de Remision: Añadir item: Ajuste para añadir items correctamente.<br>
2021-07-13 : fixed | #702 | Ajuste error MovementCollection.php:53<br>
2021-07-13 : fixed | #742 | plantillas de guias muestran productos compuestos nuevamente - se recrea el pdf a partir del boton opciones - mismo boton muestra el pdf en el navegador mientras el otro lo descarga directamente<br>
2021-07-13 : fixed | #702 | Finanzas: Movimiento: Posiblidad de descargar el pdf y el xml ordenado<br>
2021-07-12 : fixed | #686 | pie de pagina legenda amazonia - ajuste de espacios respecto a configuracion de visual pdf<br>
2021-07-12 : fixed | #747 | Reporte de Venta: Cambio de orden de columnas distrito por departamento<br>
2021-07-12 : fixed | #771 | Compile JS<br>
2021-07-12 : fixed | #749 | Reporte Caja Chica POS: Ajuste para que los pagos de compra se sumen al egreso total.<br>
2021-07-12 : fixed | #771 | Customer por defecto en CPE<br>
2021-07-12 : fixed | #686 | pto1, plantilla legend_amazonia cuenta con leyenda correcta para todos los formatos tipo ticket de factura y boleta<br>
2021-07-09 : fixed | #753 | Reporte de cuentas por cobrar: La columna total de expenses estaba en la posicion 9, la posicion correcta es 8<br>
2021-07-09 : fixed | #652 | scroll para listado de guias al generar mediante multiple seleccion<br>
2021-07-09 : fixed | #741 | Reporte de Venta: Ajuste de columna en reporte por documentos<br>
2021-07-08 : fixed | #747 | Reporte de Venta: Cambio de localizacion de establecimiento por cliente<br>
2021-07-08 : fixed | - | Añadiendo comentario de la ruta url para facilitar buscarla<br>
2021-07-08 : fixed | #642 | Item: descripcion de productos: Se establece el stock para el establecimiento del usuario (no general) en los componentes d eañadir item<br>
2021-07-08 : fixed | #310 | Guias: corrección ortográfica<br>
2021-07-07 : fixed | #718 | Nota de venta: Generar CPE desde multiples NV: Corrige mostrar los CPE relacionados en el listado de nota de venta.<br>


### feature
2021-07-20 : feature | #771 | template basado en default3_new_account con mas espacio para las cuentas bancarias<br>
2021-07-19 : feature | #702 | Removiendo # de las tablas<br>
2021-07-19 : feature | #596 | Compile JS<br>
2021-07-19 : feature | #596 | Compile JS<br>
2021-07-19 : feature | #596 | Reporte de inventario: Añadiendo profit como diferencia entre costo y precio.<br>
2021-07-19 : feature | #596 | Textos entre cliente y proveedor<br>
2021-07-19 : feature | #596 | Cliente: Dias de crédito: Mostrando la columna de dias<br>
2021-07-19 : feature | #596 | Cliente: Dias de crédito: Añadiendo campo de dias de credito<br>
2021-07-16 : feature | #601 | envio de mensaje al administrador cuando se genera un pedido en ecommerce (plus : historial de pedidos)<br>
2021-07-14 : feature | #746 | Api: Guias de remision:  PDF: Se muestra documento afectado.Se añade el campo documento_afectado de la forma siguiente :    "documento_afectado": {     "serie_documento": "F001",     "numero_documento": "190",     "codigo_tipo_documento": "01"   }<br>
2021-07-13 : feature | #748 | Compile #702 #748 #679<br>
2021-07-13 : feature | #748 | Template DEFAULT_DATE_END: Valida la configuracion, si es farmacia, se le ajusta el registro sanitario del producto<br>
2021-07-13 : feature | #679 | Compras: Añadiendo Nota dedebito y nota de credito<br>
2021-07-12 : feature | loretosot habilita metodo de pago en mobilecontroller para uso en app<br>
2021-07-12 : feature | #686 | pie de pagina legenda amazonia - ajuste de espacios<br>
2021-07-08 : feature | #640 | crear nuevo cliente en formulario de guia lo asigna automaticamente al campo correspondiente<br>
2021-07-08 : feature | api validador para app<br>
2021-07-07 : feature | #310 | Comprobantes avanzados: Se agrega funcionalidad para control de stock en guias<br>
2021-07-07 : feature | #747 | Reporte de ventas: Añadiendo departamento, distrito y provincia para exportar pdf y excel<br>
2021-07-07 : feature | #557 | estructura de plantilla heaer_image_full_width aplicada en cotizaciones, pedidos y notas de venta<br>
2021-07-06 : feature | #310 | Comprobantes avanzados: Se agrega flujo inventario a guias (avance)<br>


## 4.1.1

### docs
2021-06-25 : docs | Update README.md<br>
2021-06-10 : docs | update changelog<br>


### fixed
2021-07-07 : fixed | #699 | mostrando cantidades de lista de precios y calculando montos de compra en funcion a estos<br>
2021-07-07 : fixed | #615 | Compile JS<br>
2021-07-07 : fixed | #615 | Nota de venta: Listado: Seleccionar el vendedor en la nota de venta.<br>
2021-07-07 : fixed | #716 #615 #454 #547 #674 | Compile JS<br>
2021-07-06 : fixed | #674 | Nota de ventas: Vendedor puede crear items desde nota de ventas, si esta habilitado por la configuracion<br>
2021-07-06 : fixed | #716 #615 #454 #547 | Nota de ventas: Añadiendo nombre personalizado de item en planilla default. Posibilidad de añadir vendedor en las notas de venta. Enviando configuracion standar. Modal de generar CPE es cerrado correctamente. Item se homologa a item CPE, puede buscarse por codigo de barra, editarlo, impuesto a bolsa plastica, atributos adicionales.<br>
2021-07-06 : fixed | - | buble continuo en validador<br>
2021-07-06 : fixed | #493 | Compile JS<br>
2021-07-06 : fixed | #493 | Reporte general: Compras: Ajuste para ver serie de productos comprados.<br>
2021-07-06 : fixed | - | Tramite documentario: Ajuste para siguiente y anterior.<br>
2021-07-06 : fixed | - | Tramite documentario: Ajuste para siguiente y anterior.<br>
2021-07-06 : fixed | - | Compile JS - localStorage almacena 5mb solamente. sessionStorage puede almacena mas datos mientras la Tab no se cierre.<br>
2021-07-05 : fixed | #141 | Nuevo cpe: Ajustes metodos de pago en edicion de cpe<br>
2021-07-05 : fixed | #141 | Ajuste orden asignacion moneda cpe<br>
2021-07-05 : fixed | #738 | Reportes: Ajustes reporte compras<br>
2021-07-01 : fixed | #627 | Cotizaciones: se agrega la posibilidad de elegir vendedores<br>
2021-07-01 : fixed | #722 | Compras: validacion caja<br>
2021-06-29 : fixed | #659 | Ventas: Ajustes valores facturacion en importacion de documentos F1<br>
2021-06-28 : fixed | #507 | Pos - Productos: Aplica precios por almacen a productos - Ajuste crud precios por almacen<br>
2021-06-28 : fixed | #720 | compile js<br>
2021-06-25 : fixed | - | Probando descargar 5000 items<br>
2021-06-25 : fixed | #269 | Compile JS<br>
2021-06-25 : fixed | #269 | Compras: Importar XML : El importador se basa en el mismo XML firmado generado por Facturalo. Se añade obtener 600 items debido que el importador requiere de estos para comparar los datos<br>
2021-06-25 : fixed | - | Compile js<br>
2021-06-25 : fixed | - | CPE: Cuando añades metodos de pago, se divide entre cada uno.<br>
2021-06-24 : fixed | #650 | eliminado parametro width de <td> en la vista, al parecer impide en ciertas versiones la descarga correcta<br>
2021-06-24 : fixed | #714 | Compile JS<br>
2021-06-24 : fixed | #714 | CPE: Ajuste en contado para que ajuste el total directamente. Ajuste para ampliar lotes.<br>
2021-06-24 : fixed | #590 | Compile JS<br>
2021-06-24 : fixed | - | Ajuste para no mostrar error en ticket_58<br>
2021-06-24 : fixed | #590 | Farmacia: Ajuste para importar y exportar items de DIGEMID.<br>
2021-06-23 : fixed | - | CPE: Lista todos los usuarios cuando tienes uno por defecto, no borra los anteriores.<br>
2021-06-21 : fixed | #590 | Ajuste para guardar correctamente el permiso de farmacia<br>
2021-06-17 : fixed | #590 | Creacion de modulo DIGEMID. Falta importacion por catalogo y menu<br>
2021-06-17 : fixed | #308 | Reportes consolidados: Exportar items en pdf: fix de indices no encontrados<br>
2021-06-15 : fixed | #664 #631 #657 | Compile js<br>
2021-06-15 : fixed | #681 | Permitir busqueda por codigo de barras en POS en el campo normal<br>
2021-06-14 : fixed | #631 | Pedido: Habilita vendedor para que pueda generar comprobantes<br>
2021-06-14 : fixed | #631 | Configuracion: Cambio de texto "Permite habilitar las acciones en oportunidad de venta para vendedores" a "Permite habilitar las acciones para vendedores".<br>
2021-06-14 : fixed | #664 | Registro de items en caja: Al editar un item, se reporta en caja nuevamente. Se realiza ajuste para que cuando se edite, pueda actualizarse y no duplicar el registro<br>
2021-06-14 : fixed | #637 | Reporte Cuentas por pagar, añadiendo columna vendedor<br>
2021-06-14 : fixed | #678 | Compile JS<br>
2021-06-09 : fixed | #591 | Comando: php artisan tenancy:run recurrency:sale-note : Se ajusta para que el numero sea consecutivo basado el serie y prefijo<br>


### feature
2021-07-06 : feature | - | Tramite Docuemntario: Añadiendo dias festivos<br>
2021-07-06 : feature | - | Tramite Documentario: Correccion de fecha final de la etapa<br>
2021-07-06 : feature | - | Tramite Documentario: Compile JS<br>
2021-07-05 : feature | #551 | Cotizaciones: Agrega seleccion de almacen al generar cpe<br>
2021-07-01 : feature | - | Tramite Documentario: Ajuste para las 4 etapas de tramite documentario / Erro ticket_58 en cotizacion<br>
2021-07-01 : feature | - | Tramite Documentario: Ajuste para las 4 etapas de tramite documentario<br>
2021-07-01 : feature | - | Tramite Documentario: Ajuste para las 4 etapas de tramite documentario<br>
2021-07-01 : feature | - | Tramite Documentario: Ajuste para las 4 etapas de tramite documentario<br>
2021-07-01 : feature | #722 | Pos: Se integra compras a caja chica<br>
2021-07-01 : feature | - | Tramite Documentario: Compile JS<br>
2021-07-01 : feature | - | Tramite Documentario: Texto Oficina a Etapa, Icono de ojo para historico de observaciones. Filtro se establece como inicial el dia de hoy.<br>
2021-06-30 : feature | - | Tramite Documentario: Falta ajustar devolver.<br>
2021-06-30 : feature | - | Tramite Documentario: Ajuste para cuando ya no se tiene procesos hijos<br>
2021-06-30 : feature | - | Tramite Documentario: Ajuste para cuando ya no se tiene procesos hijos<br>
2021-06-30 : feature | - | Tramite Documentario: Compile JS<br>
2021-06-30 : feature | #722 | Pos: Agregando compras a caja chica<br>
2021-06-30 : feature | - | Tramite Documentario: Admin puede ver todos los expedientes, pero solo se podran ver los expedientes en la etapa asignada para los usuarios.<br>
2021-06-30 : feature | - | Tramite Documentario: Ajuste para obtener las observaciones. Textos y visual.<br>
2021-06-30 : feature | #232 | Ventas: Se agregan cargos globales a nuevo cpe<br>
2021-06-30 : feature | - | Tramite Documentario: Ajuste de retornos<br>
2021-06-30 : feature | - | Tramite Documentario: Ajuste de retornos<br>
2021-06-30 : feature | - | Tramite Documentario: Compilacion JS<br>
2021-06-30 : feature | - | Tramite Documentario: Ajuste para carga de archivos, atras y adelante en el proceso de tramite<br>
2021-06-30 : feature | mostrando boton de servidor alterno para que puedan cambiar a NO en la bd de cada facturador, complementar con configuracion de archivo .env<br>
2021-06-30 : feature | #699 | unidad de medida en listado de productos generales<br>
2021-06-29 : feature | - | Tramite Documentario: Mejoras al modulo, Ajustes para cargar archivos por dropzone, descargar y borrarlo. Cuadro de observaciones.<br>
2021-06-29 : feature | #232 | configuracion: se agrega config para recargo al consumo<br>
2021-06-28 : feature | - | Tramite Documentario: Mejoras al modulo, Oficinas se llama Etapas. Pueden tener 1 hijo. Proceso tiene precio.<br>
2021-06-28 : feature | #695 | template full_height_ticket ancho de imagen logo completa, solo para tickets<br>
2021-06-25 : feature | - | instalacion de Vuex<br>
2021-06-25 : feature | #629 | Reporte de Caja POS: Añadiendo Nota de debito y credito a los documentos que se relacionan.<br>
2021-06-25 : feature | - | Añadiendo info de version de laravel a la config<br>
2021-06-23 : feature | #702 | Aumento a 1000 registros para provocar error 504<br>
2021-06-23 : feature | #702 | Compile JS<br>
2021-06-23 : feature | #702 | Finanzas: Movimiento: Ajuste de la tabla para que sea por frontend y pueda ordenarse.<br>
2021-06-22 : feature | #702 |   ini_set('max_execution_time', 0);<br>
2021-06-22 : feature | #702 | Compile JS<br>
2021-06-22 : feature | #702 | removiendo paginacion<br>
2021-06-22 : feature | #702 | Compile JS<br>
2021-06-22 : feature | #702 | Balance: Finanzas: Ajuste de tabla con el-table para testing. Se cambia la paginacion por frontend<br>
2021-06-22 : feature | #596 - 5 | Clientes: Listado: Añadiendo columnas para su visualizacion opcional<br>
2021-06-22 : feature | #596 - 5 | Menor: Ajuste de estilo de codigo<br>
2021-06-22 : feature | #596 - 5 | Clientes: Añadiendo datos de clientes (Zona, Observacion, Sitio Web)<br>
2021-06-22 : feature | #596 - 5 | Clientes: Añadiendo datos de clientes (Zona, Observacion, Sitio Web)<br>
2021-06-21 : feature | #493 | Compile js<br>
2021-06-21 : feature | #493 | Reporte: Compra: Ocultando plataforma cuando es por compras<br>
2021-06-21 : feature | #493 | Reporte: Compra: Reporte por productos: Exportar productos de forma individual<br>
2021-06-21 : feature | #493 | Ajuste para busqueda de productos por compra<br>
2021-06-21 : feature | #493 | Documentacion de Modelos<br>
2021-06-21 : feature | #493 | Reporte: Compra: Porductos generales<br>
2021-06-18 : feature | #493 | Añadiendo ruta para los reportes<br>
2021-06-18 : feature | #590 | Compile JS<br>
2021-06-18 : feature | #590 | Ajuste para no sobreescribir datos del item<br>
2021-06-18 : feature | #590 | Modulo DIGEMID: Ajuste de permisos para DIGEMID<br>
2021-06-18 : feature | #590 | Modulo DIGEMID: Ajuste de importacion masiva<br>
2021-06-18 : feature | reporte general de productos muestra nota de credito en opciones del filtro y reporte excel para ventas muestra vendedor<br>
2021-06-18 : feature | #688 | moneda en reportes de cuentas por cobrar/pagar<br>
2021-06-16 : feature | #599 | Guias de Remision: Ajuste para prevenir el enviado automatico a Sunat por configuracion. En vez de eso, se hará manual por la lista.<br>
2021-06-16 : feature | #599 | Guias de Remision: Ajuste para prevenir el enviado automatico a Sunat por configuracion. En vez de eso, se hará manual por la lista.<br>
2021-06-16 : feature | #627 | Cotizacion: Generar CPE: Permie seleccionar vendedor<br>
2021-06-16 : feature | #603 | Cotizacion: Nueva Cotizacion: Generar comprobante: Se añade Observacion y orden de compra<br>
2021-06-16 : feature | #308 |  Compile JS<br>
2021-06-16 : feature | #308 | Reporte: Guias: Creando el modulo de Consolidado Por Items.<br>
2021-06-16 : feature | #308 | Ajuste para normalizar las colleciones mediante el modelo.<br>
2021-06-15 : feature | #533 | mejoras visuales en pos<br>
2021-06-15 : feature | #308 | Ajuste de estilo de codigo<br>
2021-06-15 : feature | #657 | Documentacion de variables<br>
2021-06-15 : feature | #657 | Pedidos: Nuevo / Editar producto se le añade nombre pdf. Template default para order note se ajusta el nombre pdf o, la descripcion del item<br>
2021-06-10 : feature | #588 #648 | Compile JS<br>
2021-06-10 : feature | #588 | Producto/Servicio: Producto: Se añade la lista de columnas si la configuracion es de farmacia<br>
2021-06-10 : feature | #648 | Finanzas: Pagos: Se añade cuentas bancarias al destino de modo BankAccount::class.'::'. id, y asi filtrar el banco correspondiente.<br>
2021-06-10 : feature | #588 | Producto/Servicio: Producto: Si esta habilitada la configuracion de farmacia, se muestra registro sanitario y codigo de observacion<br>
2021-06-10 : feature | #588 | Configuracion: Empresa: Añade la posibilidad de colocar el codigo de observacion DIGEMID a la empresa<br>


## 4.1.0

### docs
2021-05-17 : docs | changelog<br>


### fixed
2021-06-10 : fixed | #649 | si no se aplica tipo vendedor y selecciona un nombre de vendedor se sobre entiende que se usa -vendedor asignado-<br>
2021-06-09 : fixed | #630 | Ventas: Pedidios: Nuevo/Editar: Item: Si esta activado la configuracion "Permitir Editar precio unitario a vendedores" y no es admin, podra editar el precio unitario en los pedidos.<br>
2021-06-09 : fixed | #676 | reporte documentos pdf, se traslada los totales a la posicion correcta<br>
2021-06-08 : fixed | - | Clave de usuarios de tenant 5.3.1 para para restaurarlas en mysql.user<br>
2021-06-08 : fixed | #661 | marca repetida al seleccionar con enter un producto en pos<br>
2021-06-07 : fixed | #653 | relacion de item en cotizacion para mostrar el nombre de producto para pdf<br>
2021-06-04 : fixed | #653 | nombre producto pdf en plantilla customer_contact<br>
2021-06-04 : fixed | #455 | Test Set time limit 3900 para balance<br>
2021-06-04 : fixed | #307 | Ajuste en ruta para series<br>
2021-06-03 : fixed | #307 | Menor: Ajuste de estilo<br>
2021-06-03 : fixed | #568 | Producto: Duplicado: Se añade (duplicado) a la descripcion del item para poder distinguirlo cuando es duplicado<br>
2021-06-03 : fixed | #572 | Configuracion: Metodo de ingreso: Añadiendo columna de condicion de pago en ingresos.<br>
2021-06-03 : fixed | #572 | Ajuste de templates para mostrar metodo de pago cuando es credito.<br>
2021-06-03 : fixed | #572 | Comrpobante electronico: Generar pdf: Ajuste para almacenar el metodo de pago en fee y poder filtrarlo al momento de mostrar la factura o boleta.<br>
2021-06-02 : fixed | #514 | Compile JS<br>
2021-06-02 : fixed | #514 | Compile JS<br>
2021-06-02 : fixed | #514 | Compras: Gastos diversos: Nuevo: Añadiendo filtro a Motivo.<br>
2021-06-01 : fixed | #455 | Finanzas: Ingreso y Egreso por metodo de pago: Añadiendo Totales. Mejorando el rendimiento del reporte.<br>
2021-06-01 : fixed | #455 | Finanzas: Balance: Añadiendo saldo inicial. Totales. Mejorando el rendimiento del reporte.<br>
2021-05-31 : fixed | #455 | menor, ajuste de estilo.<br>
2021-05-31 : fixed | #572 | Ajustando documentacion para excluir elementos en metodo de pago, Ajustando scope para ExcludeMethodTypes.<br>
2021-05-28 : fixed | #572 | Venta: Comprobante Electrónico: Añadiendo Crédito y Crédito con cuotas a la factura.<br>
2021-05-27 : fixed | #626 | Compile JS<br>
2021-05-27 : fixed | #626 | Venta: Oportunidad de venta: Habilita la configuracion para que un vendedor pueda tener acciones en las oportunidades de venta para generar comprobantes.<br>
2021-05-27 : fixed | #616 | Compile JS<br>
2021-05-27 : fixed | #616 | Pedido: Generar Comprobante: Se añade boleta y nota de venta cuando el customer tiene Doc.trib.no.dom.sin.ruc o DNI como documento de identificacion<br>
2021-05-26 : fixed | #399 #580 | Compile JS<br>
2021-05-26 : fixed | #580 | Editar/Crear item: Al momento de crear el modal, se limpia el precio por almacen.<br>
2021-05-26 : fixed | #399 #580 |<br>
2021-05-26 : fixed | #399 | Integraicion del commit cdd7c6ab<br>
2021-05-25 : fixed | #591 | Nota de venta: Crear/Editar: Cuando el tipo de periodo y cantidad de periodo no esten vacios, se mostrará un aviso para mostrar cuando se duplicará la nota de venta<br>
2021-05-25 : fixed | #616 | Pedidos: Listado: Generar Comprobante: Habilitando todos los tipos de comprobantes enviados por modules/Order/Http/Controllers/OrderNoteController.php::212<br>
2021-05-25 : fixed | #269 |  Compras: Listado: Importar: Añadiendo notificaciones para XML que no cumplan con los elementos requeridos.<br>
2021-05-25 : fixed | #619 | error pagina en blanco al exportar productos general; se aumenta la cantidad que soporta php a convertir el html de la vista para el reporte<br>
2021-05-24 : fixed | #578 | Contabilidad: Exportar Reporte: Venta: Si no se encuentra el documento, se añaden los datos de  data_affected_document para mostrarlo en el reporte<br>
2021-05-24 : fixed | #482 | Reporte de Caja: Si el documento es a Credito, no se sumará si esta anulado.<br>
2021-05-21 : fixed | #500 | Ventas: Pedidos:  Anular: Evaluar correctamente el has_sale<br>
2021-05-21 : fixed | #500 | Ventas: Pedidos: Series: Cuando se añade un producto, la serie se evalua correctamente al realizar un pedido<br>
2021-05-21 : fixed | #500 | Ventas: Pedidos: Al anular un pedido cuyos items tengan lotes, estos se vuelven a habilitar.<br>
2021-05-21 : fixed | #587 | reporte general de productos campos codigo interno y unidad de medida<br>
2021-05-20 : fixed | #578 | Contabilidad: exportar reporte: venta: si existe data_affected_document, se buscara el documento, si este existe, se tomará para el calculo.<br>
2021-05-20 : fixed | #473 | Finanzas: Movimientos: Exportar Excel: Se ajusta el nombre de la hoja para maximo 30 caracteres.<br>
2021-05-20 : fixed | #609 | ajustes al mostrar las guias de remision de la plantilla legend_amazonia<br>
2021-05-19 : fixed | #611 | redireccion de diferentes usuario en la vista de ordenes de compras<br>
2021-05-19 : fixed | #604 | Productos: Item: Exportar Excel: Se añade la lista de precios para los elementos consultados en el reporte.<br>
2021-05-19 : fixed | #602 | enlaces eliminados u ocultos en ecommerce<br>
2021-05-19 : fixed | #357 | Pos: Venta: Packs: Evalua el inventario de cada item del pack multiplicando la cantidad individual en el pack por la cantidad solicitada. Debe habilitarse stock_control para la validacion<br>
2021-05-18 : fixed | #595 | error en variables que muestran cargos, agregado los cargos globales en totales del pdf<br>


### feature
2021-06-09 : feature | #646 #645 #644 #630 #586 | Compile JS<br>
2021-06-09 : feature | #646 #645 #644 #630 #586 | Compile JS<br>
2021-06-09 : feature | #645 | Comprobantes avanzados: Guias de remisión: Al crear, Permite seleccionar rapidamente transportistas y conductores. Se llena el input con los valores de los selectores.<br>
2021-06-09 : feature | #645 | Comprobantes avanzados: Guias de remisión: Al crear, Permite seleccionar rapidamente transportistas y conductores. Se llena el input con los valores de los selectores.<br>
2021-06-09 : feature | #646 | Productos y servicios: Productos: Añadido Marcas y Modelo al listado.<br>
2021-06-09 : feature | #586 | Ventas: Nota de ventas: Habilita la  funcion de duplicar la nota de venta.<br>
2021-06-09 : feature | #645 | Comprobantes avanzados: Guias de remisión: Permite seleccionar rapidamente transportistas y conductores. Se llena el input con los valores de los selectores.<br>
2021-06-09 : feature | #645 | Menor: Ajuste de estilo de codigo.<br>
2021-06-09 : feature | #644 | Configuracion: Empresa: Avanzado: Pdf: Ajuste para permitir la seleccion de actualizacion del pdf de documento al generar la guia.<br>
2021-06-09 : feature | #644 | Ajuste para añadir configuraciones de actualizar documentos al generar despacho.<br>
2021-06-09 : feature | #644 | Al generar la guia, se actualiza automaticamente el pdf de factura.<br>
2021-06-09 : feature | #644 | Ajuste en modelo para actualizar los archivos pdf.<br>
2021-06-07 : feature | #650 | campo numerico ahora es alfanumerico<br>
2021-06-07 : feature | #650 | reporte excel para gastos diversos<br>
2021-06-04 : feature | #654 | en campo vendedores de formulario de venta se muestran tanto admin con vendedores del establecimiento actual<br>
2021-06-04 : feature | #307 #572 | Compile JS<br>
2021-06-04 : feature | #307 | Comprobante Electronico: Ajuste para seleccionar automaticamente la factura y serie por defecto<br>
2021-06-04 : feature | #307 | Usuarios: Editar/crear usuario: Ajuste grafico por tabs.<br>
2021-06-04 : feature | #651 | cliente en plantilla de compra customer_contact y reporte de compras totales<br>
2021-06-03 : feature | #307 | Comprobante Electronico: Ajuste para seleccionar automaticamente la factura y serie por defecto<br>
2021-06-03 : feature | #307 | Usuarios: Editar usuario: Seleccionar un tipo de documento y serie por defecto para el usuario<br>
2021-06-03 : feature | #584 | fecha de vencimiento en reporte de documentos<br>
2021-06-03 : feature | issues | plantillas predefinidas para generar issues en gitlab<br>
2021-06-02 : feature  | #424 | Compile JS<br>
2021-06-02 : feature  | #424 | Reporte: Pedidos: Consolidado de items: Añadiendo Totales por productos con exporte pdf y excel.<br>
2021-06-02 : feature  | #424 | Menor: Ajuste de estilo<br>
2021-05-26 : feature | - | Añadiendo informacion sobre datos del archivo de configuracion PHP<br>
2021-05-26 : feature | - | Añadiendo informacion sobre datos del archivo de configuracion PHP<br>
2021-05-26 : feature | - | Añadiendo informacion sobre datos del archivo de configuracion PHP<br>
2021-05-21 : feature | #518 | visualizacion de campos de pago en pago de notas de ventas<br>
2021-05-18 : feature | #592 | plantilla para notas de venta en formato ticket_58<br>


## 4.0.9

### docs
2021-05-05 : docs | changelog<br>


### fixed
2021-05-17 : fixed | #521 | Reporte: Ventas: Ajuste de tiempo maximo para pdf a 1800 segundos (30 minutos)<br>
2021-05-14 : fixed | #517 | Compile JS<br>
2021-05-14 : fixed | #517 | Nota de Ventas: Añadiendo fecha de pago al metodo de pago<br>
2021-05-14 : fixed | #389 | permisos y restricciones de accesos mediante urls<br>
2021-05-13 : fixed | #519 | missing use configuration<br>
2021-05-13 : fixed | #519 #577 | Fix Response<br>
2021-05-13 : fixed | #519 | Compile js<br>
2021-05-13 : fixed | #519 | Finanzas: Balance: Oculta el saldo total basado en la configuracion seller_can_view_balance<br>
2021-05-13 : fixed | #519 | Configuracion: Avanzada: Visual: Permite mostrar opcion de vendedor para ver balance en finanzas<br>
2021-05-13 : fixed | #578 | Contabilidad: Exportar reporte: Reporte de venta: Se amplia nota de debito y credito cuando afecta a un documento, para evaluar su status y, de estar anulada, se ajusta a 0 el total de la misma<br>
2021-05-12 : fixed | #516 #577 | Compile JS<br>
2021-05-12 : fixed | #577 | Item: DocumentFormItem evalua si es vendedor y si puede crear producto.<br>
2021-05-12 : fixed | #577 | Configuracion: Visual: Se añade posibilidad de vendedor para crear producto<br>
2021-05-12 : fixed | #389 | accesos de usuarios a vistas sin permisos<br>
2021-05-12 : fixed | #516 | Admin: Lista de usuarios: Ajuste grafico para orden de comprobantes<br>
2021-05-12 : fixed | #578 | Contabilidad: Exportar Reporte: Ajuste para colocar en 0 los totales cuando sea factura o bolenta, con status Rechazado o Anulado<br>
2021-05-12 : fixed | #578 | Contabilidad: Exportar Reporte: Ajuste para mostrar las notas correctamente.<br>
2021-05-12 : fixed | 582 | errores en filename y selector de series al editar comprobante<br>
2021-05-11 : fixed | #482 | Cash: Reportes de caja: Generacion de datos en el controlador. Ajuste para añadir credito y su calculo en los reportes de caja<br>
2021-05-11 : fixed | #582 | actualizando correlativo al modificar tipo de comprobante tomando el proximo numero a registrar en la serie seleccionada<br>
2021-05-11 : fixed | #576 | Compras: Anular Compra: Ajuste para verificar que exista lots_enabled.<br>
2021-05-10 : fixed | #407 #554 | Compile js<br>
2021-05-10 : fixed | #496 | Administración: Usuarios: Restaurando configuración de correo por cliente<br>
2021-05-10 : fixed | #482 #554 | Revirtiendo Cambios 3a2d670b838dabcddbcd3698d29c1d9bc13e93d0 37a23f9f91feaa47ae8bb695706c531e842d6de5 496e0cb02dd740c0d0cca36b285b5f5b69dfaa8d 8cfda276e32db47e7834ebfcd2e260dcccc09bb5 664d0a047d62c286396ead521c9dd71616b4e828<br>
2021-05-10 : fixed | #550 | altura cuerpo de plantilla<br>
2021-05-10 : fixed | #407 | Compras: Añadir Producto: Codigo de barras: Al encontrar el item, se elimina la busqueda por barras y se ajusta normal.<br>
2021-05-10 : fixed | #563 | Marcas y Categorías: Crear/Editar: Se añade validacion para marca o categoría única<br>
2021-05-07 : fixed | #433 | Reporte Caja: Muestra todos los ingresos a caja. No suma Credito al total de caja<br>
2021-05-07 : fixed | #433 #468 #554 | Compile js<br>
2021-05-07 : fixed | #554 | Documentos por Regularizar: Archivo de migracion<br>
2021-05-07 : fixed | #554 | Documentos por Regularizar: Se añade posiblidad de eliminar documentos con regularize_shipping y response_regularize_shipping, que tambien, tengan un documento con serie y con numero igual. Softdelete a tabla documents<br>
2021-05-07 : fixed | #433 | Reporte: Venta: Documento: Se añade opciones de descarga en columna<br>
2021-05-06 : fixed | #468 | Nota de venta: Se añade fecha de vencimiento a las nota de venta. Se lista Fecha de vencimiento a las nota de ventas<br>
2021-05-06 : fixed | #511 #512 | Finanzas: Cuentas por Cobrar/ Cuentas por pagar: Añadiendo la opcion a todos en proveedores y clientes<br>


### feature
2021-05-17 : feature | #592 | ticket formato 58mm para notas de venta<br>
2021-05-13 : feature | #589 | mejoras visuales de tablas y datos del dashboard<br>
2021-05-07 : feature | #550 | plantilla con bordes redondeados -rounded-<br>
2021-05-06 : feature | - | Tarea Programada: Añadiendo bat para tareas programadas en windows<br>


## 4.0.8

### docs
2021-04-21 : docs | changelog<br>


### fixed
2021-05-05 : fixed | #565 | mostrando logs al ejecutar una tarea programada<br>
2021-05-05 : fixed | #513 | Compile js<br>
2021-05-05 : fixed | #565 | mostrando logs al ejecutar una tarea programada<br>
2021-05-05 : fixed | #482 | CashControllerRevision: Separando Tipo de documenos de Metodos de pago<br>
2021-05-04 : fixed | #496 #407 | Compile JS<br>
2021-05-04 : fixed | #496 | Administración: Usuarios: Notificacion para correo como informacion adiciona en el manual. Cambia la configuracion si todos los campos estan llenos. Contraseña queda excluida de la actualizacion<br>
2021-05-04 : fixed | #544 | Cotizacion: Nueva cotizacion: Añadido Marca al PDF default A5.<br>
2021-05-04 : fixed | #544 | Cotizacion: Nueva cotizacion: Añadido Marca al PDF default.<br>
2021-05-04 : fixed | #566 | plantilla model3<br>
2021-05-04 : fixed | #540 | llamados en mobilecontroller<br>
2021-05-04 : fixed | #407 | Compras: Añadir Producto: Codigo de barras: Se añade la funcionabilidad de buscar por codigo de barras.<br>
2021-05-03 : fixed | #545 | valor booleano desde resources<br>
2021-05-03 : fixed | #496 | Administración: Usuarios: Añade configuracion de correo por clientes.<br>
2021-05-01 : fixed | #141 | se reparo el error al convertir dolares a soles y viceversa, al editar comprobantes<br>
2021-04-30 : fixed | #503 | Pos: Busqueda: Se ajusta para que la paginacion funcione correctamente por input_item<br>
2021-04-30 : fixed | #555 | consulta de productos mediante busqueda en guias de remision<br>
2021-04-30 : fixed | #503 | Pos: Busqueda: Se ajusta para que la paginacion funcione correctamente por input_item<br>
2021-04-30 : fixed | #539 | Admin: Usuarios: Modulos: Permite que el los nodos hijos se seleccionen cuando el padre es seleccionado, y quiten la seleccion cuando el caso contrario suceda<br>
2021-04-30 : fixed | #498 | Reporte: Venta: GENERAL DE PRODUCTOS: Muestra Ventas Anuladas en los registros cuando no deberia.<br>
2021-04-30 : fixed | #555 | vista guia tarda mucho en realizar consultas, se elimina repeticion de queries de localidades<br>
2021-04-29 : fixed | #539 | Compile JS<br>
2021-04-29 : fixed | cambio de plantilla solo cuando se tenga un establecimiento seleccionado<br>
2021-04-29 : fixed | #539 | Usuarios: Permisos: Ajuste para seleccionar un elemento y sus hijos. Ajusta el mismo status de check a los hijos<br>
2021-04-29 : fixed | #556 | Pos: Añade item al buscar por Codigo de barras<br>
2021-04-29 : fixed | #482 | CashControllerRevision: Nomenclatura correcta.<br>
2021-04-29 : fixed | #513 | Dashboard: Grafico Nota de venta - Comprobante: Total Pagado por Total Cobrado / Total por Pagar por Pendiente de cobro<br>
2021-04-28 : fixed | #482 | Removiendo PaymentMethodType del template<br>
2021-04-28 : fixed | #482 | compile js<br>
2021-04-28 : fixed | #425 | Compile JS<br>
2021-04-28 : fixed | #503 | Compile JS<br>
2021-04-28 : fixed | #503 | Pos: Filtrado de items: Ajuste para evaluar si description es null, sino se toma name o internal_id. Se mantiene el filtrado input_item<br>
2021-04-27 : fixed | #482 | Pos: Caja chica Pos: Reporte PDF: Se remueven metodos de pago a credito de facturacion, para que en el reporte puedan salir correctamente listados y de el monto correcto en el ingreso a caja<br>
2021-04-26 : fixed | #329 | Compile JS<br>
2021-04-26 : fixed | #541 | Nota de venta: Creacion con producto con lotes: Validar que exista presentation -> quantity_unit en el array<br>
2021-04-26 : fixed | #466 | Contabilidad: Exportar Reporte: Ajuste para validar que documento exista cuando document_type_id es 07 o 08<br>
2021-04-26 : fixed | #425 | REPORTES: CONSISTENCIA DE DOCUMENTOS POR RANGO DE FECHAS: Al front, se limita a 30 dias la seleccion final de consulta.<br>
2021-04-26 : fixed | #425 | REPORTES: CONSISTENCIA DE DOCUMENTOS POR RANGO DE FECHAS: Se elimina la validacion getIsClient, se inicia con 1 mes pero el cliente puede consultar todo el rango de fechas( documents created_at min hasta documents created_at max)<br>
2021-04-26 : fixed | #530 | Facturas: (invoice) Ocultar cuentas inhabilitadas y que tengan "Mostrar cuenta en los reportes de comprobantes" desactivado para el tema Top Ruc<br>
2021-04-26 : fixed | #329 | Pedidos: Ajuste para establecer seller_id desde Pedidos al generar el comprobante<br>
2021-04-23 : fixed | #329 | Compile js<br>
2021-04-23 : fixed | #529 | Compra: Nueva compra desde Orden de Compra: update_price si es verdadero accede a la actualizaion de precios.<br>
2021-04-23 : fixed | #529 | Compra: Nueva compra desde Orden de Compra: Error Undefined index: update_price al crear Nueva Compra<br>
2021-04-22 : fixed | #329 | Nota de ventas: Ajuste para establecer seller_id desde nota de ventas al generar el comprobante<br>
2021-04-22 : fixed | #451 #479 | Compile JS<br>
2021-04-22 : fixed | #451 | Item: Marcas: Ajuste para la url de brands. No guarda desde el modulo de compra<br>
2021-04-22 : fixed | #451 | Item: Categorias: Ajuste para la url de categorias. No guarda desde el modulo de compra<br>
2021-04-22 : fixed | #479 | Nota de ventas: Generar Comprobante: Ajuste para que v-modal no se sobreponga en el modal de impresion<br>
2021-04-22 : fixed | #432 | Compile JS<br>
2021-04-22 : fixed | #399<br>
2021-04-21 : fixed | #408 | Actualizar precio de compra en formulario de PRODUCTO en base al precio de COMPRA del formulario de COMPRAs<br>
2021-04-21 : fixed | i-399 | PROBLEMA NO COINCIDE STOCK DE CATALOGO/PRODUCTOS CON REPORTE KARDEX<br>
2021-04-21 : fixed | #515 | titulos y listas en modo oscudo<br>
2021-04-21 : fixed | #432 | Se revierte la configuracion para stock 0 y solo items por almacen de usuario. En vez de eso, se mostrará el almacen del item<br>
2021-04-21 : fixed | #481 | error lots_enable (no existe key en json)<br>
2021-04-21 : fixed | #497 | Log Diario de laravel en .env.example<br>
2021-04-21 : fixed | #497 | Compile JS<br>
2021-04-21 : fixed | #497 | Ajuste para validar user_id como creador de sales note<br>
2021-04-20 : fixed | #497 | Reporte general de productos: El modelo sale_notes no permite registrar el vendor. Se ajusta para que nota de venta no muestre vendedor.<br>


### feature
2021-05-04 : feature | #434 | organizando anulaciones por orden de fecha de emision<br>
2021-05-03 : feature | #540 | mobileContrlller y ruta para subir imagenes desde apk personalizadas por terceros<br>
2021-04-29 : feature | #545 | creado ticket 58mm para pedidos<br>
2021-04-29 : feature | habilitando ticket de 58mm desde configuracion avanzada<br>
2021-04-28 : feature | #545 | rediseño de vista configuracion avanzada<br>
2021-04-27 : feature | #450 | compile js<br>
2021-04-27 : feature | #450 | modulo de compras funcionando con formatos por establecimientos<br>
2021-04-27 : feature | #450 | modulo de ventas funcionando con formatos por establecimientos<br>
2021-04-27 : feature | #450 | plantilla de establecimiento funcionando en cotizaciones (recorriendo todos los documentos para que tomen esta configuracion)<br>
2021-04-27 : feature | #450 | plantilla de establecimiento funcionando en oportunidades de venta (recorriendo todos los documentos para que tomen esta configuracion)<br>
2021-04-27 : feature | #450 | plantilla de establecimiento funcionando en notas de venta (recorriendo todos los documentos para que tomen esta configuracion)<br>
2021-04-23 : feature | #450 | guardando plantilla por establecimiento, funcionando solo en documents (guias notas y demas en proceso)<br>
2021-04-22 : feature | #450 | campo en establecimiento para formato pdf, consulta en vista del formato actual por establecimiento (en proceso)<br>


## 4.0.7

### docs
2021-04-12 : docs | changelog<br>


### fixed
2021-04-21 : fixed | complie js, padding top nota de credito/debito<br>
2021-04-21 : fixed | condicion para editar precio en nota de credito<br>
2021-04-20 : fixed | #480 | Compile JS<br>
2021-04-20 : fixed | #480 | Lista de pedidos en tienda virtual: Se determina que description es el nombre del producto (desde creacion del item) por lo tanto, se cambia la etiqueta de la propiedad<br>
2021-04-19 : fixed | #432 | Filtro de productos, seleccion de solo item.<br>
2021-04-19 : fixed | #501 | Reporte de Ventas: Ajuste para alinear totales en descarga (pdf y excel)<br>
2021-04-18 : fixed | #436 | Editar precio en perfil vendedor - pos<br>
2021-04-18 : fixed | #367 | Pago de servicio tecnico en caja<br>
2021-04-16 : fixed | #425 | REPORTES: CONSISTENCIA DE DOCUMENTOS POR RANGO DE FECHAS: Se ajsuta la validacion para seleccionar por rango de fechas.<br>
2021-04-15 : fixed | #309 | TIENDA VIRTUAL: Al seleccionar la categoria, se mostrará resaltado y enviará correctamente a la categoria<br>
2021-04-15 : fixed | #480 | Tienda virtual: Pedidos: Añadiendo descripcion a la tabla de pedidos (lupa)<br>
2021-04-15 : fixed | #495 | NV desde POS: Se ajusta id en false, type_period y quantity_period se valida que existan para que no falle la creacion<br>
2021-04-15 : fixed | #449 | Api: Guia de Remision: Ejemplo para crear items al momento de enviar la guia: removiendo codigo_tipo_item<br>
2021-04-15 : fixed | #449 | Api: Guia de Remision: Ejemplo para crear items al momento de enviar la guia.<br>
2021-04-15 : fixed | #486 | POS: Nota de venta: Ajuste para mensaje por whatsapp<br>
2021-04-14 : fixed | #463 | visualizacion categorias en pos<br>
2021-04-14 : fixed | #491 | menu header comprobantes no enviados<br>
2021-04-14 : fixed | #485 | Compile JS<br>
2021-04-14 : fixed | #485 | Ingreso de Producto en inventario: Convirtiendo cantidad en numero para comparacion<br>
2021-04-14 : fixed | #485 | Ingreso de Producto en inventario: Convirtiendo cantidad en numero para comparacion<br>
2021-04-13 : fixed | #433 | Compile js<br>
2021-04-12 : fixed | #462 | datos visuales en letras blanco con modo oscuro<br>
2021-04-08 : fixed | #433 | Filtro para buscar por numero de guia en Reportes -> Ventas -> Documentos<br>
2021-04-08 : fixed | #433 | Filtro para buscar por numero de guia en Ventas -> Listado de Comprobantes<br>
2021-04-07 : fixed | #449 | Api: Guia de Remision: Ejemplo para crear items al momento de enviar la guia<br>

### feature
2021-04-14 : feature | i-367 | EMITIR FACTURA DESDE UN SERVICIO DE SOPORTE TÉCNICO<br>

## 4.0.6

### docs
2021-03-24 : docs | changelog<br>


### fixed
2021-04-12 : fixed | #477 | Editar Producto: Fecha de vencimiento en formato Y-m-d H:i:s<br>
2021-04-12 : fixed | #477 | Editar Item: Fecha de vencimiento en formato Y-m-d H:i:s<br>
2021-04-12 : fixed | #477 | Editar Item: Ajuste para guardar la fecha de vencimiento como fecha y no como array<br>
2021-04-12 : fixed | #476 | Ajuste de validacion de configuracion ( #432 ) en el modelo y aplicado a la busqueda<br>
2021-04-09 : fixed | #390 | Packs: Edicion/Nuevo pack, muestra el total y se puede modificar la cantidad de los items que lo componen<br>
2021-04-09 : fixed | #432 | mejoras en la lista de productos, evita duplicados por stock si no se tiene la configuracion habilitada<br>
2021-04-08 : fixed | #475 | GUIA DE REMISION - Cambio de nomenclatura por migracion<br>
2021-04-07 : fixed | #441 | Nota de venta: Ajuste para añadir formas de pago Credito y Contado. Similar a Cotizacion<br>
2021-04-07 : fixed | #441 | Cotizacion: Ajuste de diseño en los campos de fecha y total.<br>
2021-04-07 : fixed | #441 | Cotizacion: Modal al 50%, Se muestra la moneda (currency_type_id) y el total<br>
2021-04-07 : fixed | #441 | Cotizacion: Al momento de generar el comprobante, se selecciona credito o contado. Default Contado.<br>
2021-04-07 : fixed | #432 | Ajuste de texto en el sidebar<br>
2021-04-07 : fixed | #432 | Ajuste de estilo de codigo.<br>
2021-04-06 : fixed | #435 | Ajuste para guardar la fecha en formato Y-m-d y no un array<br>
2021-04-06 : fixed | #393 | error de texto azanvado x avanzado<br>
2021-04-05 : fixed | #414 #301 #277 | Añadido filtro por marcas y categorias como opcional. Ajuste para ordenar por nombre de item<br>
2021-04-03 : fixed | i-418 | Detalle del Item al Generar FACTURA de una Cotización no se Copia<br>
2021-04-03 : fixed | carga de items en movimiento de inventario<br>
2021-04-03 : fixed | carga de items en movimiento de inventario<br>
2021-04-03 : fixed | carga de items en movimiento de inventario<br>
2021-04-03 : fixed | carga de items en movimiento de inventario<br>
2021-04-03 : fixed | i-395 | error en la busqueda al momento de ingresar en el modulo de movimiento<br>
2021-04-03 : fixed | reporte de inventario<br>
2021-04-03 : fixed | reporte de inventario<br>
2021-04-03 : fixed | reporte de inventario<br>
2021-04-03 : fixed | reporte de inventario<br>
2021-04-03 : fixed | reporte de inventario<br>
2021-04-03 : fixed | reporte de inventario<br>
2021-03-31 : fixed | i-1 | Removiendo "ELECTRÓNICA" de "GUIA DE REMISIÓN REMITENTE ELECTRÓNICA"<br>
2021-03-31 : fixed | #448 | stock 0 para servicios<br>
2021-03-31 : fixed | #458 | boton permite duplicados<br>
2021-03-30 : fixed | campos giro grifo activo form de venta<br>
2021-03-30 : fixed | campos de informacion adicional en formulario de venta<br>
2021-03-30 : fixed | observaciones en formulario de venta<br>
2021-03-30 : fixed | t-373 | Corregir edición items en cotizaciones luego de ser generadas, así como en ventas<br>
2021-03-29 : fixed | direcciones de cliente en form de venta<br>
2021-03-29 : fixed | direcciones de cliente en form de venta<br>
2021-03-29 : fixed | direcciones de cliente en formulario de venta<br>
2021-03-26 : fixed | #387 | solución al error en reporte de caja, montos a credito no aparecian<br>
2021-03-25 : fixed | js<br>
2021-03-25 : fixed | compilado<br>
2021-03-25 : fixed | #420 | cierre inesperado del popup de pagos en notas de venta, solucionado<br>
2021-03-25 : fixed | #327 | bug de permisos, usuarios secundarios pueden entrar a modulos no asignados, reparado}<br>
2021-03-25 : fixed | #233 | se eliminó la duplicidad del nombre del vendedor en los pdfs de cotizaciones<br>
2021-03-24 : fixed | #327 | se ocultaron los modules y componentes no otorgados a la empresa al momento de editar un usuario secundario<br>
2021-03-24 : fixed | #306 | se corrigieron los detalles del buscador de clientes en el popup de generar cpe desde multiples guías y se agrego el número del comprobante en la plantilla blank<br>


### feature
2021-04-11 : feature | #233 | se agrego el cuadro para cambiar la fecha de pago cuando se genera un comprobante a partir de una cotización<br>
2021-04-08 : feature | cambio de url para consulta interna la sunat<br>
2021-04-07 : feature | #439 | se agregó el modod cargando en las opciones de los cpe<br>
2021-04-06 : feature | detalles dashboard<br>
2021-04-06 : feature | #443 | Ajuste para nota de venta, Copia de default y ajuste para orden visual de las cuentas<br>
2021-04-06 : feature | #443 | Plantilla PDF basada en default3_new para añadir las cuentas bancarias al final de la factura a4<br>
2021-04-05 : feature | #390 | poder editar la cantidad del producto luego de creado<br>
2021-04-05 : feature | i-436 | Agregó configuración: Permitir editar precio unitario a vendedores<br>
2021-04-05 : feature | i-367 | REQUERIMIENTO PARA PODER EMITIR FACTURA DESDE UN SERVICIO DE SOPORTE TÉCNICO<br>
2021-04-05 : feature | #313 | se agregó la columna cantidad a los cpe generados desde nota de venta<br>
2021-04-05 : feature | #401 | mostrar el precio de venta de producto al editar el precio en las compras<br>
2021-04-02 : feature | i-358 | REPORTE DE PRODUCTOS SIN STOCK Y CON STOCK MÍNIMO<br>
2021-04-01 : feature | #435 | Añadiendo modelo de factura con fecha de vencimiento por producto. Se quita Serie para fecha de vencimiento<br>
2021-04-01 : feature | #285 | módulos y componentes controlados desde el superadmin<br>
2021-03-31 : feature | #313 | generar un cpe desde múltiples notas de ventas. listo<br>
2021-03-31 : feature | #401 | opción de editar el precio de venta de un producto desde las compras fue aadido<br>
2021-03-31 : feature | #423 | template pedido sin mostrar igv<br>
2021-03-29 : feature | #391 | se agregaron los campos tipos de usuario y vendedor y categoría de los productos en el reporte general del productos<br>
2021-03-29 : feature | rediseño posiciones en formulario de venta<br>
2021-03-25 : feature | diseño formulario venta<br>
2021-03-25 : feature | condicion de pago<br>
2021-03-25 : feature | arreglos de diseño sidebar, colores e iconos<br>
2021-03-25 : feature | json retorna url de imagen a apk<br>


## 4.0.5

### docs
2021-03-24 : cambios para ver el google docs viewer<br>
2021-03-10 : docs | changelog<br>


### fixed
2021-03-24 : fixed | https://gitlab.com/carlomagno83/facturadorpro4/-/issues/431 | no listan tareas programadas<br>
2021-03-24 : fixed | margen top en panel admin<br>
2021-03-23 : fixed | i-306 | se ha asociado el documento generado desde multiples guías a sus repectivas guías<br>
2021-03-22 : fixed | t-416 | Requerimiento para Cotizaciones - Referencia<br>
2021-03-19 : fixed | i-327 | error al seleccionar un modulo con hijos arreglado<br>
2021-03-18 : fixed | i-306 | se corrigió el error al crear cpe desde las guías<br>
2021-03-17 : fixed | i-141 | se oculto el boton editar de los cpe<br>
2021-03-17 : fixed | POS | problema con nombres de 50 caracteres<br>
2021-03-17 : fixed | i-141 | error en el campo leyenda al editar un cpe, solucionado<br>
2021-03-16 : fixed | mostrando orden de compra en plantilla full_height<br>
2021-03-15 : fixed | i-392 | se limito la busqueda de productos a los vendedores, ahora solo ven los items de su almacen<br>
2021-03-15 : fixed | i-282 | se modificó la formula para obtener las ganancias<br>
2021-03-12 : fixed | subida de logo en formato jpg<br>
2021-03-10 : fixed | errores varios<br>


### feature
2021-03-24 : feature | i-405 | problema al mostrar el pdf en moviles, arreglado<br>
2021-03-22 : feature | t-338 | Permitir generar comprobante de pago desde cotización a vendedores<br>
2021-03-21 : feature | sidebar espacios visible para submenus<br>
2021-03-21 : feature | header mas uniforme en diseño, cambio de switch, tooltip para todos<br>
2021-03-20 : feature | i-249 | backups independientes por empresas<br>
2021-03-20 : feature | i-327 | se cambió el orden para mostrar de los modulos(en el superadmin y los tenant)<br>
2021-03-19 : feature | i-327 | se agrego una la opción para activar o desactivar las opciones del menu ventas<br>
2021-03-18 : feature | i-261 | se puede cargar plantillas personalizada[*] sin afectacion a las actualizaciones<br>
2021-03-18 : feature | i-380 | telefono y vendedor en nota de venta y guia de remision, plantilla default<br>
2021-03-17 : feature | i-346 | se agrego una ruta más para editar un producto a partir de su id<br>
2021-03-16 : feature | i-306 | se agregó la opción para seleccionar la dirección de llegada dependiendo del cliente seleccionado en las guías de remisión<br>
2021-03-16 : feature | i-365 | reduccion de espacios superiores en formulario de creacion<br>
2021-03-15 : feature | i-369 | el usuario vendedor ya no puede editar el precio de venta de un producto al emitir un cpe<br>
2021-03-15 : feature | i-398 | telefono de cliente en plantilla brand<br>
2021-03-12 : feature | i-306 | se agrego la opción para generar un cpe desde multiples guías de remisión<br>
2021-03-12 : feature | i-306 | se agrego la opción para generar un cpe desde multiples guías de remisi+on<br>
2021-03-11 : feature | i-374 | se eliminó la marca de la descripción del producto cuando se usa la vista lista<br>
2021-03-11 : feature | i-129 | se agrego la plantilla multilples_logos que habilita la opción de imprimir el logo del establecimiento en los cpe<br>


## 4.0.4

### docs
2021-03-04 : docs | Update README.md<br>
2021-03-03 : docs | changelog<br>


### fixed
2021-03-19 : fixed | i-332 | optimización visual del pos
2021-03-10 : fixed | solución al error  logo not found<br>
2021-03-09 : fixed | i-361 | template header_image_full_width<br>
2021-03-08 : fixed | i-220-2 | se eliminó el icono de la frase genear guía<br>
2021-03-03 : fixed | i-363-2 | Se modificó la forma de importar productos, ahora el almacén se selecciona en el popup de importación<br>


### feature
2021-03-22 : feature | i-416 | Requerimiento para Cotizaciones - Referencia
2021-03-10 : feature | template center_note (solo cambia ticket en nota de venta)<br>
2021-03-09 : feature | i-374 | se agrego la marca del producto a la descripción en la sección POS<br>
2021-03-09 : feature | i-381 | se agregó una opción para subir un logo global, tambien una opción para ocultar el logo global, se ocultó la opción de configurar el login en los clientes<br>
2021-03-08 : feature | i-381 | se agregó una opción para configurar el login de los clientes desde el superadmin<br>
2021-03-05 : feature | feature-dashboard-tenants | Se hicieron algunas modificaciones al dashboard y se resolvió el problema de los totales en el dashboard<br>
2021-03-04 : feature | i-220 | se agregó el botón Generar Guía en la sección Reportes/Ventas/Consolidado de items<br>
2021-03-03 : feature | i-368 | se modificó la plantilla datatime, se agregó la hora de creación del pedido en los tickets<br>
2021-03-03 : feature | i-334 | se agregó la opción para agregar un lote a un producto desde las guías de remisión<br>


## 4.0.3

### fixed
2021-03-03 : fixed | i-361 | ocultando descuento en plantilla header_image_full_width<br>
2021-03-02 : fixed | compilando archivo estaticos<br>
2021-03-01 : fixed | a5 brand sale_note<br>
2021-02-24 : fixed | i-345 | La busqueda por rango de fechas se modifico, se dejó de usar la columna created_at por date_of_issue<br>
2021-02-24 : fixed | fixed-colores-sidebar-superadmin | Se modifico el color del menu del superadmin<br>
2021-02-24 : fixed | i-348 | template brand - error tickets y a5 en notas de venta<br>


### feature
2021-03-02 : feature | i-363 | Se agregó la columna almacén a la importación de productos.<br>
2021-03-01 : feature | i-386 | Se editaron las plantillas default, default, default2, default3, default4, font_sm, legend_amazonia y se le agregaron la hora a los reportes de tamaño ticket<br>
2021-03-01 : feature | i-343 | dashboard tenant sin espacio en disco<br>
2021-03-01 : feature | i-361 | cod por dto en plantilla image_full<br>
2021-02-26 : feature | feature-tramite-documentario | Se agrego la vista de crear expedientes<br>
2021-02-25 : feature | feature-sercofi-2 | se separó los servicios de los productos, ahora los servicios tienen su item en el sidebar, además se preselecciona la unidad de medida zz desde el boton crear<br>
2021-02-25 : feature | header Mi empresa<br>
2021-02-24 : feature | i-350 | se agrego el boton editar en las cotizaciones<br>


## 4.0.2

### fixed
2021-02-24 : fixed | i-348 | template brand - error tickets y a5<br>
2021-02-17 : fixed | header de tenant en diferentes opciones<br>


### feature
2021-02-23 : feature | feature-topbar | se agregó el botón de pedidos al topbar, se arreglo el modo responsivo en el topbar y se corrigió la combinación de colores en el sidabr<br>
2021-02-23 : feature | feature-topbar | Se egregó el boton modo oscuro en el header, se cambiaron los colores de los botones del topbar, se cambió los estilos del sidebar en sus diferentes variantes<br>
2021-02-22 : feature | feature-topbar | Cambios visuales en el sidebar, cambios finales, esperando feedback<br>
2021-02-22 : feature | feature-topbar | Cambios visuales en el sidebar, limpiando el fondo blanco del div nano<br>
2021-02-22 : feature | feature-topbar | Cambios visuales en el sidebar, limpiando el fondo blanco<br>
2021-02-22 : feature | feature-topbar | Cambios visuales en el sidebar<br>
2021-02-22 : feature | feature-topbar | Se agrego el sidebar de colores<br>
2021-02-19 : feature | feature-sercofi | Se separaron los filtros en cuentas por pagar y en cuentas por cobrar se agregó el boton mostrar penalidad por mora<br>
2021-02-19 : feature | sidebar estilo paneles oscuros<br>
2021-02-19 : feature | switcher-top color<br>


## 4.0.1

### docs
chagelod

## 4.0.0

### feature
Despliegue de PRO4
