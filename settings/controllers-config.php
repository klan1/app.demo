<?php

namespace k1app;

/**
 * TABLE CONFIG DEFAULT
 */
class table_config_default_class {

    const CONTROLLER_ALLOWED_LEVELS = ['god', 'admin', 'user'];

    /**
     * URLS
     */
    const ROOT_URL = "app/controlername";
    const BOARD_CREATE_URL = "crear";
    const BOARD_READ_URL = "leer";
    const BOARD_UPDATE_URL = "actualizar";
    const BOARD_DELETE_URL = "borrar";
    const BOARD_EXPORT_URL = "exportar";
    const BOARD_LIST_URL = "listar";

    /**
     * AVAILABILITY
     */
    const BOARD_CREATE_ENABLED = TRUE;
    const BOARD_READ_ENABLED = TRUE;
    const BOARD_UPDATE_ENABLED = TRUE;
    const BOARD_DELETE_ENABLED = TRUE;
    const BOARD_EXPORT_ENABLED = TRUE;
    const BOARD_LIST_ENABLED = TRUE;

    /**
     * NAMES
     */
    const BOARD_CREATE_NAME = "Crear registro";
    const BOARD_READ_NAME = "Leer registro";
    const BOARD_UPDATE_NAME = "Actualizar datos del registro";
    const BOARD_DELETE_NAME = "Borrar registro";
    const BOARD_EXPORT_NAME = "Exportar tabla";
    const BOARD_LIST_NAME = "Listar datos";

    /**
     * ALLOWED LEVELS
     */
    const BOARD_CREATE_ALLOWED_LEVELS = ['god', 'admin', 'user'];
    const BOARD_READ_ALLOWED_LEVELS = ['god', 'admin', 'user'];
    const BOARD_UPDATE_ALLOWED_LEVELS = ['god', 'admin', 'user'];
    const BOARD_DELETE_ALLOWED_LEVELS = ['god', 'admin', 'user'];
    const BOARD_EXPORT_ALLOWED_LEVELS = ['god', 'admin'];
    const BOARD_LIST_ALLOWED_LEVELS = ['god', 'admin', 'user'];

}

class table_config_user_read_default_class extends table_config_default_class {

    const CONTROLLER_ALLOWED_LEVELS = ['god', 'admin', 'user', 'guest'];

    /**
     * ALLOWED LEVELS
     */
    const BOARD_CREATE_ALLOWED_LEVELS = ['god', 'admin'];
    const BOARD_READ_ALLOWED_LEVELS = ['god', 'admin', 'user'];
    const BOARD_UPDATE_ALLOWED_LEVELS = ['god', 'admin'];
    const BOARD_DELETE_ALLOWED_LEVELS = ['god', 'admin'];
    const BOARD_EXPORT_ALLOWED_LEVELS = ['god', 'admin'];
    const BOARD_LIST_ALLOWED_LEVELS = ['god', 'admin', 'user'];

}

class table_config_user_write_default_class extends table_config_default_class {
    
}

class table_config_guest_read_default_class extends table_config_default_class {

    const CONTROLLER_ALLOWED_LEVELS = ['god', 'admin', 'user', 'guest'];

    /**
     * ALLOWED LEVELS
     */
    const BOARD_CREATE_ALLOWED_LEVELS = ['god', 'admin', 'user', 'guest'];
    const BOARD_READ_ALLOWED_LEVELS = ['god', 'admin', 'user', 'guest'];
    const BOARD_UPDATE_ALLOWED_LEVELS = ['god', 'admin', 'user'];
    const BOARD_DELETE_ALLOWED_LEVELS = ['god', 'admin'];
    const BOARD_EXPORT_ALLOWED_LEVELS = ['god', 'admin'];
    const BOARD_LIST_ALLOWED_LEVELS = ['god', 'admin', 'user', 'guest'];

}

class table_config_guest_write_default_class extends table_config_default_class {

    const CONTROLLER_ALLOWED_LEVELS = ['god', 'admin', 'user', 'guest'];

    /**
     * ALLOWED LEVELS
     */
    const BOARD_CREATE_ALLOWED_LEVELS = ['god', 'admin', 'user', 'guest'];
    const BOARD_READ_ALLOWED_LEVELS = ['god', 'admin', 'user', 'guest'];
    const BOARD_UPDATE_ALLOWED_LEVELS = ['god', 'admin', 'user', 'guest'];
    const BOARD_DELETE_ALLOWED_LEVELS = ['god', 'admin', 'user', 'guest'];
    const BOARD_EXPORT_ALLOWED_LEVELS = ['god', 'admin'];
    const BOARD_LIST_ALLOWED_LEVELS = ['god', 'admin', 'user', 'guest'];

}

class table_config_admin_default_class extends table_config_default_class {

    const CONTROLLER_ALLOWED_LEVELS = ['god', 'admin'];

    /**
     * ALLOWED LEVELS
     */
    const BOARD_CREATE_ALLOWED_LEVELS = ['god', 'admin'];
    const BOARD_READ_ALLOWED_LEVELS = ['god', 'admin'];
    const BOARD_UPDATE_ALLOWED_LEVELS = ['god', 'admin'];
    const BOARD_DELETE_ALLOWED_LEVELS = ['god', 'admin'];
    const BOARD_EXPORT_ALLOWED_LEVELS = ['god', 'admin'];
    const BOARD_LIST_ALLOWED_LEVELS = ['god', 'admin'];

}

class table_config_god_default_class extends table_config_default_class {

    const CONTROLLER_ALLOWED_LEVELS = ['god'];

    /**
     * ALLOWED LEVELS
     */
    const BOARD_CREATE_ALLOWED_LEVELS = ['god'];
    const BOARD_READ_ALLOWED_LEVELS = ['god'];
    const BOARD_UPDATE_ALLOWED_LEVELS = ['god'];
    const BOARD_DELETE_ALLOWED_LEVELS = ['god'];
    const BOARD_EXPORT_ALLOWED_LEVELS = ['god'];
    const BOARD_LIST_ALLOWED_LEVELS = ['god'];

}

/**
 * TABLE EXPLORER
 */
class crudlexs_config extends table_config_god_default_class{

    /**
     * URLS
     */
    const ROOT_URL = "table-explorer/crudlexs";
}

/**
 * APP USER CONFIG CLASESS FROM HERE, UPCODE PLEASE LET IT BE.
 */

