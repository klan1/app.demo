<?php

namespace k1app;
?>
<pre>
    <?php

    use k1lib\urlrewrite\url as url;

print_r($_GET);

    $url_data = $_GET['K1LIB_URL'];

    $url_data_array = explode('/', $url_data);

    print_r($url_data_array);

    $action = url::set_url_rewrite_var(1, 'action', TRUE);
    $parameter = url::set_url_rewrite_var(2, 'parameter', TRUE);
    $optional = url::set_url_rewrite_var(url::get_url_level_count(), 'optional', FALSE);

    d("action: $action");
    d("parameter: $parameter");
    d("opt: $optional");

    d(url::get_data());
    d(url::get_url_level_name(1));
    d(url::get_url_level_value(2));

    d(APP_ROOT . url::get_this_url());
    d(APP_URL . url::get_this_url());
    ?>
</pre>