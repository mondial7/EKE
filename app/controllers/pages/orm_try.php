<?php

require MODELS_DIR . '/ORMTry.php';

$test = (new ORMTry())->getResult();

if (count($test)>0) {

  print_r($test);

} else {

  echo 'Dude! I don\'t find anything.';

}
