<?php

namespace k1app;

require 'db.php';

$crud_api_obj = new api\auth\api_crud(TRUE,TRUE);
$crud_api_obj->set_db($db);
$crud_api_obj->set_db_table_name('estudios_ipig');
//$crud_api_obj->set_db_table_keys_fields(['fk_empresa_id']);
$crud_api_obj->set_db_table_keys_fields(['fk_granja_id','fk_empresa_id']);
$crud_api_obj->exec();
