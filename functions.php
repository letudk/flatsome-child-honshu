<?php
 
/*Proceed to Checkout*/
remove_action( 'woocommerce_proceed_to_checkout', 'woocommerce_button_proceed_to_checkout', 20 ); 
add_action('woocommerce_proceed_to_checkout', 'sm_woo_custom_checkout_button_text',20);
function sm_woo_custom_checkout_button_text() {
    $checkout_url = WC()->cart->get_checkout_url();
  ?>
       <a href="<?php echo $checkout_url; ?>" class="checkout-button button alt wc-forward"><?php  _e( 'XÁC NHẬN ĐẶT HÀNG', 'woocommerce' ); ?></a> 
  <?php
} 

add_filter( 'woocommerce_order_button_text', 'custom_order_text' );

function custom_order_text() {
return ( 'ĐẶT HÀNG' );
}

/* Thanh Toán - Xóa - Sửa Field */
function nz_edit_cko($fields){
	$fields['billing']['billing_first_name']['label'] = 'Họ tên';
	$fields['billing']['billing_first_name']['placeholder'] = 'Nhập họ tên quý khách';
	// $fields['billing']['billing_email']['label'] = 'Email';
	// $fields['billing']['billing_email']['placeholder'] = 'Địa chỉ Email (có thể để trống)';
	$fields['billing']['billing_phone']['label'] = 'Số điện thoại';
	$fields['billing']['billing_phone']['placeholder'] = 'Nhập số điện thoại';

	$fields['billing']['billing_address_1']['label'] = 'Địa chỉ nhận hàng';
	$fields['billing']['billing_address_1']['placeholder'] = 'Số nhà - Quận/Huyện - Thành phố...';

	$fields['order']['order_comments']['label'] = 'Ghi chú thêm về đơn hàng';
	$fields['order']['order_comments']['placeholder'] = 'Ví dụ thời gian giao hàng, địa chỉ giao hàng, gọi trước khi giao...';

    unset($fields['billing']['billing_last_name']);
    unset($fields['billing']['billing_company']);
    unset($fields['billing']['billing_address_2']);
    unset($fields['billing']['billing_postcode']);
    unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_email']);
    return $fields;
}
add_filter('woocommerce_checkout_fields','nz_edit_cko');

/* Thanh Toán - Chỉnh sửa kích thước Field
form-row-first – Nửa đầu
form-row-last – Nửa sau
form-row-wide – Full
Fix - p.form-row-wide.form-row-last {clear: none;}
*/
function nz_style_checkout($nz_size_cko){ 
	$nz_size_cko['billing']['billing_first_name']['class'][10] = 'form-row-first';
	$nz_size_cko['billing']['billing_phone']['class'][20] = 'form-row-last'; 
	$nz_size_cko['billing']['billing_address_1']['class'] =  array('form-row', 'form-row', 'form-row-wide', 'address-field', 'validate-required'); 

	return $nz_size_cko;
}
add_filter('woocommerce_checkout_fields','nz_style_checkout',9999);

/* Thanh Toán - Sắp xếp vị trí priority-data */
function woocommerce_default_address_fields_reorder($fields) { 	
    $fields['address_1']['priority'] = 135; 	 
    return $fields; 
    }
add_filter('woocommerce_default_address_fields','woocommerce_default_address_fields_reorder');