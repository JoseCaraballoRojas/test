#Test 

#Pasos para ejecutar el proyecyo

#Clonar el proyecto: 
* $ mkdir test
* $ cd test
* $ git init
* $ git clone https://github.com/JoseCaraballoRojas/test.git


#Instalar dependencias Laravel:
* $cd test
* $ composer install

#Configurar base de datos:
* $ cp .env.example .env

* Setear en el archivo .env los datos correspondientes a sus manejador de base de datos y entono local

#Creamos un nuevo API key:
* $ php artisan key:generate

#Ejecutamos las migraciones y seeders:
* $ php artisan migrate --seed

#levantar server Laravel:
* $ php artisan serve

#instalar dependencias de front:
* $ cd test_react
* $ yarn install
* $ yarn start
