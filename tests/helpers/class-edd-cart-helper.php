<?php
/**
 * Class Charitable_EDD_Cart_Helper
 *
 * Helper class to create and delete a campaign EDD benefactors easily.
 */
class Charitable_EDD_Cart_Helper extends WP_UnitTestCase {

	/**
	 * Create a fake cart.
	 *
	 * This doesn't actually create a payment or log. 
	 *
	 * @param 	array 		$downloads
	 * @param 	array 		$quantity 			Optional array of quantities to add. 
	 * @return 	array 		$downloads
	 * @access  public
	 * @static
	 * @since 	1.0.0
	 */
	public static function create_cart( $downloads, $quantities = array() ) {
		$cart = array();

		foreach ( $downloads as $key => $download ) {

			if ( is_array( $download ) ) {
				list( $download, $price_id ) = $download;
			}

			$ret = array(
				'id'			=> $download->ID, 				
				'category_ids'	=> wp_get_object_terms( $download->ID, 'download_category', array( 'fields' => 'ids' ) )
			);

			/* Set quantity */
			if ( ! empty( $quantities ) ) {

				$quantity = isset( $quantities[ $key ] ) ? $quantities[ $key ] : end( $quantities );

			}
			else {

				$quantity = 1;

			}

			$ret[ 'quantity' ] = $quantity;

			/* Set price & price ID (if applicable) */
			$prices = get_post_meta( $download->ID, 'edd_variable_prices', true );
			
			if ( false == $prices ) {

				$ret[ 'item_price' ] = get_post_meta( $download->ID, 'edd_price', true );

			}
			else {

				$price_id = isset( $price_id ) ? $price_id : 1;
				$ret[ 'item_price' ] = $prices[$price_id]['amount'];
				$ret[ 'options' ][ 'price_id' ] = $price_id;
			}				

			$cart[] = $ret; 
			
			unset( $price_id );
		}

		return $cart;
	}
}