################################################################################
##  Фреймворк MNBV-1.0
################################################################################

Легкий фреймворк с поддержкой composer. Установку пакетов необходимо вести из
корня проекта в папку vendors, автозагрузчик будет автоматически добавлен в
очередь.

################################################################################
##  Установка системы CMS.MNBV-8
################################################################################

1. Склонируйте из GIT систему в каталог.

2. Скопируйте и при необходимости скорректируйте файлы:
    console/console.default.php
    www/index_default.php
удалив "default" на конце.

3. Установите рутовую папку для данного домена на папку "www"

Консольный запуск ведется вызовом console/console.php с параметрами командной
строки, которые будут переданы в скрипт.