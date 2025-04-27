<?php

defined( 'ABSPATH') || exit;	// Exit if accessed directly.

class Pvalley_Role_Based_Shipping_Rule_Matrix {
	public function __construct() {
		! empty($this->rule_matrix) || $this->rule_matrix = get_option( 'pvalley_user_role_based_shipping_rule_matrix', array() );
		$this->init();
	}

	public function init() {
		$this->shipping_countries = WC()->countries->get_shipping_countries();
		$this->user_roles 		= get_editable_roles();
		$this->actionOnShippingMethodsDropdownOptions = PvalleyURBSActionOnShippingMethodType::dropDownOptions();

		// Add Guest User, It's not available in editable roles by default
		if( ! isset( $this->user_roles[Pvalley_User_Role_Based_Shipping_Common::GUEST_ROLE_KEY] ) ) {
			$this->user_roles[Pvalley_User_Role_Based_Shipping_Common::GUEST_ROLE_KEY] = [
				"name"	=>	Pvalley_User_Role_Based_Shipping_Common::GUEST_ROLE_NAME,
				"capabilities"	=>	[
					"read"	=>	1,
					"level_0"	=>	1
				]
			];
		}

		if( empty($this->country_options) ) {
			$this->country_options = null;
			foreach( $this->shipping_countries as $code => $name ) {
				$this->country_options.="<option value=$code>".$name."</option>";
			}
		}

		if(empty($this->user_roles_options)) {
			$this->user_roles_options = null;
			foreach( $this->user_roles as $code => $data ) {
				$this->user_roles_options.="<option value=$code>".$data['name']."</option>";
			}
		}

		?>
		<table class ="wp-list-table widefat fixed posts">
			<tr>
				<th> <?php _e( "User Role", "ph-skip-shipping-calculation"); ?> </th>
				<th> <?php _e( "Countries", "ph-skip-shipping-calculation"); ?> </th>
				<th> <?php _e( "Action On Shipping Method","ph-skip-shipping-calculation"); ?> </th>
				<th> <?php _e( "Shipping Methods","ph-skip-shipping-calculation"); ?> </th>
			</tr>
			<?php
			$rule_count = 0;
			foreach( $this->rule_matrix as $rule ) {
				?>
					<tr>
						<td>
							<select class="wc-enhanced-select xa_est_shipping_class" multiple="multiple" style="width: 70%;" name="pvalley_user_role_based_shipping_rule_matrix[<?php echo $rule_count; ?>][user_roles][]">
								<?php
									foreach( $this->user_roles as $user_role_key => $user_role_val )
									{
										if( in_array($user_role_key, $rule['user_roles']) ) {
											echo "<option value='$user_role_key' selected >".$user_role_val['name']."</option>";
										}
										else{
											echo "<option value='$user_role_key' >".$user_role_val['name']."</option>";
										}
									}
								?>
							</select>
						</td>
						<td>
							<select class="wc-enhanced-select xa_est_shipping_class" multiple="multiple" style="width: 70%;" name="pvalley_user_role_based_shipping_rule_matrix[<?php echo $rule_count; ?>][countries][]">
								<?php
									foreach( $this->shipping_countries as $country_code => $country_name )
									{
										if( in_array($country_code, $rule['countries']) ) {
											echo "<option value='$country_code' selected >".$country_name."</option>";
										}
										else{
											echo "<option value='$country_code' >".$country_name."</option>";
										}
									}
								?>
							</select>
						</td>
						<td>
							<select class="wc-enhanced-select xa_est_shipping_class" style="width: 70%;" name="pvalley_user_role_based_shipping_rule_matrix[<?php echo $rule_count; ?>][actionOnShippingMethod]">
								<?php
									foreach( $this->actionOnShippingMethodsDropdownOptions as $option ) {
										if(! array_key_exists("actionOnShippingMethod", $rule) || $option->value == $rule['actionOnShippingMethod']) {
											echo "<option value='$option->value' selected>".$option->name."</option>";
										}
										else {
											echo "<option value='$option->value'>".$option->name."</option>";
										}
									}
								?>
							</select>
						</td>
						<td>
							<input type="text" style="width:100%" name="pvalley_user_role_based_shipping_rule_matrix[<?php echo $rule_count; ?>][shipping_methods]" placeholder="flat_rate:1; free_shipping:2" value="<?php echo $rule['shipping_methods'] ?>" >
						</td>
					</tr>
				<?php
				$rule_count++;
			}
		?>
			<!--New Entry Empty form-->
			<tr>
				<td>
					<select class="wc-enhanced-select xa_est_shipping_class" multiple="multiple" style="width: 70%;" name="pvalley_user_role_based_shipping_rule_matrix[<?php echo $rule_count; ?>][user_roles][]">
						<?php
							// echo wp_dropdown_roles();
							foreach( $this->user_roles as $user_role_key => $user_role_val ) {
								echo "<option value='$user_role_key' >".$user_role_val['name']."</option>";
							}
						?>
					</select>
				</td>
				<td>
					<select class="wc-enhanced-select xa_est_shipping_class" multiple="multiple" style="width: 70%;" name="pvalley_user_role_based_shipping_rule_matrix[<?php echo $rule_count; ?>][countries][]">
						<?php
							echo $this->country_options;
						?>
					</select>
				</td>
				<td>
					<select class="wc-enhanced-select xa_est_shipping_class" style="width: 70%;" name="pvalley_user_role_based_shipping_rule_matrix[<?php echo $rule_count; ?>][actionOnShippingMethod]">
						<?php
							foreach( $this->actionOnShippingMethodsDropdownOptions as $option ) {
								echo "<option value='$option->value'>".$option->name."</option>";
							}
						?>
					</select>
				</td>
				<td>
					<input type="text" style="width:100%" name="pvalley_user_role_based_shipping_rule_matrix[<?php echo $rule_count; ?>][shipping_methods]" placeholder="flat_rate:1; free_shipping:2">
				</td>
			</tr>
		</table>
		<?php
		
	}
}

new Pvalley_Role_Based_Shipping_Rule_Matrix();