<?php

namespace k1app;

use k1lib\html\foundation\off_canvas as off_canvas;
use k1lib\html\foundation\title_bar as title_bar;
use k1lib\html\foundation\top_bar as top_bar;

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

    static public function start($lang = "en", $left = TRUE, $right = FALSE) {
        parent::start($lang);
        self::$off_canvas = new off_canvas(self::html()->body());
        if ($left) {
            self::$off_canvas->left();
            self::$off_canvas->left_menu_head();
            self::$off_canvas->left_menu();
            self::$off_canvas->left_menu_tail();
        }
        if ($right) {
            self::$off_canvas->right();
        }
        self::html()->body()->init_sections(self::$off_canvas->content());

        /**
         * TITLE BAR
         */
        self::$title_bar = new title_bar();
        self::$top_bar = new top_bar();

        self::$title_bar->append_to(self::html()->body()->header());
        self::$title_bar->set_class('hide-for-large', TRUE);
        self::$title_bar->left_button()->set_attrib('data-open', 'offCanvasLeft');
        self::$title_bar->title()->append_span("k1lib-title-1");
        self::$title_bar->title()->append_span("k1lib-title-2");
        self::$title_bar->title()->append_span("k1lib-title-3");

        self::$top_bar->append_to(self::html()->body()->header());
        self::$top_bar->set_class('show-for-large', TRUE);
        self::$top_bar->title()->append_span("k1lib-title-1");
        self::$top_bar->title()->append_span("k1lib-title-2");
        self::$top_bar->title()->append_span("k1lib-title-3");
    }

    /**
     * @return off_canvas
     */
    static public function off_canvas() {
        return self::$off_canvas;
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
