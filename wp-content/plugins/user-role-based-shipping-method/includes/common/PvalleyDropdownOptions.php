<?php

defined('ABSPATH') || exit;		// Exit if accessed directly

if(! class_exists(PvalleyDropdownOptions::class)) {
    final class PvalleyDropdownOptions {
        /**
         * Use it for display purpose only.
         */
        public $name;
        public $value;
        /**
         * Constructor.
         * @param name Name that will be shown to the user
         * @param value Value that will be used for operations
         */
        public function __construct($name, $value) {
            $this->name = $name;
            $this->value = $value;
        }
    }
}
