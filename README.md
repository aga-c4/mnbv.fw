################################################################################
##  Фреймворк FW.MNBV-1.1
################################################################################

Легкий фреймворк с поддержкой composer. Установку пакетов необходимо вести из
корня проекта в папку vendors, автозагрузчик будет автоматически добавлен в
очередь.

################################################################################
##  Установка фреймворка FW.MNBV-1.1
################################################################################

1. Склонируйте из GIT систему в каталог.

2. Скопируйте и при необходимости скорректируйте и скопируйте файлы:
    из console.default.php в console/console.php
    из www/index_default.php в www/index.php
удалив "default" на конце.

3. Установите рутовую папку для данного домена на папку "www"

4. Если вам необходимо переопределить системные константы, то сделайте это в 
файле app/modules/core/config/constants.php - он вызывается до вызова
файла с определением дефолтовых констант с проверкой их наличия.

5. Вы можете поменять дефолтовый конфиг, используя для этого файл
app/modules/core/config/config.php - этот файл выполнится после выполнения
дефолтового конфига и может переопредлить его установки.

6. Вы можете разместить свои модули в папке modules или app/modules.

8. Удобный механизм миграций баз данных любого типа, не привязанный к приложению.
В папке "data/storage_dumps/migration.default.php" подробно описан сам механизм
и особенности его применения. Все реализовано в одном php файле, вы можете
использовать его отдельно в своих проектах, если он вам понравится. Помимо
обновления базы, вы также сможете делать и дампы текущего состояния БД.

9. Консольный запуск ведется вызовом console.php с параметрами командной
строки, которые будут переданы в скрипт.
