#!/bin/bash
cd /home/site/wwwroot || exit

php artisan import:fire-hydrants-csv
php artisan import:firestations
php artisan import:fires app/Console/Commands/fires/part_1.csv
php artisan import:fires app/Console/Commands/fires/part_2.csv
php artisan import:fires app/Console/Commands/fires/part_3.csv