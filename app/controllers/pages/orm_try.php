<?php

require MODELS_DIR . '/ORMTry.php';

$model = new ORMTry();

print_r($model->getResult());
