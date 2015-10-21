<?php
/**
 * Class Charitable_Campaign_Helper
 *
 * Helper class to create and delete a campaign easily.
 */
class Charitable_Campaign_Helper extends WP_UnitTestCase {

	/**
	 * Delete a campaign 
	 *
	 * @return 	void
	 * @access  public
	 * @static
	 * @since 	1.0.0
	 */
	public static function delete_campaign( $campaign_id ) {
		wp_delete_post( $campaign_id, true );
	}

	/**
	 * Create a campaign. 
	 *
	 * @param 	array 		$args 				Optional arguments.
	 * @return 	int 		$campaign_id
	 * @access  public
	 * @static
	 * @since 	1.0.0	 
	 */
	public static function create_campaign( $args = array() ) {
		$defaults = array(
			'post_title'					=> 'Test Campaign', 
			'post_name'						=> 'test-campaign', 
			'post_type'						=> 'campaign', 
			'post_status'					=> 'publish', 
			'_campaign_goal' 				=> 0, 
			'_campaign_end_date'			=> 0, 
			'_campaign_suggested_donations'	=> ''
		);

		$args = array_merge( $defaults, $args );

		$campaign_id = wp_insert_post( array(
			'post_title'    => $args['post_title'],
			'post_name'     => $args['post_name'], 
			'post_type'     => $args['post_type'], 
			'post_status'   => $args['post_status']
		) );

		$meta_keys = array(
			'_campaign_goal', 
			'_campaign_end_date',
			'_campaign_suggested_donations'
		);

		foreach ( $meta_keys as $key ) {
			update_post_meta( $campaign_id, $key, $args[$key] );
		}

		return $campaign_id;
	}

	/**
	 * Create a campaign with a goal. 
	 *
	 * @param 	string 		$goal
	 * @param 	array 		$args 				Optional arguments.
	 * @return 	int 		$campaign_id
	 * @access  public
	 * @static
	 * @since 	1.0.0
	 */
	public static function create_campaign_with_goal( $amount, $args = array()  ) {
		$args['_campaign_goal'] = $amount;
		return self::create_campaign( $args );
	}

	/**
	 * Create a campaign with an end date. 
	 *
	 * @param 	string 		$end_date
	 * @param 	array 		$args 				Optional arguments.
	 * @return 	int 		$campaign_id
	 * @access  public
	 * @static
	 * @since 	1.0.0
	 */
	public static function create_campaign_with_end_date( $end_date, $args = array() ) {
		$args['_campaign_end_date'] = $amount;
		return self::create_campaign( $args );
	}
}