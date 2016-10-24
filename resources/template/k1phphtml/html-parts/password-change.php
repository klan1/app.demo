<?php
/**
 * HTML insertion example
 */

namespace k1app;

use \k1lib\html\DOM as DOM;

$body = DOM::html()->body();

ob_start();
?>
<h1>Hello World!</h1>
<?php
$buffer = ob_get_clean();
$body->content()->set_value($buffer);
