<?php

namespace k1app;

$text = "Hola mundo!";
d("String: {$text}");

$encrypted = \k1lib\crypt::encrypt($text);
d("Encrypted: " . $encrypted);

$decrypted = \k1lib\crypt::decrypt($encrypted);
d("Decrypted: " . $decrypted);

d($decrypted, TRUE);


