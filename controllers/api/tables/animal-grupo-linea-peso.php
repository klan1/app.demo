<?php

namespace k1app;

require 'db.php';

$crud_api_obj = new api\auth\api_crud(TRUE,TRUE);
$crud_api_obj->set_db($db);
$crud_api_obj->set_db_table_name('animal_grupo_linea_peso');
$crud_api_obj->set_db_table_keys_fields(['peso_id','fk_linea_id']);
$crud_api_obj->exec();
