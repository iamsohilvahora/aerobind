<?php

defined('ABSPATH') || exit;		// Exit if accessed directly

if(! class_exists(PvalleyURBSActionOnShippingMethodType::class)) {
	final class PvalleyURBSActionOnShippingMethodType {
		/**
		 * Perform action on shipping method label name.
		 * Note - Don't change the value, Used in db.
		 * @see https://woocommerce.github.io/code-reference/classes/WC-Shipping-Rate.html#method_get_id
		 */
		const LABEL = "ShippingMethodlabel";
		/**
		 * Perform action on shipping method id.
		 * Note - Don't change the value, Used in db.
		 * @see https://woocommerce.github.io/code-reference/classes/WC-Shipping-Rate.html#method_get_id
		 */
		const METHOD_ID = "ShippingMethodId";

		/**
		 * Shows option to admin.
		 * Note - You are allowed to change name but not value.
		 * @return PvalleyDropdownOptions[]
		 */
		public static function dropDownOptions() {
			return [
				new PvalleyDropdownOptions("Label", self::LABEL),
				new PvalleyDropdownOptions("Value", self::METHOD_ID)
			];
		}
	}
}
