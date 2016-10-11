<?php

namespace k1app;

/**
 * THE AGENCY
 */
class crudlexs_config {

    const CONTROLLER_ALLOWED_LEVELS = ['god', 'admin', 'user', 'guest'];

    /**
     * URLS
     */
    const ROOT_URL = "auto-app/crudlexs";
    const BOARD_CREATE_URL = "create";
    const BOARD_READ_URL = "read";
    const BOARD_UPDATE_URL = "update";
    const BOARD_DELETE_URL = "delete";
    const BOARD_EXPORT_URL = "export";
    const BOARD_LIST_URL = "list";

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
    const BOARD_CREATE_NAME = "Create row";
    const BOARD_READ_NAME = "Read row";
    const BOARD_UPDATE_NAME = "Update row details";
    const BOARD_DELETE_NAME = "Delete row";
    const BOARD_EXPORT_NAME = "Export table";
    const BOARD_LIST_NAME = "List table data";

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