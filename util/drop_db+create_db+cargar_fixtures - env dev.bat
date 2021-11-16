cd ..

set PATH=%PATH%;C:\msas\211101-smrc-gesac-gestion-activos-finanzas\devenv\bin\php8

echo Crear bbdd
"php.exe" "bin/console" doctrine:database:drop --force
"php.exe" "bin/console" doctrine:database:create 

echo Crear schema
"php.exe" "bin/console" doctrine:schema:create
"php.exe" "bin/console" doctrine:fixtures:load --no-interaction -vv --append 




pause