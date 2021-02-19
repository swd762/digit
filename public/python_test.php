<?php

// $python = system('python C:\OpenServer\domains\digit\storage\app\python\runAnalizing.py', $resultCode);
$python =
    exec('"C:\Program Files\Python38\python.exe" C:\OpenServer\domains\digit\storage\app\python\runAnalizing.py "{\"dateFrom\" : \"2021-02-15\", \"dateTo\" : \"2021-02-17\"}"');

echo $python;
echo $resultCode;
echo "end";
