<?php

date_default_timezone_set("Asia/Jakarta");

function autoload($class) {
    include "src/$class.php";
}

spl_autoload_register("autoload");
