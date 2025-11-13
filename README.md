# 🚀 NuvemERP - Sistema de Gestión Empresarial

**NuvemERP** es una plataforma completa de gestión empresarial desarrollada con arquitectura **MVC (Model-View-Controller)** en PHP, que integra gestión de clientes, productos y cotizaciones en un sistema unificado. Enfocado en **seguridad avanzada** y mejores prácticas de desarrollo web moderno.

> 🌐 **Proyecto en GitHub**: [https://github.com/wpadillav/NuvemERP.git](https://github.com/wpadillav/NuvemERP.git)

---

## ✨ Características Principales

### 🏢 **Gestión Empresarial Integral**
- **Gestión de Clientes** - CRUD completo con información detallada de contacto
- **Catálogo de Productos** - Control de inventario, precios y SKUs con búsqueda AJAX
- **Sistema de Cotizaciones** - Workflow simplificado: Borrador → Enviada → Entregada
- **Cálculos Automáticos** - IVA opcional, subtotales y totales en tiempo real
- **Dashboard Ejecutivo** - Resumen de actividad y estadísticas clave
- **Numeración Automática** - Sistema COT-YYYY-NNNN para cotizaciones

### 🔐 **Seguridad Avanzada**
- **Autenticación Robusta** - Sistema de login con protección anti-fuerza bruta
- **Cifrado Seguro** - Implementación con libsodium y hash seguros
- **Sistema de Roles** - Control granular de acceso (admin, operador, etc.)
- **Protección CSRF** - Tokens únicos para formularios críticos
- **Sesiones Seguras** - Configuración HTTPOnly, Secure, SameSite
- **Validación Exhaustiva** - Sanitización completa de entradas y salidas

### 🎯 **Experiencia de Usuario**
- **Interfaz Responsive** - Bootstrap 5 optimizado para todos los dispositivos
- **Navegación Intuitiva** - Menú contextual adaptativo según roles
- **Feedback Visual** - Mensajes claros de éxito/error y confirmaciones
- **Carga Rápida** - AJAX para operaciones sin recarga de página
- **Workflow Simplificado** - Procesos optimizados para productividad

---

## ⚙️ Tecnologías Utilizadas

| Tecnología | Versión | Propósito |
|------------|---------|-----------|
| **PHP** | 8.3+ | Backend y lógica de negocio |
| **MariaDB** | 10.11+ | Base de datos relacional |
| **Apache** | 2.4 | Servidor web con mod_security |
| **Bootstrap** | 5.x | Framework CSS responsive |
| **jQuery** | 3.x | Biblioteca JavaScript para AJAX |
| **Font Awesome** | 6.x | Iconografía moderna |
| **Composer** | Latest | Gestión de dependencias |
| **PHPDotEnv** | 5.6+ | Gestión de variables de entorno |

---

## 📁 Estructura del Proyecto

```
NuvemERP/
├── assets/                     # Recursos estáticos
│   ├── css/bootstrap.min.css   # Framework CSS
│   ├── img/                    # Imágenes del sistema
│   └── js/                     # JavaScript (Bootstrap, jQuery)
├── config/                     # Configuraciones del sistema
│   ├── database.php            # Configuración de base de datos
│   └── security.php            # Configuraciones de seguridad
├── controllers/                # Controladores MVC
│   ├── AuthController.php      # Autenticación y sesiones
│   ├── ClientController.php    # Gestión de clientes
│   ├── DashboardController.php # Panel principal
│   ├── ProductController.php   # Gestión de productos
│   ├── QuoteController.php     # Sistema de cotizaciones
│   ├── ProfileController.php   # Perfil de usuario
│   ├── ToolsController.php     # Herramientas del sistema
│   └── UserAdminController.php # Administración de usuarios
├── models/                     # Modelos de datos
│   ├── Database.php            # Conexión a base de datos
│   ├── ClientModel.php         # Lógica de clientes
│   ├── ProductModel.php        # Lógica de productos
│   ├── QuoteModel.php          # Lógica de cotizaciones
│   ├── UserModel.php           # Gestión de usuarios
│   └── EncryptionModel.php     # Modelo de cifrado
├── views/                      # Vistas del sistema
│   ├── auth/                   # Formularios de autenticación
│   ├── clients/                # Interfaces de clientes
│   ├── products/               # Interfaces de productos
│   ├── quotes/                 # Interfaces de cotizaciones
│   ├── dashboard/              # Panel de control
│   ├── profile/                # Perfil de usuario
│   ├── tools/                  # Herramientas
│   ├── useradmin/              # Administración de usuarios
│   └── components/             # Componentes reutilizables
├── vendor/                     # Dependencias de Composer
├── .env                        # Variables de entorno
├── composer.json               # Configuración de dependencias
├── index.php                   # Punto de entrada principal
└── README.md                   # Documentación del proyecto
```

---

## 🛠️ Requisitos del Sistema

### **Requisitos Mínimos**
- **PHP 8.0+** con extensiones:
  - `php-mysql` (PDO MySQL)
  - `php-json` (manejo de JSON)
  - `php-mbstring` (manejo de cadenas)
  - `php-libsodium` (operaciones criptográficas)
- **Apache 2.4** con módulos:
  - `mod_rewrite` (URL amigables)
  - `mod_ssl` (HTTPS recomendado)
- **MySQL 5.7+** o **MariaDB 10.2+**
- **Composer** para gestión de dependencias

### **Requisitos Recomendados**
- **PHP 8.3+** para máximo rendimiento
- **MariaDB 10.11+** con configuración UTF8MB4
- **Apache 2.4 con HTTPS** configurado
- **mod_security** activado para seguridad adicional

---

## 🚀 Instalación y Configuración

### **1. Clonar el Repositorio**
```bash
git clone https://github.com/wpadillav/NuvemERP.git
cd NuvemERP
```

### **2. Instalar Dependencias**
```bash
composer install
```

### **3. Configurar Variables de Entorno**
```bash
cp .env.example .env
nano .env
```

Configurar las variables necesarias:
```env
# Base de datos
DB_HOST=localhost
DB_NAME=gestion_db
DB_USER=tu_usuario
DB_PASS=tu_password

# Clave de aplicación (generar con: php -r "echo bin2hex(random_bytes(32))";)
APP_SECRET_KEY=tu_clave_hexadecimal_segura
```

### **4. Crear Base de Datos**
```sql
-- Crear base de datos
CREATE DATABASE gestion_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE gestion_db;

-- Crear tablas principales
CREATE TABLE users (
    id INT AUTO_INCREMENT PRIMARY KEY,
    username VARCHAR(50) NOT NULL UNIQUE,
    password_hash VARCHAR(255) NOT NULL,
    salt VARCHAR(255) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE clients (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    email VARCHAR(255),
    phone VARCHAR(50),
    address TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE products (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    name VARCHAR(255) NOT NULL,
    description TEXT,
    price DECIMAL(10,2) NOT NULL DEFAULT 0.00,
    sku VARCHAR(100),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
);

CREATE TABLE quotes (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_id INT NOT NULL,
    client_id INT NOT NULL,
    quote_number VARCHAR(50) NOT NULL,
    status ENUM('draft', 'sent', 'delivered') DEFAULT 'draft',
    issue_date DATE NOT NULL,
    valid_until DATE,
    subtotal DECIMAL(10,2) DEFAULT 0.00,
    tax_rate DECIMAL(5,2) DEFAULT 19.00,
    apply_tax BOOLEAN DEFAULT TRUE,
    tax_amount DECIMAL(10,2) DEFAULT 0.00,
    total DECIMAL(10,2) DEFAULT 0.00,
    notes TEXT,
    terms_conditions TEXT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    FOREIGN KEY (client_id) REFERENCES clients(id) ON DELETE CASCADE
);

CREATE TABLE quote_items (
    id INT AUTO_INCREMENT PRIMARY KEY,
    quote_id INT NOT NULL,
    product_id INT NOT NULL,
    quantity INT NOT NULL DEFAULT 1,
    unit_price DECIMAL(10,2) NOT NULL,
    discount_percentage DECIMAL(5,2) DEFAULT 0.00,
    subtotal DECIMAL(10,2) NOT NULL,
    FOREIGN KEY (quote_id) REFERENCES quotes(id) ON DELETE CASCADE,
    FOREIGN KEY (product_id) REFERENCES products(id) ON DELETE CASCADE
);

-- Insertar usuario administrador inicial
INSERT INTO users (username, password_hash, salt) VALUES 
('admin', '283dfa5977c8cfe8c881405e33f576d5', 'c8ad2a055b0067efd43c1eb873606bb06f16b548531b970c771b1aab4172f038');
```

### **5. Configurar Apache**
```apache
<VirtualHost *:80>
    ServerName tu-dominio.com
    DocumentRoot /var/www/html/NuvemERP
    
    <Directory /var/www/html/NuvemERP>
        AllowOverride All
        Require all granted
    </Directory>
    
    # Redirección automática a HTTPS
    Redirect permanent / https://tu-dominio.com/
</VirtualHost>

<VirtualHost *:443>
    ServerName tu-dominio.com
    DocumentRoot /var/www/html/NuvemERP
    
    SSLEngine on
    SSLCertificateFile /path/to/certificate.crt
    SSLCertificateKeyFile /path/to/private.key
    
    <Directory /var/www/html/NuvemERP>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### **6. Configurar Permisos**
```bash
sudo chown -R www-data:www-data /var/www/html/NuvemERP
sudo chmod -R 755 /var/www/html/NuvemERP
sudo chmod 600 .env  # Proteger variables de entorno
```

---

## 🎯 Funcionalidades del Sistema

### **📊 Dashboard Ejecutivo**
- Resumen de actividad empresarial con métricas clave
- Estadísticas de cotizaciones por estado
- Valor total de ventas y promedios
- Accesos rápidos a funcionalidades principales

### **👥 Gestión de Clientes**
- **Crear**: Formulario completo con validación
- **Listar**: Vista tabular con búsqueda y filtros avanzados
- **Editar**: Actualización de información con historial
- **Eliminar**: Soft delete con confirmación de seguridad

### **📦 Gestión de Productos**
- **Catálogo Completo**: Lista con precios, SKU y descripciones
- **Búsqueda AJAX**: Localización instantánea para cotizaciones
- **Control de Inventario**: Seguimiento básico de productos
- **Integración Directa**: Conexión automática con cotizaciones

### **📋 Sistema de Cotizaciones**
- **Workflow Simplificado**: 3 estados claros (Borrador → Enviada → Entregada)
- **Creación Interactiva**: Agregar múltiples productos con búsqueda
- **Cálculos Automáticos**: Subtotales, IVA opcional y totales en tiempo real
- **IVA Opcional**: Checkbox para empresas no obligadas
- **Numeración Automática**: Sistema COT-YYYY-NNNN
- **Control de Edición**: Permisos según estado de cotización
- **Generación PDF**: Documentos profesionales para envío

### **🔧 Administración del Sistema**
- **Gestión de Usuarios**: Panel completo para administradores
- **Sistema de Roles**: Control granular de acceso
- **Perfil Personal**: Cambio de contraseña y datos personales
- **Herramientas**: Funcionalidades adicionales del sistema

---

## 🔐 Características de Seguridad Detalladas

### **Autenticación Robusta**
- Sistema de login con validación server-side
- Protección contra ataques de fuerza bruta (5 intentos máximo)
- Bloqueo temporal de 300 segundos tras intentos fallidos
- Regeneración de ID de sesión tras login exitoso
- Logout seguro con limpieza completa de sesión

### **Cifrado y Protección de Datos**
- **Cifrado de contraseñas**: Hash seguros con salt único por usuario
- **Prepared statements**: Prevención 100% de SQL Injection
- **Sanitización de entrada**: `filter_var()` en todos los inputs
- **Escapado de salida**: `htmlspecialchars()` en todas las vistas
- **Validación exhaustiva**: Controles server-side en formularios

### **Configuración Segura**
- **Cookies seguras**: Flags HTTPOnly, Secure, SameSite=Strict
- **Headers de seguridad**: Configuración avanzada de Apache
- **Variables de entorno**: Credenciales protegidas en .env
- **Timeouts apropiados**: Sesiones con expiración controlada
- **Logging de eventos**: Registro de acciones críticas

### **Protección CSRF**
- Tokens únicos generados con `random_bytes(32)`
- Validación automática en formularios POST
- Comparación segura con `hash_equals()`
- Regeneración por sesión para máxima seguridad

---

## 📊 Sistema de Roles y Permisos

| Rol | Descripción | Permisos |
|-----|-------------|----------|
| **Admin** | Administrador general | Acceso completo al sistema, gestión de usuarios |
| **Operador** | Usuario operativo | Gestión de clientes, productos y cotizaciones |
| **Visor** | Solo lectura | Visualización de datos sin modificación |

### **Control de Acceso**
- Verificación automática en todos los controladores
- Middleware de autenticación obligatorio
- Prevención de escalada de privilegios
- Navegación adaptativa según permisos del usuario

---

## 🎯 Uso del Sistema

### **Acceso Inicial**
```
URL: https://tu-dominio.com
Usuario: admin
Contraseña: admin123
```

### **Flujo de Trabajo Principal**

1. **Login Seguro** - Autenticación con protección anti-fuerza bruta
2. **Dashboard** - Vista general de la actividad empresarial
3. **Gestión de Clientes** - Crear y mantener base de datos de clientes
4. **Catálogo de Productos** - Mantener inventario actualizado
5. **Crear Cotizaciones** - Proceso interactivo con cálculos automáticos
6. **Seguimiento** - Monitoreo del estado de cotizaciones
7. **Entrega** - Marcado de cotizaciones como entregadas

---

## 🎨 Personalización

### **Configurar Branding Empresarial**
```php
// En views/components/nav.php
<a class="navbar-brand" href="/?action=dashboard">
    <i class="fas fa-building"></i> Tu Empresa
</a>
```

### **Ajustar Configuración de IVA**
```php
// En controladores de productos y cotizaciones
$taxRate = 19; // Cambiar por tu tasa de impuestos local
```

### **Personalizar Colores y Estilo**
```css
/* Crear assets/css/custom.css */
:root {
    --primary-color: #007bff;
    --secondary-color: #6c757d;
    --success-color: #28a745;
    --danger-color: #dc3545;
}
```

---

## 🚦 Estado del Proyecto

| Módulo | Estado | Descripción |
|--------|--------|-------------|
| ✅ **Autenticación** | Completo | Sistema seguro con roles |
| ✅ **Clientes** | Completo | CRUD completo funcional |
| ✅ **Productos** | Completo | Catálogo con búsqueda |
| ✅ **Cotizaciones** | Completo | Workflow simplificado |
| ✅ **Dashboard** | Completo | Estadísticas y métricas |
| ✅ **Seguridad** | Completo | Implementación robusta |
| 🔄 **Reportes** | En desarrollo | Informes avanzados |
| 🔄 **Facturación** | Planificado | Sistema de facturas |
| 🔄 **API REST** | Planificado | Integración externa |

---

## 📈 Monitoreo y Logs

### **Eventos de Seguridad Monitoreados**
- Intentos de login fallidos
- Accesos no autorizados
- Tokens CSRF inválidos
- Errores de autenticación
- Cambios de contraseña
- Creación/eliminación de usuarios

### **Ubicación de Logs**
```bash
# Logs de Apache
/var/log/apache2/error.log

# Logs de PHP
/var/log/php_errors.log

# Ver logs en tiempo real
sudo tail -f /var/log/apache2/error.log | grep "NuvemERP"
```

---

## 🤝 Contribuciones

### **Cómo Contribuir**
1. Fork el proyecto
2. Crea una rama feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit tus cambios (`git commit -am 'Agregar nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Crea un Pull Request

### **Estándares de Código**
- **PSR-12** para estilo de código PHP
- **Comentarios en español** para documentación
- **Validación de seguridad** obligatoria
- **Pruebas exhaustivas** antes de envío

---

## 📞 Soporte

### **¿Encontraste un problema?**
- 📋 Reporta en: [GitHub Issues](https://github.com/wpadillav/NuvemERP/issues)
- 📝 Incluye detalles del error y pasos para reproducir
- 🖼️ Screenshots si es posible

### **¿Necesitas ayuda?**
- 📖 Revisa esta documentación completa
- 💬 Consulta los ejemplos de uso
- 📧 Contacta al desarrollador

---

## 👤 Autor

**William Padilla** - Desarrollador Full Stack

- 👨‍💻 **GitHub**: [@wpadillav](https://github.com/wpadillav)
- 📧 **Email**: willipadilla@proton.me
- 🌐 **Perfil**: [github.com/wpadillav](https://github.com/wpadillav)

---

## ⚖️ Licencia

Este proyecto está bajo la **Licencia MIT**. Ver [LICENSE](LICENSE) para detalles completos.

```
MIT License - Copyright (c) 2025 William Padilla

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.
```

---

## 🌟 Agradecimientos

- **OWASP** por las guías de seguridad web
- **PHP Security Consortium** por las mejores prácticas
- **Bootstrap Team** por el framework CSS excepcional
- **Font Awesome** por la iconografía moderna
- **jQuery Team** por la biblioteca JavaScript
- **Comunidad PHP** por las herramientas y soporte
- **MariaDB Foundation** por el sistema de base de datos

---

<div align="center">

**Desarrollado con ❤️ para simplificar la gestión empresarial**

*NuvemERP - Tu ERP seguro en la nube desde 2025*

[![Made with PHP](https://img.shields.io/badge/Made%20with-PHP-777BB4.svg)](https://php.net/)
[![Database](https://img.shields.io/badge/Database-MariaDB-003545.svg)](https://mariadb.org/)
[![Framework](https://img.shields.io/badge/CSS-Bootstrap%205-7952B3.svg)](https://getbootstrap.com/)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)

</div>