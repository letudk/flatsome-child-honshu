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
    // unset($fields['billing']['billing_country']);
    unset($fields['billing']['billing_state']);
    unset($fields['billing']['billing_city']);
    unset($fields['billing']['billing_email']);

     // Shipping fields
     unset( $fields['shipping']['shipping_company'] );
     unset( $fields['shipping']['shipping_phone'] );
     unset( $fields['shipping']['shipping_state'] );
     unset( $fields['shipping']['shipping_first_name'] );
     unset( $fields['shipping']['shipping_last_name'] );
     unset( $fields['shipping']['shipping_address_1'] );
     unset( $fields['shipping']['shipping_address_2'] );
     unset( $fields['shipping']['shipping_city'] );
     unset( $fields['shipping']['shipping_postcode'] );

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
    // $nz_size_cko['billing']['billing_country']['class'] =  array('form-row','form-row-last'); 
	$nz_size_cko['billing']['billing_address_1']['class']=  array('form-row-wide'); 

	return $nz_size_cko;
}
add_filter('woocommerce_checkout_fields','nz_style_checkout',9999);

/* Thanh Toán - Sắp xếp vị trí priority-data */
function woocommerce_default_address_fields_reorder($fields) { 	
    $fields['address_1']['priority'] = 135; 	 
    return $fields; 
    }
add_filter('woocommerce_default_address_fields','woocommerce_default_address_fields_reorder'); 

/*
 * Tùy chỉnh hiển thị thông tin chuyển khoản trong woocommerce
 * Author: levantoan.com
 */
add_filter('woocommerce_bacs_accounts', '__return_false');
add_action( 'woocommerce_email_before_order_table', 'devvn_email_instructions', 10, 3 );
function devvn_email_instructions( $order, $sent_to_admin, $plain_text = false ) {
    if ( ! $sent_to_admin && 'bacs' === $order->get_payment_method() && $order->has_status( 'on-hold' ) ) {
        devvn_bank_details( $order->get_id() );
    }
}
add_action( 'woocommerce_thankyou_bacs', 'devvn_thankyou_page' );
function devvn_thankyou_page($order_id){
    devvn_bank_details($order_id);
}
function devvn_bank_details( $order_id = '' ) {
    $bacs_accounts = get_option('woocommerce_bacs_accounts');
    if ( ! empty( $bacs_accounts ) ) {
        ob_start();
        echo '<table style=" border: 1px solid #ddd; border-collapse: collapse; width: 100%; ">';
        ?>
        <tr>
            <td colspan="2" style="border: 1px solid #eaeaea;padding: 6px 10px;"><strong>Thông tin chuyển khoản</strong></td>
        </tr>
        <?php
        foreach ( $bacs_accounts as $bacs_account ) {
            $bacs_account = (object) $bacs_account;
            $account_name = $bacs_account->account_name;
            $bank_name = $bacs_account->bank_name;
            $stk = $bacs_account->account_number;
            $icon = $bacs_account->iban;
            ?>
            <tr>
                <td style="width: 200px;border: 1px solid #eaeaea;padding: 6px 10px;"><?php if($icon):?><img src="<?php echo $icon;?>" alt=""/><?php endif;?></td>
                <td style="border: 1px solid #eaeaea;padding: 6px 10px;">
                    <strong>STK:</strong> <?php echo $stk;?><br>
                    <strong>Chủ tài khoản:</strong> <?php echo $account_name;?><br>
                    <strong>Chi Nhánh:</strong> <?php echo $bank_name;?><br>
                    <strong>Nội dung chuyển khoản:</strong> DH<?php echo $order_id;?>
                </td>
            </tr>
            <?php
        }
        echo '</table>';
        echo ob_get_clean();;
    }
}

