<?php

namespace k1app;

use \k1lib\html\DOM as DOM;

$run_info = DOM::html()->body()->footer()->get_element_by_id("k1lib-run-info");

if (!empty($run_info)) {
    $k1lib_a = new \k1lib\html\a("https://github.com/klan1/k1.lib", "k1.lib", "_blank");
    $run_time = round(\k1lib\PROFILER::end(), 4);

    $run_info->set_value(" | Runtime: {$run_time}s - $k1lib_a V" . \k1lib\VERSION);
}

\k1lib\notifications\on_DOM::insert_messases_on_DOM();
