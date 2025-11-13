# 🚀 NuvemERP# � NuvemERP



**NuvemERP** es una plataforma de gestión empresarial en la nube que integra clientes, productos y mucho más en un solo lugar. Diseñada para empresas y personas que buscan simplicidad, control y crecimiento, NuvemERP permite administrar tus operaciones comerciales de forma ágil, segura y desde cualquier dispositivo.



> 🌐 **Proyecto en GitHub**: [https://github.com/wpadillav/NuvemERP.git](https://github.com/wpadillav/NuvemERP.git)**NuvemERP** es una plataforma de gestión empresarial en la nube que integra clientes, productos y mucho más en un solo lugar. Diseñada para empresas y personas que buscan simplicidad, control y crecimiento, NuvemERP permite administrar tus operaciones comerciales de forma ágil, segura y desde cualquier dispositivo.



---



## ✨ Características Principales> 🌐 **Proyecto en GitHub**: [https://github.com/wpadillav/NuvemERP.git](https://github.com/wpadillav/NuvemERP.git)



### 🏢 **Gestión Empresarial Integral**

- **Gestión de Clientes** - CRUD completo con información detallada

- **Catálogo de Productos** - Control de inventario y precios## ✨ Características Principales---

- **Sistema de Cotizaciones** - Creación, edición y seguimiento de cotizaciones

- **Cálculos Automáticos** - IVA, subtotales y totales en tiempo real

- **Búsqueda Inteligente** - Localización rápida de productos por AJAX

### 🔐 **Seguridad Avanzada**### ⚙️ Tecnologías utilizadas

### 🔐 **Seguridad Empresarial**

- **Autenticación Segura** - Sistema de login con protección anti-fuerza bruta- **Cifrado PBKDF2 + SHA-256** con 100,000 iteraciones

- **Gestión de Sesiones** - Configuración segura con cookies HTTPOnly

- **Validación de Datos** - Sanitización completa de entradas- **Salt único** de 32 bytes por cada contraseña* **PHP 8.3** (`php8.3-fpm`, `php8.3-mysql`, `libapache2-mod-php8.3`)

- **Protección CSRF** - Tokens únicos para formularios críticos

- **Protección contra timing attacks** usando `sodium_memcmp()`* **MariaDB 10.11**

### 🎨 **Experiencia de Usuario**

- **Interfaz Responsive** - Bootstrap 5 optimizado para móviles y escritorio- **Prevención de ataques de fuerza bruta** (5 intentos, bloqueo temporal)* **Apache 2.4 con HTTPS (SSL/TLS)**

- **Navegación Intuitiva** - Menú contextual y breadcrumbs

- **Feedback Visual** - Mensajes de éxito/error y confirmaciones- **Protección CSRF** con tokens únicos por sesión* **mod\_security** activado

- **Carga Rápida** - AJAX para operaciones sin recarga de página

- **Configuración segura de sesiones** (HTTPOnly, Secure, SameSite)* **libsodium** (`libsodium-dev`) para cifrado moderno

---

* **Composer** para gestión de dependencias

## ⚙️ Tecnologías Utilizadas

### 👥 **Gestión Completa de Usuarios*** **Bootstrap 5** para la interfaz

| Tecnología | Versión | Propósito |

|------------|---------|-----------|- Sistema de **roles y permisos** granular (root, admin, admingrupo, operador)

| **PHP** | 8.4+ | Backend y lógica de negocio |

| **MariaDB** | 10.11+ | Base de datos relacional |- **Panel de administración** para gestión de usuarios (solo admin/root)---

| **Apache** | 2.4 | Servidor web |

| **Bootstrap** | 5.x | Framework CSS responsive |- **Perfil de usuario** con cambio de contraseña personal

| **jQuery** | 3.x | Biblioteca JavaScript para AJAX |

| **Font Awesome** | 6.x | Iconografía moderna |- **CRUD completo** (Crear, Leer, Actualizar, Eliminar usuarios)### 📁 Estructura del proyecto

| **Composer** | - | Gestión de dependencias |

- **Validación exhaustiva** de datos de entrada

---

```

## 📁 Estructura del Proyecto

### 🏗️ **Arquitectura MVC**.

```

NuvemERP/- **Separación clara** de responsabilidades├── assets/           # Estáticos (CSS, JS, imágenes)

├── 📂 assets/                     # Recursos estáticos

│   ├── css/- **Controladores** especializados por funcionalidad├── config/           # Archivos de configuración (.env, BD, seguridad)

│   │   └── bootstrap.min.css      # Framework CSS

│   ├── img/                       # Imágenes del sistema- **Modelos** con abstracción de base de datos├── controllers/      # Controladores MVC

│   └── js/

│       ├── bootstrap.bundle.min.js # Bootstrap JavaScript- **Vistas** modulares y reutilizables con Bootstrap├── models/           # Lógica de datos, cifrado y usuarios

│       └── jquery.min.js          # jQuery

├── 📂 config/                     # Configuraciones- **Enrutamiento** centralizado y seguro├── views/            # Vistas del sistema

│   ├── database.php               # Configuración de BD

│   └── security.php               # Configuraciones de seguridad├── components/       # Fragmentos reutilizables (navbar, etc.)

├── 📂 controllers/                # Controladores MVC

│   ├── AuthController.php         # Autenticación---├── index.php         # Punto de entrada (Front Controller)

│   ├── ClientController.php       # Gestión de clientes

│   ├── DashboardController.php    # Panel principal├── .env              # Variables de entorno

│   ├── ProductController.php      # Gestión de productos

│   ├── QuoteController.php        # Gestión de cotizaciones## ⚙️ Tecnologías Utilizadas├── .env.example      # Plantilla base para `.env`

│   └── UserAdminController.php    # Administración de usuarios

├── 📂 models/                     # Modelos de datos├── composer.json     # Dependencias del proyecto

│   ├── Database.php               # Conexión a BD

│   ├── ClientModel.php            # Modelo de clientes| Tecnología | Versión | Propósito |└── vendor/           # Librerías Composer (ignorado por Git)

│   ├── ProductModel.php           # Modelo de productos

│   ├── QuoteModel.php             # Modelo de cotizaciones|------------|---------|-----------|```

│   └── UserModel.php              # Modelo de usuarios

├── 📂 views/                      # Vistas del sistema| **PHP** | 7.4+ | Lenguaje backend principal |

│   ├── auth/                      # Autenticación

│   ├── clients/                   # Gestión de clientes| **Apache** | 2.4 | Servidor web |---

│   ├── products/                  # Gestión de productos

│   ├── quotes/                    # Gestión de cotizaciones| **MySQL/MariaDB** | 5.7+ | Base de datos relacional |

│   └── components/                # Componentes reutilizables

├── 📂 vendor/                     # Dependencias de Composer| **PDO** | - | Abstracción de base de datos |### 🛠 Requisitos

├── composer.json                  # Configuración de dependencias

├── index.php                      # Punto de entrada principal| **Bootstrap** | 5.x | Framework CSS responsive |

└── README.md                      # Documentación

```| **Libsodium** | - | Operaciones criptográficas seguras |* Ubuntu 24.04 LTS



---| **Composer** | - | Gestión de dependencias |* Apache 2.4 con SSL y `mod_rewrite`, `mod_security`



## 🛠️ Requisitos del Sistema| **jQuery** | 3.x | Biblioteca JavaScript |* PHP 8.3 (`fpm`, `mysql`, `libsodium`)



### **Requisitos Mínimos*** MariaDB

- **PHP 8.0+** con extensiones:

  - `php-mysql` (PDO MySQL)---* Composer

  - `php-json` (manejo de JSON)

  - `php-mbstring` (manejo de cadenas)

- **Apache 2.4** con módulos:

  - `mod_rewrite` (URL amigables)## 📁 Estructura del Proyecto---

  - `mod_ssl` (HTTPS recomendado)

- **MySQL 5.7+** o **MariaDB 10.2+**

- **Composer** para gestión de dependencias

```### 🚀 Instalación paso a paso

### **Requisitos Recomendados**

- **PHP 8.4+** para mejor rendimientophp-mvc-seguro/

- **MariaDB 10.11+** para características avanzadas

- **Apache con HTTPS** configurado├── 📂 assets/                    # Recursos estáticos1. **Clona el repositorio:**

- **mod_security** para seguridad adicional

│   ├── css/

---

│   │   └── bootstrap.min.css     # Framework CSS```bash

## 🚀 Instalación y Configuración

│   ├── img/git clone https://github.com/wpadillav/php-mvc-seguro.git

### **1. Clonar el Repositorio**

```bash│   │   └── favicon.ico           # Icono del sitiocd php-mvc-seguro

git clone https://github.com/wpadillav/NuvemERP.git

cd NuvemERP│   └── js/```

```

│       ├── bootstrap.bundle.min.js # Bootstrap JavaScript

### **2. Instalar Dependencias**

```bash│       └── jquery.min.js         # jQuery2. **Instala dependencias:**

composer install

```├── 📂 config/                    # Configuraciones del sistema



### **3. Configurar Base de Datos**│   ├── database.php              # Configuración de base de datos```bash



**Crear base de datos:**│   └── security.php              # Configuraciones de seguridadcomposer install

```sql

CREATE DATABASE gestion_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;├── 📂 controllers/               # Controladores MVC```

USE gestion_db;

```│   ├── AuthController.php        # Autenticación y sesiones



**Importar estructura:**│   ├── DashboardController.php   # Panel principal3. **Copia el archivo `.env` de ejemplo y edítalo:**

```bash

mysql -u root -p gestion_db < backup_appdb.sql│   ├── ProfileController.php     # Gestión de perfil personal

```

│   ├── ToolsController.php       # Herramientas del sistema```bash

### **4. Configurar Conexión**

Editar `config/database.php` con tus credenciales:│   └── UserAdminController.php   # Administración de usuarioscp .env.example .env

```php

private $host = 'localhost';├── 📂 models/                    # Modelos de datosnano .env

private $db_name = 'gestion_db';

private $username = 'tu_usuario_mysql';│   ├── Database.php              # Clase de conexión a BD```

private $password = 'tu_password_mysql';

```│   ├── EncryptionModel.php       # Modelo de cifrado



### **5. Configurar Apache**│   └── UserModel.php             # Gestión de usuarios y rolesRellena con tus datos de conexión y una clave segura:

```apache

<VirtualHost *:80>├── 📂 views/                     # Vistas y templates

    ServerName tu-dominio.com

    DocumentRoot /var/www/html/NuvemERP│   ├── auth/```env

    

    <Directory /var/www/html/NuvemERP>│   │   └── login.php             # Formulario de loginAPP_SECRET_KEY=tu_clave_hexadecimal_segura

        AllowOverride All

        Require all granted│   ├── components/```

    </Directory>

</VirtualHost>│   │   └── nav.php               # Barra de navegación

```

│   ├── dashboard/Genera una clave con:

### **6. Datos de Acceso Inicial**

```│   │   └── index.php             # Dashboard principal

URL: http://tu-dominio.com

Usuario: usuario│   ├── profile/```bash

Contraseña: password123

```│   │   ├── change_password.php   # Cambio de contraseña personalphp -r "echo bin2hex(random_bytes(32)) . PHP_EOL;"



---│   │   └── index.php             # Perfil de usuario```



## 🎯 Módulos del Sistema│   ├── tools/



### **📊 Dashboard**│   │   └── index.php             # Herramientas del sistema4. **Crea la base de datos y tabla de usuarios:**

- Resumen ejecutivo de la actividad empresarial

- Estadísticas de clientes, productos y cotizaciones│   └── useradmin/

- Accesos rápidos a funcionalidades principales

│       ├── change_password.php   # Cambio de contraseña admin```sql

### **👥 Gestión de Clientes**

- **Crear**: Formulario completo con validación│       ├── create.php            # Crear usuarioCREATE DATABASE app_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

- **Listar**: Vista tabular con búsqueda y filtros

- **Editar**: Actualización de información existente│       ├── delete.php            # Eliminar usuario

- **Eliminar**: Soft delete con confirmación

│       ├── edit.php              # Editar usuarioUSE app_db;

### **📦 Gestión de Productos**

- **Catálogo**: Lista completa con precios y SKU│       └── index.php             # Lista de usuarios

- **Búsqueda AJAX**: Localización instantánea

- **CRUD Completo**: Crear, editar y eliminar productos├── 📂 vendor/                    # Dependencias de ComposerCREATE TABLE users (

- **Integración**: Conexión directa con cotizaciones

├── composer.json                 # Dependencias de Composer  id INT AUTO_INCREMENT PRIMARY KEY,

### **📋 Sistema de Cotizaciones**

- **Creación Interactiva**: Agregar múltiples productos├── composer.lock                 # Versiones bloqueadas  username VARCHAR(50) NOT NULL UNIQUE,

- **Cálculos Automáticos**: Subtotales, IVA y totales

- **Estados**: Borrador, Enviada, Aprobada, Rechazada├── index.php                     # Punto de entrada principal  password_hash VARCHAR(255) NOT NULL,

- **Numeración**: Sistema automático COT-YYYY-NNNN

- **Edición**: Modificación completa post-creación├── LICENSE                       # Licencia MIT  salt VARCHAR(255) NOT NULL,



---└── README.md                     # Documentación del proyecto  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP



## 🔐 Características de Seguridad```);



### **Autenticación Robusta**

- Sistema de login con validación server-side

- Protección contra ataques de fuerza bruta---INSERT INTO users (username, password_hash, salt) VALUES ('demo_user1', '283dfa5977c8cfe8c881405e33f576d5', 'c8ad2a055b0067efd43c1eb873606bb06f16b548531b970c771b1aab4172f038');

- Regeneración de ID de sesión tras login exitoso

- Logout seguro con limpieza de sesión```



### **Protección de Datos**## 🛠️ Requisitos del Sistema

- Prepared statements para prevenir SQL Injection

- Sanitización de entrada con `filter_var()`**Datos de Acceso**

- Escapado de salida con `htmlspecialchars()`

- Validación exhaustiva en todos los formularios### **Requisitos Mínimos**



### **Configuración Segura**- **PHP 7.4+** con extensiones:```

- Cookies con flags `HttpOnly`, `Secure`, `SameSite`

- Headers de seguridad configurados  - `php-mysql` (PDO MySQL)Usuario: demo_user

- Timeouts de sesión apropiados

- Logging de eventos críticos  - `php-libsodium` (operaciones criptográficas)Password: demo_password123



---  - `php-json` (manejo de JSON)```



## 📱 Capturas de Pantalla- **Apache 2.4** con módulos:



### Dashboard Principal  - `mod_rewrite` (URL amigables)### 🔐 Seguridad aplicada

![Dashboard](assets/img/dashboard-preview.png)

  - `mod_ssl` (HTTPS recomendado)

### Gestión de Cotizaciones

![Cotizaciones](assets/img/quotes-preview.png)- **MySQL 5.7+** o **MariaDB 10.2+*** ✅ HTTPS forzado (`security.php` + Apache redirect)



### Sistema de Productos- **Composer** para gestión de dependencias* ✅ `libsodium` para cifrado seguro simétrico

![Productos](assets/img/products-preview.png)

* ✅ Protección CSRF en formularios

---

### **Requisitos Recomendados*** ✅ Rate limiting por sesión en herramientas

## 🎨 Personalización

- **PHP 8.1+** para mejor rendimiento* ✅ Regeneración de ID de sesión

### **Configurar Logo y Branding**

```php- **Apache 2.4** con HTTPS configurado* ✅ Cookies con flags `Secure`, `HttpOnly`, `SameSite=Strict`

// En views/components/nav.php

<a class="navbar-brand" href="/?controller=Dashboard&action=index">- **MariaDB 10.6+** para mejor compatibilidad* ✅ Escapado de salida (`htmlspecialchars`)

    <i class="fas fa-cloud"></i> Tu Empresa

</a>- **mod_security** para seguridad adicional* ✅ mod\_security activado (Apache)

```



### **Modificar Configuración de IVA**

```php------

// En controllers (productos y cotizaciones)

$taxRate = 19; // Cambiar por tu tasa de impuestos

```

## 🚀 Instalación y Configuración### 🖥️ Apache VirtualHost (resumen)

### **Personalizar Colores**

```css

/* En assets/css/custom.css */

:root {### **1. Clonar el Repositorio****Redirección HTTP a HTTPS:**

    --primary-color: #007bff;

    --secondary-color: #6c757d;```bash

    --success-color: #28a745;

}git clone https://github.com/wpadillav/php-mvc-seguro.git```apache

```

cd php-mvc-seguro<VirtualHost *:80>

---

```    ServerName localhost

## 🚦 Estado del Proyecto

    Redirect permanent / https://localhost/

- ✅ **Autenticación de usuarios**

- ✅ **Gestión completa de clientes**### **2. Instalar Dependencias**</VirtualHost>

- ✅ **Catálogo de productos**

- ✅ **Sistema de cotizaciones**```bash```

- ✅ **Cálculos automáticos**

- ✅ **Búsqueda AJAX**composer install

- ✅ **Interfaz responsive**

- 🔄 **Reportes y estadísticas** (En desarrollo)```**VirtualHost HTTPS (default-ssl.conf):**

- 🔄 **Sistema de facturación** (Próximamente)

- 🔄 **API REST** (Próximamente)



---### **3. Configurar Base de Datos**```apache



## 🤝 Contribuciones<VirtualHost _default_:443>



¡Las contribuciones son bienvenidas! Por favor:**Crear base de datos:**    ServerAdmin webmaster@localhost



1. Fork el proyecto```sql    ServerName localhost

2. Crea una rama para tu feature (`git checkout -b feature/nueva-funcionalidad`)

3. Commit tus cambios (`git commit -am 'Agregar nueva funcionalidad'`)CREATE DATABASE app_db CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;    DocumentRoot /var/www/html

4. Push a la rama (`git push origin feature/nueva-funcionalidad`)

5. Crea un Pull RequestUSE app_db;



---```    SSLEngine on



## 📞 Soporte    SSLCertificateFile    /etc/ssl/certs/apache-selfsigned.crt



### **¿Encontraste un bug?****Crear estructura de tablas:**    SSLCertificateKeyFile /etc/ssl/private/apache-selfsigned.key

- Reporta issues en: [GitHub Issues](https://github.com/wpadillav/NuvemERP/issues)

- Incluye detalles del error y pasos para reproducir```sql</VirtualHost>



### **¿Necesitas ayuda?**-- Tabla de usuarios```

- Revisa la documentación completa

- Consulta los ejemplos de usoCREATE TABLE users (

- Contacta al desarrollador

    id INT PRIMARY KEY AUTO_INCREMENT,---

---

    username VARCHAR(50) UNIQUE NOT NULL,

## 👤 Autor

    password_hash VARCHAR(128) NOT NULL,### 💡 Uso y desarrollo

- **William Padilla** ([@wpadillav](https://github.com/wpadillav))

- **Email**: willipadilla@proton.me    salt VARCHAR(64) NOT NULL,

- **GitHub**: [github.com/wpadillav](https://github.com/wpadillav)

    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMPPuedes iniciar un servidor local (sin Apache) para pruebas rápidas:

---

);

## ⚖️ Licencia

```bash

Este proyecto está bajo la **Licencia MIT**. Ver el archivo [LICENSE](LICENSE) para más detalles.

-- Tabla de rolesphp -S localhost:8000

```

MIT LicenseCREATE TABLE roles (```



Copyright (c) 2025 William Padilla    id INT PRIMARY KEY AUTO_INCREMENT,



Permission is hereby granted, free of charge, to any person obtaining a copy    name VARCHAR(50) UNIQUE NOT NULL,---

of this software and associated documentation files (the "Software"), to deal

in the Software without restriction, including without limitation the rights    description TEXT

to use, copy, modify, merge, publish, distribute, sublicense, and/or sell

copies of the Software, and to permit persons to whom the Software is);### 👤 Autor

furnished to do so, subject to the following conditions:



The above copyright notice and this permission notice shall be included in all

copies or substantial portions of the Software.-- Tabla relacional usuarios-roles* **William Padilla**



THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS ORCREATE TABLE user_roles (* GitHub: [@wpadillav](https://github.com/wpadillav)

IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,

FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.    user_id INT,* Contacto: [willipadilla@proton.me](mailto:willipadilla@proton.me)

```

    role_id INT,

---

    PRIMARY KEY (user_id, role_id),---

## 🌟 Agradecimientos

    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,

- **Bootstrap Team** por el framework CSS

- **Font Awesome** por los iconos    FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE### ⚖️ Licencia

- **jQuery Team** por la biblioteca JavaScript

- **Comunidad PHP** por las mejores prácticas);

- **MariaDB Foundation** por el sistema de base de datos

```MIT License - libre para usar, modificar y distribuir.

---



**Desarrollado con ❤️ para simplificar la gestión empresarial****Insertar datos iniciales:**

```sql

*NuvemERP - Tu ERP en la nube desde 2025*-- Insertar roles básicos

INSERT INTO roles (id, name, description) VALUES 

---(1, 'root', 'Acceso total al sistema, nivel máximo'),

(2, 'admin', 'Administrador general con permisos amplios'),

## 📊 Estadísticas del Proyecto(3, 'admingrupo', 'Administrador de grupos o secciones específicas'),

(4, 'operador', 'Usuario operador con permisos limitados');

- **Líneas de código**: ~3,500+

- **Archivos PHP**: 15+-- Crear usuario administrador inicial

- **Módulos**: 4 principales-- Usuario: admin | Contraseña: admin123

- **Tablas de BD**: 6+INSERT INTO users (username, password_hash, salt) VALUES 

- **Funcionalidades**: 20+('admin', '283dfa5977c8fe8c881405e33f576d5', 'c8ad2a055b0067efd43c1eb873606bb06f16b548531b970c771b1aab4172f038');

- **Compatibilidad**: PHP 8.0+

- **Arquitectura**: MVC-- Asignar rol de administrador

- **Licencia**: MITINSERT INTO user_roles (user_id, role_id) VALUES (1, 2);
```

### **4. Configurar Conexión a Base de Datos**

Editar el archivo `config/database.php`:
```php
// Actualizar con tus credenciales de base de datos
private $host = 'localhost';
private $db_name = 'app_db';
private $username = 'tu_usuario_mysql';
private $password = 'tu_password_mysql';
```

### **5. Configurar Apache**

**Habilitar módulos necesarios:**
```bash
sudo a2enmod rewrite
sudo a2enmod ssl
sudo systemctl restart apache2
```

**Configurar VirtualHost (.htaccess automático):**
El proyecto incluye configuración automática de URL rewriting. Solo asegúrate de que el DocumentRoot apunte al directorio del proyecto:

```apache
<VirtualHost *:80>
    ServerName tu-dominio.com
    DocumentRoot /var/www/html/php-mvc-seguro
    
    <Directory /var/www/html/php-mvc-seguro>
        AllowOverride All
        Require all granted
    </Directory>
</VirtualHost>
```

### **6. Configurar Permisos**
```bash
# Permisos recomendados
sudo chown -R www-data:www-data /var/www/html/php-mvc-seguro
sudo chmod -R 755 /var/www/html/php-mvc-seguro
sudo chmod 600 config/database.php  # Proteger credenciales
```

---

## 🔒 Características de Seguridad Detalladas

### **1. Cifrado de Contraseñas PBKDF2 + SHA-256**
```php
// Configuración implementada en UserModel
$hash = hash_pbkdf2(
    'sha256',           // Algoritmo: SHA-256
    $password,          // Contraseña en texto plano
    $salt,              // Salt único de 32 bytes
    100000,             // Iteraciones: 100,000
    32,                 // Longitud del hash: 32 bytes
    false               // Salida binaria
);
```

**¿Por qué es seguro?**
- **100,000 iteraciones** hacen que romper el hash sea computacionalmente costoso
- **Salt único** de 32 bytes por usuario previene rainbow tables
- **SHA-256** es un algoritmo criptográfico robusto
- **Comparación segura** con `sodium_memcmp()` previene timing attacks

### **2. Protección de Sesiones**
```php
// Configuración en AuthController
session_start([
    'cookie_lifetime' => 86400,      // 24 horas
    'cookie_secure' => true,         // Solo HTTPS
    'cookie_httponly' => true,       // No accesible desde JavaScript
    'cookie_samesite' => 'Strict'    // Protección CSRF adicional
]);
```

### **3. Control de Acceso por Roles**
- **Verificación automática** en UserAdminController
- **Middleware de autenticación** en todos los controladores
- **Prevención de escalada** de privilegios
- **Logging de eventos** de seguridad en error_log

### **4. Protección Anti-Fuerza Bruta**
- **Límite de 5 intentos** de login por sesión
- **Bloqueo temporal de 300 segundos** tras superar el límite
- **Registro de intentos fallidos** en logs del sistema
- **Regeneración de ID de sesión** tras login exitoso

### **5. Protección CSRF**
- **Tokens únicos** generados con `random_bytes(32)`
- **Validación automática** en formularios POST
- **Comparación segura** con `hash_equals()`

---

## 📊 Sistema de Roles y Permisos

| Rol | ID | Descripción | Permisos en el Sistema |
|-----|----|-----------|-----------------------| 
| **root** | 1 | Acceso total al sistema | Todos los permisos, nivel máximo |
| **admin** | 2 | Administrador general | Gestión de usuarios, acceso a UserAdmin |
| **admingrupo** | 3 | Admin de secciones específicas | Gestión limitada por grupos/secciones |
| **operador** | 4 | Usuario con permisos básicos | Acceso básico, sin funciones administrativas |

### **Control de Acceso Implementado:**
- Solo usuarios con rol `admin` o `root` pueden acceder a `/UserAdmin`
- Todos los usuarios autenticados pueden acceder a su perfil personal
- La navegación se adapta automáticamente según los permisos del usuario

---

## 🎯 Uso del Sistema

### **Acceso Inicial**
```
URL: http://tu-dominio.com/?controller=Auth&action=login
Usuario: admin
Contraseña: admin123
```

### **Funcionalidades por URL**

1. **Login** - `/?controller=Auth&action=login`
   - Formulario de autenticación con protección CSRF
   - Bloqueo temporal tras intentos fallidos
   - Redirección automática al dashboard

2. **Dashboard** - `/?controller=Dashboard&action=index`
   - Panel principal del usuario autenticado
   - Resumen de información personal
   - Enlaces rápidos a funcionalidades disponibles

3. **Perfil Personal** - `/?controller=Profile&action=index`
   - Información completa del usuario actual
   - Visualización de roles asignados
   - Enlace para cambiar contraseña personal

4. **Cambio de Contraseña Personal** - `/?controller=Profile&action=changePassword`
   - Formulario seguro para cambio de contraseña
   - Validación de contraseña actual obligatoria
   - Medidor de fortaleza de contraseña

5. **Administración de Usuarios** - `/?controller=UserAdmin&action=index` *(Solo admin/root)*
   - Lista completa de usuarios con roles
   - Opciones para crear, editar y eliminar usuarios
   - Gestión de asignación de roles

6. **Crear Usuario** - `/?controller=UserAdmin&action=create` *(Solo admin/root)*
   - Formulario completo de creación de usuario
   - Selección múltiple de roles
   - Validación exhaustiva de datos

7. **Editar Usuario** - `/?controller=UserAdmin&action=edit&id=X` *(Solo admin/root)*
   - Modificación de información de usuario existente
   - Reasignación de roles
   - Preservación de seguridad de contraseñas

8. **Cambio de Contraseña Admin** - `/?controller=UserAdmin&action=changePassword&id=X` *(Solo admin/root)*
   - Cambio administrativo de contraseñas
   - Sin requerir contraseña actual (privilegio admin)

9. **Eliminar Usuario** - `/?controller=UserAdmin&action=delete&id=X` *(Solo admin/root)*
   - Confirmación segura antes de eliminación
   - Prevención de auto-eliminación
   - Limpieza de relaciones en base de datos

10. **Herramientas** - `/?controller=Tools&action=index`
    - Herramientas adicionales del sistema
    - Funcionalidades complementarias

11. **Logout** - `/?controller=Auth&action=logout`
    - Cierre seguro de sesión
    - Limpieza completa de datos de sesión
    - Redirección automática al login

---

## 🛡️ Mejores Prácticas Implementadas

### **Seguridad**
- ✅ **Nunca almacenar contraseñas en texto plano** - Solo hashes PBKDF2
- ✅ **Salt único por usuario** - 32 bytes criptográficamente seguros
- ✅ **100,000 iteraciones PBKDF2** - Resistencia contra ataques GPU
- ✅ **Protección timing attacks** - `sodium_memcmp()` para comparaciones
- ✅ **Tokens CSRF únicos** - Prevención de ataques cross-site
- ✅ **Validación server-side exhaustiva** - Nunca confiar en el cliente
- ✅ **Sanitización de salida** - `htmlspecialchars()` en todas las vistas
- ✅ **Prepared statements** - Prevención 100% de SQL Injection
- ✅ **Configuración segura de headers** - HTTPOnly, Secure, SameSite
- ✅ **Regeneración de sesión** - Tras login exitoso
- ✅ **Logging de seguridad** - Registro de eventos críticos

### **Desarrollo**
- ✅ **Arquitectura MVC clara** - Separación total de responsabilidades
- ✅ **Código documentado** - Comentarios exhaustivos en español
- ✅ **Manejo de errores robusto** - Try/catch con logging
- ✅ **PDO para base de datos** - Abstracción y seguridad
- ✅ **Principios SOLID** - Código mantenible y extensible
- ✅ **Composer para dependencias** - Gestión profesional de librerías
- ✅ **Bootstrap responsive** - Interfaz móvil-first

---

## 🔧 Personalización y Extensión

### **Agregar Nuevos Roles**
```sql
-- Insertar nuevo rol
INSERT INTO roles (name, description) VALUES 
('editor', 'Editor de contenido con permisos específicos'),
('moderador', 'Moderador de usuarios y contenido');

-- Asignar rol a usuario existente
INSERT INTO user_roles (user_id, role_id) VALUES (2, 5);
```

### **Crear Nuevo Controlador**
```php
<?php
class MiNuevoControlador {
    public function __construct() {
        // Verificar autenticación obligatoria
        if (!isset($_SESSION['user'])) {
            header('Location: /?controller=Auth&action=login');
            exit;
        }
    }
    
    public function index() {
        // Tu lógica aquí
        require_once __DIR__ . '/../views/mi-seccion/index.php';
    }
    
    // Método para verificar roles específicos
    private function requireRole($requiredRoles) {
        $userModel = new UserModel();
        $userProfile = $userModel->getUserProfileByUsername($_SESSION['user']['username']);
        
        if (!$userProfile || !$this->userHasRole($userProfile['roles'], $requiredRoles)) {
            header('HTTP/1.1 403 Forbidden');
            die('Acceso denegado: Permisos insuficientes');
        }
    }
    
    private function userHasRole($userRoles, $requiredRoles) {
        $userRoleArray = explode(', ', $userRoles);
        foreach ($requiredRoles as $role) {
            if (in_array($role, $userRoleArray)) {
                return true;
            }
        }
        return false;
    }
}
?>
```

### **Agregar Nueva Vista**
```php
<!-- views/mi-seccion/index.php -->
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Mi Nueva Sección | Sistema Seguro</title>
    <link rel="stylesheet" href="../assets/css/bootstrap.min.css">
</head>
<body>
    <?php include_once __DIR__ . '/../components/nav.php'; ?>
    
    <div class="container mt-5">
        <h2>🆕 Mi Nueva Sección</h2>
        <p>Contenido personalizado aquí...</p>
    </div>
    
    <script src="../assets/js/bootstrap.bundle.min.js"></script>
</body>
</html>
```

---

## 📈 Monitoreo y Logs

### **Logs de Seguridad**
El sistema registra automáticamente eventos críticos:
- Intentos de login fallidos
- Accesos no autorizados
- Tokens CSRF inválidos
- Errores de autenticación

**Ubicación de logs:**
```bash
# Logs del sistema (Ubuntu/Debian)
/var/log/apache2/error.log

# Logs de PHP
/var/log/php_errors.log

# Ver logs en tiempo real
sudo tail -f /var/log/apache2/error.log | grep "Security:"
```

### **Eventos Monitoreados**
```php
// Ejemplos de logs generados automáticamente
Security: Usuario no encontrado: usuario_inexistente
Security: CSRF token inválido - IP: 192.168.1.100
Security: Intento de login fallido para: admin
Error crítico en autenticación: Salt inválido
```

---

## 📝 Licencia

Este proyecto está bajo la **Licencia MIT**. Ver el archivo [LICENSE](LICENSE) para más detalles.

```
MIT License

Copyright (c) 2024 William Padilla

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT.
```

---

## 👤 Autor

- **William Padilla** ([@wpadillav](https://github.com/wpadillav))
- **Email**: willipadilla@proton.me
- **GitHub**: [github.com/wpadillav/php-mvc-seguro](https://github.com/wpadillav/php-mvc-seguro)

---

## 🙏 Agradecimientos

- **OWASP** por las guías de seguridad web
- **PHP Security Consortium** por las mejores prácticas
- **Bootstrap Team** por el framework CSS
- **Comunidad PHP** por las librerías y herramientas
- **Libsodium Team** por las funciones criptográficas seguras

---

## 🆘 Soporte y Contribuciones

### **¿Encontraste un bug?**
- Reporta issues en: [GitHub Issues](https://github.com/wpadillav/php-mvc-seguro/issues)
- Incluye detalles del error y pasos para reproducir

### **¿Quieres contribuir?**
1. Fork el repositorio
2. Crea una rama para tu feature (`git checkout -b feature/nueva-funcionalidad`)
3. Commit tus cambios (`git commit -am 'Agregar nueva funcionalidad'`)
4. Push a la rama (`git push origin feature/nueva-funcionalidad`)
5. Crea un Pull Request

### **Estándares de Contribución**
- **Código en español** para comentarios y documentación
- **PSR-12** para estilo de código PHP
- **Validación de seguridad** obligatoria
- **Pruebas exhaustivas** antes de submit

---

**Desarrollado con ❤️ para la comunidad PHP**

*Sistema PHP MVC Seguro - Protegiendo aplicaciones web desde 2024*

---

## 📊 Estadísticas del Proyecto

- **Líneas de código**: ~2,500
- **Archivos PHP**: 12
- **Vistas HTML**: 11
- **Nivel de seguridad**: ⭐⭐⭐⭐⭐
- **Arquitectura**: MVC puro
- **Compatibilidad**: PHP 7.4+