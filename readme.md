Для запуска скрипта в терминале необходимо указать команду такого формата:
php C:\openserver\domains\php-practice\test\test\giffer.php 400 400 C:\openserver\domains\php-practice\test\test\wm.png C:\openserver\domains\php-practice\test\test\gif2.gif C:\openserver\domains\php-practice\test\test\newgif.gif

Есть пару негативных моментов, связанных с качеством изображения на выходе (после ресайза фрэймов и png файла, после их наложения):
1. В некоторых gif файлах искажаются цвета (иногда значительно). Поправить это стандартными функциями GD  не получилось.
2. Из-за различий в палитре, некоторые png файлы, при наложении на gif, приобретают видимую прозрачную область. Здесь все зависит, в первую очередь от самого png (требуется png-8).
3. На некоторых gif искажается отображение водяного знака.