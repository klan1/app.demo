<?php

namespace k1app;
/**
 * 
 * @param \Imagick $canvas
 * @param \ImagickDraw $draw
 * @param string $text
 * @param int $max_width
 * @return string
 */
function message_to_lines(\Imagick $canvas, \ImagickDraw $draw,  $text, $max_width) {
    $words = explode(" ", $text);

    $lines = '';
    $i = 0;
    while ($i < count($words)) {//as long as there are words 
        $line = "";
        do {//append words to line until the fit in size 
            if ($line != "") {
                $line .= " ";
            }
            $line .= $words[$i];


            $i++;
            if (($i) == count($words)) {
                break; //last word -> break 
            }

            //messure size of line + next word 
            $linePreview = $line . " " . $words[$i];
            $metrics = $canvas->queryFontMetrics($draw, $linePreview);
        } while ($metrics["textWidth"] <= $max_width);

        //echo "<hr>".$line."<br>"; 
        $lines .= $line . "\n";
    }

    return $lines;
}
