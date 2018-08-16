# Tres en raya

El juego del tres en raya, también conocido como *Tic-Tac-Toe*, es un juego de dos jugadores en que cada uno, por turnos,
marcan las casillas de un tablero 3x3 con una X o una O respectivamente.

## Primeros pasos

Siguendo estas instrucciones podrás tener una copia del proyecto lista para ser ejecutada en tu máquina local con fines
de desarrollo y de testeo.

### Requisitos previos

Al tratarse de un **proyecto hecho en Symfony**, necesitas tener las siguientes tecnologías/herramientas instaladas:
* [Composer](https://getcomposer.org/).
* [XAMPP (5.6.37)](https://www.apachefriends.org/download.html) (opción recomendada).
* PHP (5.6.X), Apache y MySQL por separado (opción alternativa).

Si no quieres usar la solución completa de XAMPP, también puedes hacer uso del [servidor integrado de Symfony](https://symfony.com/doc/current/setup/built_in_web_server.html)
(igualmente, necesitarás configurar una base de datos MySQL).

## Instalación (con XAMPP)

Una vez tengamos XAMPP instalado, **clonamos el repositorio en una carpeta dentro del directorio "htdocs"**
de nuestra instalación de XAMPP.

Abrimos una consola de comandos, nos posicionamos en la raíz de la carpeta que hemos creado anteriormente y ejecutamos 
el siguiente comando para **instalar las dependencias necesarias** de nuestro proyecto:

```
composer install
```

Con XAMPP ejecutándose en **modo administrador**, **arrancamos** los servicios de **Apache y MySQL**.
Una vez arrancados, accedemos desde nuestro navegador a **phpMyAdmin** para **crear la base de datos**:

```
http://localhost/phpmyadmin/
```

Creamos una base de datos llamada **"3_en_raya"** de **collation "utf8_bin"**, dejando toda la **configuración por defecto**.

Con la base de datos ya creada 
(y configurados los datos de conexión a la misma en el fichero **"app/config/parameters.yml"** del proyecto),
**exportamos el modelo** (desde la raíz del proyecto) a la base de datos para generar las correspondientes tablas, 
claves y relaciones necesarias:

```
php bin/console doctrine:schema:update --force
```

Una vez tenemos todo, podemos **acceder al tres en raya desde nuestro navegador** bien en modo desarrollo, 
bien en modo producción.

**Desarrollo**:

```
http://localhost/<nombre de la carpeta raíz dentro de htdocs>/web/app_dev.php/
```

**Producción**:

```
http://localhost/<nombre de la carpeta raíz dentro de htdocs>/web/
```

## Hecho con

* [Symfony](https://symfony.com/) - Framework PHP para el desarrollo de proyectos web.
* [Bootstrap](https://getbootstrap.com/) - Librería de componentes para desarrollo front-end.
* [Fontawesome](https://fontawesome.com/) - Paquetes de iconos.
* [Composer](https://getcomposer.org/) - Gestor de dependencias para PHP.
* [Bower](https://bower.io/) - Gestor de paquetes para desarrollo front-end.

## Versionado

Este proyecto usa [versionado semántico](http://semver.org/). Consulta [los tags de este repositorio](https://github.com/Muzorn/tres-en-raya/tags) 
para ver las versiones disponibles. 

## Autores

* **Jesús María García Carro**.

## Licencia

Este proyecto se encuentra bajo licencia MIT.

## Reconocimientos

* Agradecimientos a [PurpleBooth](https://gist.github.com/PurpleBooth/) por la maravillosa plantilla de README.md
