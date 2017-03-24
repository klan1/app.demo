<?php

namespace k1app;

use \k1lib\urlrewrite\url as url;
use \k1lib\html\script as script;

require 'ecard-generation.php';

$body = frontend::html()->body();
$head = frontend::html()->head();

$head->append_child_tail(new script(APP_TEMPLATE_URL . "js/jscolor.min.js"));

global $ecard_data, $ecard_mode, $category_data;

if (!empty($ecard_data)) :

    switch ($ecard_mode) {
        case 'h':
            $ecard = new ecard_generator($ecard_data['ecard_id'], ECARD_HORIZONTAL);
            $ecard_mode = 'h';
            break;
        case 'v':
            $ecard = new ecard_generator($ecard_data['ecard_id'], ECARD_VERTICAL);
            $ecard_mode = 'v';
            break;

        default:
            break;
    }

    // POST MANAGEMENT AND DEFAULTS VALUES
    if (!empty($_POST)) {
        $post_data = \k1lib\forms\check_all_incomming_vars($_POST, 'last_post');
    } else {
        $post_data = \k1lib\common\unserialize_var('last_post');
    }
    $default_message = 'Please write your custom message using form below this Ecard.';

    if (!empty($post_data)) {
        $_POST = $post_data;
        $post_data['mode'] = '';
        $custom_data = [
            'send_to_name' => $post_data['sender_name'],
            'send_from_name' => $post_data['recipent_name'],
            'send_message' => (!empty($post_data['user_message'] && strlen($post_data['user_message']) > 50) ? $post_data['user_message'] : $default_message),
        ];
    } else {
        $custom_data = [
            'send_to_name' => '',
            'send_from_name' => '',
            'send_message' => $default_message,
        ];
        $post_data = [
            'color' => '000000',
            'recipent_email' => '',
            'size' => '0',
        ];
    }
    // VALIDATE VALID ECARDS VALUES
    //TODO: check every value is is allowed
    // SEND MODE
    if ($post_data['mode'] == 'send') {
        $send_data = [
            'ecard_id' => $ecard_data['ecard_id'],
            'ecard_data_array' => $ecard_data,
            'ecard_mode' => $ecard_mode,
            'send_to_email' => $post_data['recipent_email'],
            'send_from_name' => $custom_data['send_from_name'],
            'send_to_name' => $custom_data['send_to_name'],
            'send_to_email' => $post_data['recipent_email'],
            'send_message' => $custom_data['send_message'],
            'send_font_file' => get_ecard_font_by_name($post_data['font']),
            'send_font_size' => $post_data['size'],
            'send_font_color' => $post_data['color'],
        ];
        \k1lib\common\serialize_var($send_data, 'send-data');
        $new_url = str_replace('step1', 'step2', APP_URL . url::get_this_url());

        \k1lib\html\html_header_go($new_url);
    } elseif (($post_data['mode'] == 'preview') || (empty($post_data['mode']))) {     // PREVIEW MODE

        /**
         * INPUTS
         */
        // YOUR NAME
        $send_from_name = new \k1lib\html\input('text', 'sender_name', $custom_data['send_from_name']);
        $send_from_name->set_attrib('placeholder', 'Your name');
        // RECIPENTS'S NAME
        $send_to_name = new \k1lib\html\input('text', 'recipent_name', $custom_data['send_to_name']);
        $send_to_name->set_attrib('placeholder', 'Recipent\'s name');
        // E-MAIL
        $send_to_email = new \k1lib\html\input('text', 'recipent_email', $post_data['recipent_email']);
        $send_to_email->set_attrib('placeholder', 'E-mail');
        // MESSAGE
        $user_message = new \k1lib\html\textarea('user_message');
        $user_message->set_attrib('rows', 5);
        $user_message->set_value($custom_data['send_message'] == $default_message ? '' : $custom_data['send_message']);
        // FONT
        $font = new \k1lib\html\select('font');
        $ecard_fonts = get_ecard_fonts();
        $font_selected = FALSE;
        foreach ($ecard_fonts as $font_file => $font_name) {
            $li = $font->append_option($font_name, $font_name);
            if (!empty($post_data['font'])) {
                if (!$font_selected && ($font_name == $post_data['font'])) {
                    if (!$font_selected) {
                        $li->set_attrib('selected', TRUE);
                        $ecard->set_custom_font_file($font_file);
                        $font_selected = TRUE;
                    }
                }
            } else {
                if (!$font_selected && $font_name == $ecard_fonts[$ecard_data['ecard_font']]) {
                    if (!$font_selected) {
                        $li->set_attrib('selected', TRUE);
                        $ecard->set_custom_font_file($font_file);
                        $font_selected = TRUE;
                    }
                }
            }
        }
        // FONT SIZE
        $font_size = new \k1lib\html\select('size');
        $ecard_font_sizes = get_ecard_font_sizes($post_data['font']);
        $font_size_select = FALSE;
        foreach ($ecard_font_sizes as $font_size_px => $size_number) {
            $li = $font_size->append_option($size_number, $size_number);
            if ($size_number == $post_data['size']) {
                if (!$font_size_select) {
                    $li->set_attrib('selected', TRUE);
                    $ecard->set_custom_font_size($font_size_px);
                    $font_size_select = TRUE;
                }
            }
        }
        // COLOR
        $color = new \k1lib\html\input('text', 'color', $post_data['color'], 'jscolor');
        $ecard->set_custom_font_color($post_data['color']);

        $ecard->load_message(NULL, $custom_data);
        $ecard->use_watermark();
        $ecard->set_image_proportion(0.7);
        $ecard->set_quality(70);
        ?>
        <!-- <?php echo basename(__FILE__) ?> -->
        <div class="slide-inner">
            <ul class="steps clearfix">
                <li class="selected"><a href="#"><span>Step 01</span>Write your message</a></li>
                <li><a href="#"><span>Step 02</span>Make someone happy</a></li>
                <li><a href="#"><span>Step 03</span>Send your love</a></li>
            </ul>
        </div>    
        <div class="inner-content">
            <div class="container">
                <div class="title">
                    Eebunny - Ecards
                </div>
                <div class="subtitle">
                    Custom your Ecard
                </div>
                <div class="card" id="preview">
                    <?php echo $ecard->get_ecard_img_tag(); ?>

                    <span class="eggs"><?php echo $category_data['ecc_name'] ?></span>
                </div>
                <div class="orientation">
                    <a href="../h/" class="horizontal selected" data-orientation="horizontal">
                        <img src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>horizontal.png" alt="horizontal"/>
                    </a>
                    <a href="../v/" class="vertical" data-orientation="vertical">
                        <img src="<?php echo APP_TEMPLATE_IMAGES_URL; ?>vertical.png" alt="vertical"/>
                    </a>
                </div>
                <div class="title">Select</div>
                <form id="ecard-customizer" class="eebunny-form users-data clearfix" method="post" action="./#preview">
                    <div class="col1">
                        <!--<input type="hidden" class="card-orientation" name="orientation" value="">-->
                        <label>From</label>
                        <div class="input-wrap">
                            <?php echo $send_from_name ?>
                        </div>
                        <label>To</label>
                        <div class="input-wrap">
                            <?php echo $send_to_name ?>
                        </div>
                        <div class="input-wrap">
                            <?php echo $send_to_email ?>
                        </div>
                    </div>
                    <div class="col2">
                        <div class="optional-message">
                            <label>Optional message</label>
                            <div class="input-wrap">
                                <select name="message">
                                    <option>Select message</option>
                                    <option value="message1">Message 1</option>
                                    <option value="message2">Message 2</option>
                                    <option value="message3">Message 3</option>
                                </select>
                            </div>
                        </div>
                        <table>
                            <tr>
                                <td width="50%">
                                    <label>Font</label>
                                    <div class="input-wrap">
                                        <?php echo $font ?>
                                    </div>
                                </td>
                                <td width="25%" class="font-size">
                                    <label>Size</label>
                                    <div class="input-wrap">
                                        <?php echo $font_size ?>
                                    </div>
                                </td>
                                <td width="25%">
                                    <label>Color</label>
                                    <div class="input-wrap">    
                                        <?php echo $color ?>
                                    </div>
                                </td>
                            </tr>
                        </table>
                        <div class="personal-message">
                            <label>Type message</label>
                            <div class="input-wrap">
                                <?php echo $user_message ?>
                            </div>
                        </div>
                        <div class="buttons-wrap">
                            <input id="form-mode" type="hidden" name="mode" value="preview">
                            <input id="btn-preview" type="button" name="preview" value="Preview"/>
                            <?php if (($post_data['mode'] == 'preview')) : // PREVIEW MODE  ?>
                                <input id="btn-send" type="button" name="send" value="Send"/>
                            <?php endif ?>
                        </div>
                    </div>
                </form>

            </div>
        </div>                
    <?php } // PREVIEW MODE     ?>
<?php endif; ?>