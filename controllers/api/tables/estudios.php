<?php

namespace k1app;

require 'db.php';

$crud_api_obj = new api\auth\api_crud(TRUE,TRUE);
$crud_api_obj->set_db($db);
$crud_api_obj->set_db_table_name('estudios');
$crud_api_obj->set_db_table_keys_fields(['estudio_id','fk_animal_id','fk_empresa_id','fk_granja_id']);
$crud_api_obj->exec();
