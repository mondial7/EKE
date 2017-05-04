<?php

require_once $MODELS_DIR . '/Search.php';

echo (new Search())->getAll();