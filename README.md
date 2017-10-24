IF - INTELITI FRAMEWORK
===================

Hola, nos alegra compartir contigo este framework desarrollado en inteliti para facilitar muchas de las funcionalidades utilizadas normalmente en una aplicaciones web. El framework esta basado en Codeigniter 3.x y un conjunto de librerias PHP, librerias JS y CSS. Entre sus principales características están:

 - **Maestro - Detalle:** Permite implementar de una manera el componente de interfaz de usuario compuesto por una tabla de datos acompañado con un espacio para mostrar mas detalles de los datos de la tabla. Ideal para módulos de trabajo que requieren que el usuario interactúe con muchos datos. [wiki](https://en.wikipedia.org/wiki/Master%E2%80%93detail_interface)
 - **Layer:** Con este componente podemos agregar un nuevo elemento al Maestro - Detalle, permitiendo la apertura de capas para extender la visualización de datos en la interfaz de usuario.
 - **Avatar:** ofrece la posibilidad de ubicar un componente de interfaz de usuario para la carga de una imagen que pueda ser utilizada como un avatar de un usuario. Puede funcionar tomando una foto desde la webcam del equipo o permite subir una imagen del sistema de archivos.
 - **Upload:** con este componente se puede ubicar en cualquier espacio la opción para permitir la carga de archivos asociados a una entidad. Por ejemplo la carga de archivos asociados al expediente de un cliente.
 - **Download:** con este componente se puede administrar la descarga de archivos desde el servidor y da la opción de restringir la descarga basado en la autenticación del usuario que realiza la descarga,
 - **Modal:** este componente controla la aparición de ventanas modales de una manera bastante sencilla.

----------


Documentación
-------------

A continuación explicaremos brevemente como hacer uso del framework:

 - El proyecto tiene los siguientes directorios:
	 - demo: aquí se ubica el código de demostración. Desde aquí podras entender como funciona el framework y cuales son las pautas de organización y trabajo.
	 - dist: en este directorio se ubica el codigo que se debe utilizar para empezar un nuevo proyecto y/o actualizar la versión de if que esté utilizando un proyecto
	 - db: en este directorio encontramos los scripts de BD tanto para correr el proyecto demo y para utilizar en dist.


----------


Hoja de Ruta
-------------

Nos planteamos los siguientes hitos próximoas a ser desarrollados:

 - Poder visualizar los nombres de los archivos en el plugin de if.upload
 - Funcionalidad para optimizar la carga de recursos CSS y JS (minificación y carga asincrona)
 - Mejorar documentación en el uso del framewrok y los distintos plugin.

