<?php

$python =
    exec('"C:\Program Files\Python38\python.exe" runAnalizing.py "{\"dateFrom\" : \"2021-02-15\", \"dateTo\" : \"2021-04-17\"}"');

echo $python;
echo "end";
