<?php

namespace k1app;

use k1lib\notifications\on_DOM as DOM_notifications;

//use k1lib\html\template as template;
//use \k1lib\urlrewrite\url as url;
//use k1app\k1app_template as DOM;
//use k1lib\session\session_db as session_db;

const ECARD_HORIZONTAL = 1;
const ECARD_VERTICAL = 2;
const ECARD_THUMB_WIDTH = 246;
const ECARD_THUMB_HEIGHT = 143;

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

function get_ecard_thumbnail($filename, $width = ECARD_THUMB_WIDTH, $height = ECARD_THUMB_HEIGHT) {
    $ecards_table = 'ecards';
    $thumbnail_resize_folder = APP_RESOURCES_PATH . 'images/thumbnails/';
    $thumbnail_resize_url = APP_RESOURCES_URL . 'images/thumbnails/';
    $thumbnail_file = \k1lib\forms\file_uploads::get_uploaded_file_path($filename, $ecards_table);

    if (!empty($filename) && file_exists($thumbnail_file)) {
        $just_filename = strstr(basename($filename), '.', TRUE);

        $thumbnail_file_resized = $thumbnail_resize_folder . $just_filename . '_' . $width . 'x' . $height . '.jpg';
        $thumbnail_file_resized_url = $thumbnail_resize_url . $just_filename . '_' . $width . 'x' . $height . '.jpg';
        if (file_exists($thumbnail_file_resized)) {
            return $thumbnail_file_resized_url;
        } else {
            try {
                $imagick = new \Imagick();
                $imagick->readImage($thumbnail_file);
            } catch (Exception $e) {
                DOM_notifications::queue_mesasage('Error when loading the thumbnail file: ' . $e->getMessage(), "alert");
            }
            $imagick->thumbnailimage($width, $height);
            $imagick->setformat('jpg');
            $imagick->setcompression(\Imagick::COMPRESSION_JPEG);
            $imagick->setimagecompressionquality(80);
            $imagick->writeimage($thumbnail_file_resized);
            return $thumbnail_file_resized_url;
        }
    } else {
        return $thumbnail_resize_url . 'no-thumbnail.jpg';
    }
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
    private $image_proportion = 1;
    private $no_img = APP_RESOURCES_URL . 'images/no-image-available.jpg';
    private $make_shadow = TRUE;
    private $shadow_offset = 3;
    private $shadow_color = 'FFFFFF';
    private $generated = FALSE;

    public function __construct($ecard_id, $mode = ECARD_HORIZONTAL, $send_id = NULL) {

        $this->ecard_id = $ecard_id;
        $this->ecard_mode = $mode;
        $this->send_id = $send_id;

        $this->load_ecard_data();
    }

    public function load_ecard_data() {
        global $db;

        /**
         * ECARD DATA LOAD
         */
        $ecards_table = 'ecards';
        $ecard_table = new \k1lib\crudlexs\class_db_table($db, $ecards_table);
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

            $this->make_shadow = ($this->layout_data['el_use_shadow'] == '1') ? TRUE : FALSE;
            $this->shadow_color = (!empty($this->layout_data['el_shadow_hex_color'])) ? $this->layout_data['el_shadow_hex_color'] : 'FFFFFF';

            if (empty($this->layout_data)) {
                $error = 'Layout data do not exist';
                DOM_notifications::queue_mesasage($error, "alert");
            }

            /**
             * IMAGE LOAD
             */
            if (!empty($ecard_file_name)) {

                $this->load_message();

                $ecard_file = \k1lib\forms\file_uploads::get_uploaded_file_path($ecard_file_name, $ecards_table);

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
    public function get_ecard_base64($image_proportion = NULL) {
        if (($image_proportion > 0) && ($image_proportion < 1)) {
            $this->image_proportion = $image_proportion;
        }
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
            return 'data:image/jpg;base64,' . $this->get_ecard_base64();
        } else {
            return $this->no_img;
        }
    }

    /**
     * @return \Imagick | boolean
     */
    private function _compose_ecard() {
        if ($this->generated) {
            $this->load_ecard_data();
        }
        if (!empty($this->imagick)) {
            if (!empty($this->layout_data)) {
                // Create a new drawing palette
                $this->draw_to = new \ImagickDraw();
                $this->draw_from = new \ImagickDraw();
                $this->draw_message = new \ImagickDraw();

                // Set TO properties
                $this->draw_to->setFont(APP_FONTS_PATH . $this->ecard_data['ecard_font']);
                $this->draw_to->setFontSize($this->layout_data['el_to_size']);
                if (empty($this->layout_data['el_to_hex_color'])) {
                    $this->layout_data['el_to_hex_color'] = '#000000';
                }
                try {
                    $color_to = new Color($this->layout_data['el_to_hex_color']);
                } catch (Exception $e) {
                    DOM_notifications::queue_mesasage('Error when loading the ecard file: ' . $e->getMessage(), "alert");
                }

                // Set FROM properties
                $this->draw_from->setFont(APP_FONTS_PATH . $this->ecard_data['ecard_font']);
                $this->draw_from->setFontSize($this->layout_data['el_from_size']);
                if (empty($this->layout_data['el_from_hex_color'])) {
                    $this->layout_data['el_from_hex_color'] = '#000000';
                }
                try {
                    $color_from = new Color($this->layout_data['el_from_hex_color']);
                } catch (Exception $e) {
                    DOM_notifications::queue_mesasage('Error when loading the ecard file: ' . $e->getMessage(), "alert");
                }

                // Set font properties
                $this->draw_message->setFont(APP_FONTS_PATH . $this->ecard_data['ecard_font']);
                $this->draw_message->setFontSize($this->layout_data['el_message_size']);
                if (empty($this->layout_data['el_message_hex_color'])) {
                    $this->layout_data['el_message_hex_color'] = '#000000';
                }
                try {
                    $color_message = new Color($this->layout_data['el_message_hex_color']);
                } catch (Exception $e) {
                    DOM_notifications::queue_mesasage('Error when loading the ecard file: ' . $e->getMessage(), "alert");
                }
                $this->draw_message->settextinterlinespacing($this->layout_data['el_message_line_space']);

                // Draw text on the image
                if ($this->make_shadow) {
                    $shadow_color = new Color($this->shadow_color);
                    $this->draw_to->setFillColor($shadow_color->getRgbString());
                    $this->imagick->annotateImage($this->draw_to, $this->layout_data['el_to_x'] + $this->shadow_offset, $this->layout_data['el_to_y'] + $this->shadow_offset, 0, $this->send_data['send_to_name']);
                }
                $this->draw_to->setFillColor($color_to->getRgbString());
                $this->imagick->annotateImage($this->draw_to, $this->layout_data['el_to_x'], $this->layout_data['el_to_y'], 0, $this->send_data['send_to_name']);

                if ($this->make_shadow) {
                    $this->draw_from->setFillColor($shadow_color->getRgbString());
                    $this->imagick->annotateImage($this->draw_from, $this->layout_data['el_from_x'] + $this->shadow_offset, $this->layout_data['el_from_y'] + $this->shadow_offset, 0, $this->send_data['send_from_name']);
                }
                $this->draw_from->setFillColor($color_from->getRgbString());
                $this->imagick->annotateImage($this->draw_from, $this->layout_data['el_from_x'], $this->layout_data['el_from_y'], 0, $this->send_data['send_from_name']);

                if ($this->make_shadow) {
                    $this->draw_message->setFillColor($shadow_color->getRgbString());
                    $this->imagick->annotateImage($this->draw_message, $this->layout_data['el_message_x'] + $this->shadow_offset, $this->layout_data['el_message_y'] + $this->shadow_offset, 0, $this->message_to_lines());
                }
                $this->draw_message->setFillColor($color_message->getRgbString());
                $this->imagick->annotateImage($this->draw_message, $this->layout_data['el_message_x'], $this->layout_data['el_message_y'], 0, $this->message_to_lines());


                $this->imagick->drawimage($this->draw_message);
                $this->generated = TRUE;
            }

            // Set output image format
            if (($this->image_proportion > 0) && ($this->image_proportion < 1)) {
                $this->imagick->thumbnailImage($this->imagick->getimagewidth() * $this->image_proportion, $this->imagick->getimageheight() * $this->image_proportion);
            }
            $this->imagick->setimageformat('jpg');

            return $this->imagick;
        } else {
            $error = 'No image present to compose eCard';
            DOM_notifications::queue_mesasage($error, "alert");

            return FALSE;
        }
    }

//    private imagicj

    public function load_message($send_id = NULL, $custom_data = array()) {
        if (!empty($send_id)) {
            $this->send_id = $send_id;
        }
        if (!empty($this->send_id)) {
            global $db;
            $ecard_send_table = new \k1lib\crudlexs\class_db_table($db, 'ecard_sends');
            $ecard_send_table->set_query_filter(['send_id' => $this->send_id]);
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

    function send_email($send_to = NULL, $dev_copy = FALSE) {
        if ($send_to == NULL) {
            $send_to = $this->send_data['send_to_email'];
        }

        $mail = new \PHPMailer;

        $mail->IsSMTP();                                      // Set mailer to use SMTP
        $mail->Host = 'smtp.mandrillapp.com';                 // Specify main and backup server
        $mail->Port = 587;                                    // Set the SMTP port
        $mail->SMTPAuth = true;                               // Enable SMTP authentication
        $mail->Username = 'EEBunny';                // SMTP username
        $mail->Password = 'Gn5TA04jtDb5EDwf1tZwKQ';                  // SMTP password
        $mail->SMTPSecure = 'tls';                            // Enable encryption, 'ssl' also accepted

        $mail->From = 'noreply@eebunny.com';
        $mail->FromName = $this->send_data['send_from_name'];
        $mail->AddAddress($send_to, $this->send_data['send_to_name']);  // Add a recipient
        if ($dev_copy) {
            $mail->AddAddress('alejo@klan1.com', "God developer");  // Add a recipient
        }

        $mail->IsHTML(true);                                  // Set email format to HTML

        $mail->Subject = 'Ecard from ' . $this->send_data['send_from_name'];
        $mail->Body = $this->make_email_html();
        $mail->addStringAttachment($this->get_ecard_imagick(), 'ecard-attached.jpg', 'base64', 'image/jpg', 'attachment');
        $mail->addStringEmbeddedImage($this->get_ecard_imagick(), "000ECARD000", 'ecard-inline.jpg', 'base64', 'image/jpg');
        $mail->AltBody = 'You have received an Electronic Easter Bunny Card!';

        if ($mail->Send()) {
            DOM_notifications::queue_mesasage('Message has been sent', 'sucess');
        } else {
            DOM_notifications::queue_mesasage('Message could not be sent.', 'alert');
            DOM_notifications::queue_mesasage('Mailer Error: ' . $mail->ErrorInfo, 'alert');
        }
    }

    function make_email_html() {
        $doc_type = '<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">';
        $html = new \k1lib\html\html('en');
        $html->set_attrib('xmlns', 'http://www.w3.org/1999/xhtml');

        $body = new \k1lib\html\body();
        $body->append_to($html);

        $body->append_h3('Enjoy your eCard!');

        $ecard_img_tag = new \k1lib\html\img('cid:000ECARD000');
        $ecard_img_tag->set_style('max-width:100%');

        $body->append_child($ecard_img_tag);
        return $doc_type . "\n" . $html->generate();
    }

    function get_layout_data() {
        return $this->layout_data;
    }

    function get_ecard_id() {
        return $this->ecard_id;
    }

    function get_ecard_data() {
        return $this->ecard_data;
    }

    function get_ecard_mode() {
        return $this->ecard_mode;
    }

    function get_send_id() {
        return $this->send_id;
    }

    function get_send_data() {
        return $this->send_data;
    }

    function get_make_shadow() {
        return $this->make_shadow;
    }

    function get_shadow_offset() {
        return $this->shadow_offset;
    }

    function get_image_proportion() {
        return $this->image_proportion;
    }

    function set_image_proportion($image_proportion) {
        $this->image_proportion = $image_proportion;
    }

    function set_layout_data($layout_data) {
        $this->layout_data = $layout_data;
    }

    function set_ecard_id($ecard_id) {
        $this->ecard_id = $ecard_id;
    }

    function set_ecard_data($ecard_data) {
        $this->ecard_data = $ecard_data;
    }

    function set_ecard_mode($ecard_mode) {
        $this->ecard_mode = $ecard_mode;
    }

    function set_send_id($send_id) {
        $this->send_id = $send_id;
    }

    function set_send_data($send_data) {
        $this->send_data = $send_data;
    }

    function set_make_shadow($make_shadow) {
        $this->make_shadow = $make_shadow;
    }

    function set_shadow_offset($shadow_offset) {
        $this->shadow_offset = $shadow_offset;
    }

}

class Color extends \Mexitek\PHPColors\Color {

    public function getRgbString($hex = null) {
        if (!empty($hex)) {
            $rgb = $this->hexToRgb($hex);
        } else {
            $rgb = $this->getRgb();
        }
        return "rgb({$rgb['R']}, {$rgb['G']}, {$rgb['B']})";
    }

    public function inverse() {
        $color = str_replace('#', '', $this->getHex());
        if (strlen($color) != 6) {
            return '000000';
        }
        $rgb = '';
        for ($x = 0; $x < 3; $x++) {
            $c = 255 - hexdec(substr($color, (2 * $x), 2));
            $c = ($c < 0) ? 0 : dechex($c);
            $rgb .= (strlen($c) < 2) ? '0' . $c : $c;
        }
        return '#' . $rgb;
    }

}
