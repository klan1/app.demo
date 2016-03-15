<?php

namespace k1app;

/**
  /**
 * THE AGENCY
 */
class agency_my_agency_config {

    const CONTROLLER_ALLOWED_LEVELS = ['god', 'admin', 'user', 'guest'];

    /**
     * URLS
     */
    const ROOT_URL = "the-agency/my-agency";
    const BOARD_CREATE_URL = FALSE;
    const BOARD_READ_URL = "details";
    const BOARD_UPDATE_URL = "edit-details";
    const BOARD_DELETE_URL = FALSE;
    const BOARD_EXPORT_URL = FALSE;
    const BOARD_LIST_URL = FALSE;

    /**
     * AVAILABILITY
     */
    const BOARD_CREATE_ENABLED = FALSE;
    const BOARD_READ_ENABLED = TRUE;
    const BOARD_UPDATE_ENABLED = TRUE;
    const BOARD_DELETE_ENABLED = FALSE;
    const BOARD_EXPORT_ENABLED = FALSE;
    const BOARD_LIST_ENABLED = FALSE;

    /**
     * NAMES
     */
    const BOARD_CREATE_NAME = FALSE;
    const BOARD_READ_NAME = FALSE;
    const BOARD_UPDATE_NAME = "Update agency details";
    const BOARD_DELETE_NAME = FALSE;
    const BOARD_EXPORT_NAME = FALSE;
    const BOARD_LIST_NAME = FALSE;

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

class agency_departments_config extends agency_my_agency_config {

    const ROOT_URL = "the-agency/departments";
    const BOARD_CREATE_URL = "add-new";
    const BOARD_READ_URL = "show-details";
    const BOARD_UPDATE_URL = "edit-details";
    const BOARD_DELETE_URL = "let-it-go";
    const BOARD_EXPORT_URL = "export-xml";
    const BOARD_LIST_URL = "list";

    /**
     * NAMES
     */
    const BOARD_CREATE_NAME = "Add new";
    const BOARD_READ_NAME = FALSE;
    const BOARD_UPDATE_NAME = "Update details";
    const BOARD_DELETE_NAME = "Delete record";
    const BOARD_EXPORT_NAME = "Export data";
    const BOARD_LIST_NAME = FALSE;

    /**
     * ALLOWED LEVELS
     */
    const BOARD_CREATE_ALLOWED_LEVELS = ['god', 'admin'];
    const BOARD_READ_ALLOWED_LEVELS = ['god', 'admin', 'user'];
    const BOARD_UPDATE_ALLOWED_LEVELS = ['god', 'admin'];
    const BOARD_DELETE_ALLOWED_LEVELS = ['god', 'admin'];
    const BOARD_EXPORT_ALLOWED_LEVELS = ['god', 'admin'];
    const BOARD_LIST_ALLOWED_LEVELS = ['god', 'admin'];

}

class agency_job_titles_config extends agency_departments_config {

    const ROOT_URL = "the-agency/job-titles";

}

class agency_locations_config extends agency_departments_config {

    const ROOT_URL = "the-agency/locations";

}

class agency_users_config extends agency_departments_config {

    const ROOT_URL = "the-agency/users";
    const BOARD_READ_URL = "details";
    const BOARD_UPDATE_ALLOWED_LEVELS = ['god', 'admin', 'user'];
    const BOARD_LIST_ALLOWED_LEVELS = ['god', 'admin', 'user'];

}

/**
 * THE CLIENT
 */
class client_clients_config extends agency_departments_config {

    const ROOT_URL = "the-clients/clients";
    const BOARD_CREATE_ALLOWED_LEVELS = ['god', 'admin'];
    const BOARD_READ_ALLOWED_LEVELS = ['god', 'admin', 'user', 'client'];
    const BOARD_UPDATE_ALLOWED_LEVELS = ['god', 'admin'];
    const BOARD_DELETE_ALLOWED_LEVELS = ['god', 'admin'];
    const BOARD_EXPORT_ALLOWED_LEVELS = ['god', 'admin'];
    const BOARD_LIST_ALLOWED_LEVELS = ['god', 'admin', 'client'];

}

class client_contacts_config extends client_clients_config {

    const ROOT_URL = "the-clients/contacts";

}

class client_contracts_config extends client_clients_config {

    const ROOT_URL = "the-clients/contracts";

}

class client_projects_config extends client_clients_config {

    const ROOT_URL = "the-clients/projects";

}

class client_task_orders_config extends client_clients_config {

    const ROOT_URL = "the-clients/task-orders";

    /**
     * ALLOWED LEVELS
     */
    const BOARD_CREATE_ALLOWED_LEVELS = ['god', 'admin'];
    const BOARD_READ_ALLOWED_LEVELS = ['god', 'admin', 'user', 'client'];
    const BOARD_UPDATE_ALLOWED_LEVELS = ['god', 'admin'];
    const BOARD_DELETE_ALLOWED_LEVELS = ['god', 'admin'];
    const BOARD_EXPORT_ALLOWED_LEVELS = ['god', 'admin'];
    const BOARD_LIST_ALLOWED_LEVELS = ['god', 'admin'];

}

class client_task_orders_assignations_config extends client_task_orders_config {

    const ROOT_URL = "the-clients/task-orders-assignations";
    const BOARD_CREATE_ALLOWED_LEVELS = ['god', 'admin', 'user'];
    const BOARD_LIST_ALLOWED_LEVELS = ['god', 'admin', 'user'];

}

class client_task_orders_states_config extends client_task_orders_assignations_config {

    const ROOT_URL = "the-clients/task-orders-states";
    const BOARD_CREATE_ALLOWED_LEVELS = ['god', 'admin', 'user'];
    const BOARD_LIST_ALLOWED_LEVELS = ['god', 'admin', 'user'];

}
