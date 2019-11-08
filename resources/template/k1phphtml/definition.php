<?php

namespace k1app;

use k1lib\html\foundation\off_canvas as off_canvas;
use k1lib\html\foundation\title_bar as title_bar;
use k1lib\html\foundation\top_bar as top_bar;
use k1lib\html\foundation\menu as menu;

class k1app_template extends \k1lib\html\DOM {

    /**
     * @var off_canvas
     */
    static protected $off_canvas;

    /**
     * @var title_bar
     */
    static protected $title_bar;

    /**
     * @var top_bar
     */
    static protected $top_bar;

    static public function start_template($bars = TRUE, $left = TRUE, $right = FALSE) {
        if ($left || $right) {
            self::$off_canvas = new off_canvas(self::html()->body());
            if ($left) {
                self::$off_canvas->left();
                // APP LOGO
                self::$off_canvas->left()->append_div(NULL, 'app-logo')
                        ->append_child(new \k1lib\html\img(APP_TEMPLATE_IMAGES_URL . 'klan1-white.png', 'app-logo-img', '', 'logo-img'));

                self::$off_canvas->menu_left_head();
                self::$off_canvas->menu_left();
                self::$off_canvas->menu_left_tail();
            }
            if ($right) {
                self::$off_canvas->right();
            }
            self::html()->body()->init_sections(self::$off_canvas->content());
        } else {
            self::html()->body()->init_sections();
        }

        if ($bars) {
            /**
             * TITLE BAR
             */
            self::$title_bar = new title_bar();

            self::$title_bar->append_to(self::html()->body()->header());
            self::$title_bar->left_button()
                    ->set_attrib('data-open', 'offCanvasLeft');
            self::$title_bar->title()->append_span("k1lib-title-1");
            self::$title_bar->title()->append_span("k1lib-title-2");
            self::$title_bar->title()->append_span("k1lib-title-3");
        }
    }

    static public function start_template_plain() {
        self::start_template(FALSE, FALSE, FALSE);
    }

    static public function start_template_full() {
        self::start_template(TRUE, TRUE, TRUE);
    }

    /**
     * @return off_canvas
     */
    static public function off_canvas() {
        return self::$off_canvas;
    }

    /**
     * @return menu
     */
    static public function menu_left() {
        if (!empty(self::$off_canvas)) {
            return self::$off_canvas->menu_left();
        } elseif (!empty(self::$top_bar)) {
            return self::$top_bar->menu_left();
        }
        return NULL;
    }

    /**
     * @return menu
     */
    static public function menu_left_head() {
        if (!empty(self::$off_canvas)) {
            return self::$off_canvas->menu_left_head();
        } elseif (!empty(self::$top_bar)) {
            return self::$top_bar->menu_left();
        }
        return NULL;
    }

    /**
     * @return menu
     */
    static public function menu_left_tail() {
        if (!empty(self::$off_canvas)) {
            return self::$off_canvas->menu_left_tail();
        } elseif (!empty(self::$top_bar)) {
            return self::$top_bar->menu_left();
        }
        return NULL;
    }

    /**
     * @return title_bar
     */
    public static function title_bar() {
        return self::$title_bar;
    }

    /**
     * @return top_bar
     */
    public static function top_bar() {
        return self::$top_bar;
    }

    static public function set_title($number, $value, $append = FALSE) {
        $elements = self::html()->body()->header()->get_elements_by_class("k1lib-title-{$number}");
        foreach ($elements as $element) {
            $element->set_value($value, $append);
        }
    }

}
