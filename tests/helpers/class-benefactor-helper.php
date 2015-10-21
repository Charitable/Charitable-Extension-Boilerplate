<?php
/**
 * Class Charitable_Campaign_EDD_Benefactor_Helper
 *
 * Helper class to create and delete a campaign EDD benefactors easily.
 */
class Charitable_Campaign_EDD_Benefactor_Helper extends WP_UnitTestCase {

	/**
	 * Delete a benefactor.  
	 *
	 * @param 	int 		$campaign_benefactor_id
	 * @return 	void
	 * @access  public
	 * @static
	 * @since 	1.0.0
	 */
	public static function delete_benefactor( $campaign_benefactor_id ) {
		$db = new Charitable_Benefactors_DB();
		$db->delete( $campaign_benefactor_id );
	}

	/**
	 * Create a campaign -> EDD benefactor. 
	 *
	 * @param 	int 		$campaign_id
	 * @param 	array 		$args
	 * @return 	int 		$campaign_benefactor_id
	 * @access  public
	 * @static
	 * @since 	1.0.0
	 */
	public static function create_benefactor( $campaign_id, $args ) {
		$defaults = array(
			'benefactor' 						=> array(
				'edd_download_id'				=> '', 
				'edd_is_global_contribution'	=> 0,
			),			
			'contribution_amount'				=> '', 
			'contribution_amount_is_percentage'	=> 1,  
			'date_created'						=> date( 'Y-m-d H:i:s' ), 
			'date_modified'						=> date( 'Y-m-d H:i:s' ),
			'is_active'							=> 1	
		);

		$args = array_merge( $defaults, $args );

		$args['campaign_id'] = $campaign_id;

		$db = new Charitable_Benefactors_DB();
		$campaign_benefactor_id = $db->insert( $args );

		return $campaign_benefactor_id;
	}

	/**
	 * Create a campaign -> download benefactor. 
	 *
	 * @param 	int 		$campaign_id
	 * @param 	int 		$download_id
	 * @param 	array 		$args 				Optional arguments.
	 * @return 	int 		$campaign_edd_benefactor_id
	 * @access  public
	 * @static
	 * @since 	1.0.0	 
	 */
	public function create_campaign_download_benefactor( $args ) {
		$campaign_id = Charitable_Campaign_Helper::create_campaign();
		$download = EDD_Helper_Download::create_simple_download();

		$args['campaign_id'] = $campaign_id;
		$args['benefactor']['edd_download_id'] = $download->ID;

		return self::create_benefactor( $args );
	}
}