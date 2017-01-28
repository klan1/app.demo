<?php

namespace k1app;

/**
 * TABLE EXPLORER
 */
class crudlexs_config {

    const CONTROLLER_ALLOWED_LEVELS = ['god', 'admin', 'user', 'guest'];

    /**
     * URLS
     */
    const ROOT_URL = "table-explorer/crudlexs";
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
    const BOARD_CREATE_ALLOWED_LEVELS = ['god', 'admin', 'user'];
    const BOARD_READ_ALLOWED_LEVELS = ['god', 'admin', 'user', 'guest'];
    const BOARD_UPDATE_ALLOWED_LEVELS = ['god', 'admin', 'user'];
    const BOARD_DELETE_ALLOWED_LEVELS = ['god', 'admin'];
    const BOARD_EXPORT_ALLOWED_LEVELS = ['god', 'admin'];
    const BOARD_LIST_ALLOWED_LEVELS = ['god', 'admin', 'user', 'guest'];

}

class ecards_config {

    const CONTROLLER_ALLOWED_LEVELS = ['god', 'admin', 'user'];

    /**
     * URLS
     */
    const ROOT_URL = "app/ecards";
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
    const BOARD_CREATE_NAME = "Add new";
    const BOARD_READ_NAME = "View details";
    const BOARD_UPDATE_NAME = "Update details";
    const BOARD_DELETE_NAME = "Delete";
    const BOARD_EXPORT_NAME = "Export";
    const BOARD_LIST_NAME = "List";

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

class memberships_config extends ecards_config {

    const ROOT_URL = "app/memberships";

}

class ecards_sends_config extends ecards_config {

    const ROOT_URL = "app/ecards-sends";

}

class ecard_categories_config extends ecards_config {

    const ROOT_URL = "app/ecard-categories";

}

class ecard_layouts_config extends ecards_config {

    const ROOT_URL = "app/ecard-layouts";

}

class users_config extends ecards_config {

    const ROOT_URL = "app/users";

}

class user_memberships_config extends ecards_config {

    const ROOT_URL = "app/user-memberships";

}

class payments_config extends ecards_config {

    const ROOT_URL = "app/payments";

}

class payment_details_config extends ecards_config {

    const ROOT_URL = "app/payment-details";

}