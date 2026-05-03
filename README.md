# Sistema Web de Inventario y Ventas

## Descripción del sistema

Sistema web desarrollado en PHP que permite gestionar productos y registrar ventas. El sistema incluye control de stock, cálculo automático de totales y visualización del historial de ventas con detalle de productos vendidos.

---

## Requisitos

- Sistema operativo Windows
- XAMPP (Apache, PHP y MySQL)
- Navegador web moderno
- Git (opcional)

---

## Instalación de XAMPP, Apache y MySQL

1. Descargar XAMPP desde:
   https://www.apachefriends.org

2. Ejecutar el instalador y seleccionar:
   - Apache
   - MySQL
   - PHP
   - phpMyAdmin

3. Instalar en la ruta:
   C:\xampp

4. Abrir el panel de control de XAMPP

5. Iniciar los servicios:
   - Apache
   - MySQL

6. Verificar instalación en el navegador:
   http://localhost

---

## Pasos para instalación del sistema

1. Clonar o copiar el proyecto en:
   C:\xampp\htdocs\inventario-ventas

2. Abrir phpMyAdmin:
   http://localhost/phpmyadmin

3. Crear base de datos:
   inventario_ventas

4. Importar el archivo:
   database/inventario.sql

5. Acceder al sistema:
   http://localhost/inventario-ventas/productos.php

---

## Funcionalidades

### Gestión de productos
- Crear productos
- Listar productos
- Editar productos
- Eliminar productos

### Módulo de ventas
- Registro de ventas con múltiples productos
- Validación de stock
- Cálculo automático de subtotal y total

### Historial de ventas
- Visualización de ventas realizadas
- Detalle por producto vendido

---

## Usuario de prueba

El sistema no requiere autenticación.

---

## Capturas del sistema

Se deben incluir imágenes de:

- Pantalla de productos
- Formulario de registro de productos
- Registro de ventas con múltiples productos
- Historial de ventas con detalle desplegable
