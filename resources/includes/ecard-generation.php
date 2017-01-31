<?php

namespace k1app;

use k1lib\notifications\on_DOM as DOM_notifications;

//use k1lib\html\template as template;
//use \k1lib\urlrewrite\url as url;
//use k1app\k1app_template as DOM;
//use k1lib\session\session_db as session_db;

const ECARD_HORIZONTAL = 1;
const ECARD_VERTICAL = 2;

/**
 * 
 * @param \Imagick $canvas
 * @param \ImagickDraw $draw
 * @param string $text
 * @param int $max_width
 * @return string
 */
function message_to_lines(\Imagick $canvas, \ImagickDraw $draw, $text, $max_width) {
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

class ecard_generator {

    private $layout_data = array();
    private $ecard_id = NULL;
    private $ecard_data = NULL;
    private $ecard_mode = NULL;
    private $send_id = NULL;
    private $send_data = NULL;

    /**
     * @var \Imagick
     */
    private $imagick = NULL;

    /**
     * @var \ImagickDraw
     */
    private $draw_to = NULL;

    /**
     * @var \ImagickDraw
     */
    private $draw_from = NULL;

    /**
     * @var \ImagickDraw
     */
    private $draw_message = NULL;

    /**
     * @var \ImagickDraw
     */
    private $draw_watermark = NULL;
    private $image_proportion = 0.5;
    private $no_img = APP_RESOURCES_URL . 'images/no-image-available.jpg';
    private $make_shadow = TRUE;

    public function __construct($ecard_id, $mode = ECARD_HORIZONTAL, $send_id = NULL) {

        $this->ecard_id = $ecard_id;
        $this->ecard_mode = $mode;

        $this->load_ecard_data();
    }

    public function load_ecard_data() {
        global $db;

        /**
         * ECARD DATA LOAD
         */
        $ecard_table = new \k1lib\crudlexs\class_db_table($db, 'ecards');
        $ecard_table->set_query_filter(['ecard_id' => $this->ecard_id]);
        $this->ecard_data = $ecard_table->get_data(FALSE);

        if (!empty($this->ecard_data)) {

            /**
             * HORIZONTAL OR VERTICAL
             */
            if ($this->ecard_mode === ECARD_HORIZONTAL) {
                $ecard_file_name = $this->ecard_data['ecards_image_h'];
                $layout_table_to_use = 'ecard_layouts_h';
                $layout_table_id_to_use = 'ecard_layout_h_id';
            } else {
                $ecard_file_name = $this->ecard_data['ecards_image_v'];
                $layout_table_to_use = 'ecard_layouts_v';
                $layout_table_id_to_use = 'ecard_layout_v_id';
            }

            /**
             * LAYOUT INFO
             */
            $layout_table = new \k1lib\crudlexs\class_db_table($db, $layout_table_to_use);
            $layout_table->set_query_filter([$layout_table_id_to_use => $this->ecard_data[$layout_table_id_to_use]]);
            $this->layout_data = $layout_table->get_data(FALSE);

            if (empty($this->layout_data)) {
                $error = 'Layout data do not exist';
                DOM_notifications::queue_mesasage($error, "alert");
            }

            /**
             * IMAGE LOAD
             */
            if (!empty($ecard_file_name)) {

                $this->load_message($send_id);

                $ecard_file = \k1lib\forms\file_uploads::get_uploaded_file_path($ecard_file_name);

                if (file_exists($ecard_file)) {
//                    $ecard_url = \k1lib\forms\file_uploads::get_uploaded_file_url($ecard_file_name);

                    try {
                        $this->imagick = new \Imagick();
                        $this->imagick->readImage($ecard_file);
                    } catch (Exception $e) {
                        DOM_notifications::queue_mesasage('Error when loading the ecard file: ' . $e->getMessage(), "alert");
                    }
                }
            }
        } else {
            $error = 'eCard ID do not exist';
            DOM_notifications::queue_mesasage($error, "alert");
        }
    }

    /**
     * @return \Imagick | boolean
     */
    public function get_ecard_imagick() {
        if (!empty($this->imagick)) {
            return $this->_compose_ecard();
        } else {
            return NULL;
        }
    }

    /**
     * @return string
     */
    public function get_ecard_base64() {
        if ($this->_compose_ecard()) {
            return base64_encode($this->imagick);
        } else {
            return NULL;
        }
    }

    /**
     * @return string
     */
    public function get_ecard_img_tag() {
        if (!empty($this->imagick)) {
            $img_tag = new \k1lib\html\img($this->get_ecard_src_base64(), 'eCard generated', 'ecard-img');
            return $img_tag;
        } else {
            $img_tag = new \k1lib\html\img($this->no_img, 'eCard no file', 'ecard-img');
            return $img_tag;
        }
    }

    /**
     * @return string
     */
    public function get_ecard_src_base64() {
        if (!empty($this->imagick)) {
            return 'data: image/jpg ;base64,' . $this->get_ecard_base64();
        } else {
            return $this->no_img;
        }
    }

    /**
     * @return \Imagick | boolean
     */
    private function _compose_ecard() {

        if (!empty($this->imagick)) {
            if (!empty($this->layout_data)) {
                // Create a new drawing palette
                $this->draw_to = new \ImagickDraw();
                $this->draw_from = new \ImagickDraw();
                $this->draw_message = new \ImagickDraw();

                // Set TO properties
                $this->draw_to->setFont(APP_FONTS_PATH . $this->ecard_data['ecard_font']);
                $this->draw_to->setFontSize($this->layout_data['el_to_size']);
                $this->draw_to->setFillColor($this->layout_data['el_to_rgb_color']);

                // Set FROM properties
                $this->draw_from->setFont(APP_FONTS_PATH . $this->ecard_data['ecard_font']);
                $this->draw_from->setFontSize($this->layout_data['el_from_size']);
                $this->draw_from->setFillColor($this->layout_data['el_from_rgb_color']);

                // Set font properties
                $this->draw_message->setFont(APP_FONTS_PATH . $this->ecard_data['ecard_font']);
                $this->draw_message->setFontSize($this->layout_data['el_message_size']);
                $this->draw_message->setFillColor($this->layout_data['el_message_rgb_color']);
                $this->draw_message->settextinterlinespacing($this->layout_data['el_message_line_space']);

                // Draw text on the image
                $this->imagick->annotateImage($this->draw_to, $this->layout_data['el_to_x'], $this->layout_data['el_to_y'], 0, $this->send_data['send_to_name']);
                $this->imagick->annotateImage($this->draw_from, $this->layout_data['el_from_x'], $this->layout_data['el_from_y'], 0, $this->send_data['send_from_name']);
                $this->imagick->annotateImage($this->draw_message, $this->layout_data['el_message_x'], $this->layout_data['el_message_y'], 0, $this->message_to_lines());
                $this->imagick->drawimage($this->draw_message);
            }

            // Set output image format
            $this->imagick->thumbnailImage($this->imagick->getimagewidth() * $this->image_proportion, $this->imagick->getimageheight() * $this->image_proportion);
            $this->imagick->setimageformat('jpg');

            return $this->imagick;
        } else {
            $error = 'No image present to compose eCard';
            DOM_notifications::queue_mesasage($error, "alert");

            return FALSE;
        }
    }

    public
            function load_message($send_id = NULL, $custom_data = array()) {
        if (!empty($send_id)) {
            global $db;
            $ecard_send_table = new \k1lib\crudlexs\class_db_table($db, 'ecard_sends');
            $ecard_send_table->set_query_filter(['send_id' => $send_id]);
            $this->send_data = $ecard_send_table->get_data(FALSE);

            if (empty($this->send_data)) {
                $error = 'Send data do not exist';
                trigger_error($error, E_USER_WARNING);
                DOM_notifications::queue_mesasage($error, "alert");
            }
        } elseif (!empty($custom_data)) {
            $this->send_data = $custom_data;
        } else {
            $this->send_data = [
                'send_to_name' => 'My dear friend',
                'send_from_name' => 'Yout best friend',
                'send_message' => 'Lorem ipsum dolor sit amet, consectetur adipiscing elit. Sed risus odio, vehicula sed nibh non, tempus ultricies orci. Sed diam eros, molestie at nisi sed, tristique lacinia nibh. Vivamus aliquam purus enim, a feugiat erat dignissim eget imperdiet ornare.',
            ];
        }
    }

    public function message_to_lines() {
        $words = explode(" ", $this->send_data['send_message']);

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
                $metrics = $this->imagick->queryFontMetrics($this->draw_message, $linePreview);
            } while ($metrics["textWidth"] <= $this->layout_data['el_message_width']);

            $lines .= $line . "\n";
        }
        return $lines;
    }

}
