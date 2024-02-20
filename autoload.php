<?php
    spl_autoload_register(function ($classname) {
        require("classes/".$classname.".php");
    });
