<?php

namespace k1app;

//include 'ecard-generation.php';

header('Content-type:image/png');
$ecard->set_image_proportion(1);
echo $ecard->get_ecard_imagick();
