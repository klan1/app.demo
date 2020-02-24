<?php

namespace k1app;

require 'db.php';

$crud_api_obj = new api\auth\api_crud(TRUE,TRUE);
$crud_api_obj->set_db($db);
$crud_api_obj->set_db_table_name('municipio');
$crud_api_obj->set_db_table_keys_fields(['municipio_id','fk_departamento_id','fk_pais_id']);
$crud_api_obj->exec();
