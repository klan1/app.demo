<?php

namespace k1app;
?>
<!-- <?php echo basename(__FILE__) ?> -->
<script>
    (function (i, s, o, g, r, a, m) {
        i['GoogleAnalyticsObject'] = r;
        i[r] = i[r] || function () {
            (i[r].q = i[r].q || []).push(arguments)
        }, i[r].l = 1 * new Date();
        a = s.createElement(o),
                m = s.getElementsByTagName(o)[0];
        a.async = 1;
        a.src = g;
        m.parentNode.insertBefore(a, m)
    })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');

    ga('create', 'UA-96160007-1', 'auto');
    ga('create', 'UA-96247541-1', 'auto', 'backup');
    ga('send', 'pageview');
    ga('backup.send', 'pageview');
</script>
