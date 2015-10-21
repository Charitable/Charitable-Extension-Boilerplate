<?php

class Test_Charitable_EDD_Benefactors_DB extends WP_UnitTestCase {
	
	/** @var Charitable_Benefactors_DB */
	private $parent_db;

	/** @var Charitable_EDD_Benefactors_DB */
	private $child_db;

	/** @var int */
	private $campaign_1_id;
	private $campaign_2_id;
	private $campaign_3_id;

	/** @var Object */
	private $download_1;
	private $download_2;
	private $download_3;
	private $download_4;

	/** @var int */
	private $campaign_benefactor_1_id;
	private $campaign_benefactor_2_id;
	private $campaign_benefactor_3_id;
	private $campaign_benefactor_4_id;
	private $campaign_benefactor_5_id;

	/** @var array */
	private $cart_1;
	private $cart_2;
	private $cart_3;

	public function setUp() {
		
		parent::setUp();

		$this->parent_db = new Charitable_Benefactors_DB();
		$this->child_db = new Charitable_EDD_Benefactors_DB();

		$this->campaign_1_id = Charitable_Campaign_Helper::create_campaign();
		$this->campaign_2_id = Charitable_Campaign_Helper::create_campaign();
		$this->campaign_3_id = Charitable_Campaign_Helper::create_campaign();

		$this->download_1 = EDD_Helper_Download::create_simple_download();
		$this->download_2 = EDD_Helper_Download::create_simple_download();
		$this->download_3 = EDD_Helper_Download::create_simple_download();
		$this->download_4 = EDD_Helper_Download::create_simple_download();

		$this->campaign_benefactor_1_id = Charitable_Campaign_EDD_Benefactor_Helper::create_benefactor( $this->campaign_1_id, array(			
			'benefactor'						=> array( 
				'edd_download_id' 				=> $this->download_1->ID
			),
			'contribution_amount'				=> 10, 
			'contribution_amount_is_percentage'	=> 1			
		) );

		$this->campaign_benefactor_2_id = Charitable_Campaign_EDD_Benefactor_Helper::create_benefactor( $this->campaign_2_id, array(			
			'benefactor'						=> array( 
				'edd_download_id' 				=> $this->download_2->ID
			),
			'contribution_amount' 				=> 10, 
			'contribution_amount_is_percentage'	=> 1, 			
			'is_active' 						=> 0
		) );

		$this->campaign_benefactor_3_id = Charitable_Campaign_EDD_Benefactor_Helper::create_benefactor( $this->campaign_1_id, array(			
			'benefactor'						=> array( 
				'edd_download_id' 				=> $this->download_3->ID
			),
			'contribution_amount' 				=> 2, 
			'contribution_amount_is_percentage'	=> 0 			
		) );

		$this->campaign_benefactor_4_id = Charitable_Campaign_EDD_Benefactor_Helper::create_benefactor( $this->campaign_3_id, array(			
			'benefactor'						=> array(
				'edd_is_global_contribution' 	=> 1
			),
			'contribution_amount' 				=> 2, 
			'contribution_amount_is_percentage' => 1			
		) );

		$this->cart_1 = Charitable_EDD_Cart_Helper::create_cart( 
			array( $this->download_1 ) 
		);

		$this->cart_2 = Charitable_EDD_Cart_Helper::create_cart( 
			array( $this->download_4 ) 
		);

		$this->cart_3 = Charitable_EDD_Cart_Helper::create_cart(
			array( $this->download_1, $this->download_2, $this->download_3, $this->download_4 ), 
			array( 2, 1, 1, 2 )
		);
	}

	public function tearDown() {

		parent::tearDown();

		Charitable_Campaign_Helper::delete_campaign( $this->campaign_1_id );
		Charitable_Campaign_Helper::delete_campaign( $this->campaign_2_id );
		Charitable_Campaign_Helper::delete_campaign( $this->campaign_3_id );
		
		EDD_Helper_Download::delete_download( $this->download_1->ID );
		EDD_Helper_Download::delete_download( $this->download_2->ID );
		EDD_Helper_Download::delete_download( $this->download_3->ID );

		Charitable_Campaign_EDD_Benefactor_Helper::delete_benefactor( $this->campaign_benefactor_1_id );
		Charitable_Campaign_EDD_Benefactor_Helper::delete_benefactor( $this->campaign_benefactor_2_id );
		Charitable_Campaign_EDD_Benefactor_Helper::delete_benefactor( $this->campaign_benefactor_3_id );
		Charitable_Campaign_EDD_Benefactor_Helper::delete_benefactor( $this->campaign_benefactor_4_id );
		Charitable_Campaign_EDD_Benefactor_Helper::delete_benefactor( $this->campaign_benefactor_5_id );	
	}

	public function test_columns() {
		$columns = $this->child_db->get_columns();

		$this->assertArrayHasKey( 'campaign_benefactor_id', $columns );		
		$this->assertArrayHasKey( 'edd_download_id', $columns );
		$this->assertArrayHasKey( 'edd_download_category_id', $columns );
		$this->assertArrayHasKey( 'edd_is_global_contribution', $columns );		
	}

	public function test_get_column_defaults() {
		$columns = $this->child_db->get_column_defaults();

		$this->assertEquals( 0, $columns['edd_is_global_contribution'] );		
	}

	/**
	 * Benefactor relationships are inserted by the parent db class, 
	 * and the EDD DB class then adds the child records.
	 */
	public function test_parent_insert() {
		$parent_record = $this->parent_db->get( $this->campaign_benefactor_1_id );

		$this->assertEquals( $this->campaign_1_id, $parent_record->campaign_id );	
		$this->assertEquals( 10, $parent_record->contribution_amount );
		$this->assertEquals( 1, $parent_record->contribution_amount_is_percentage );
		
		$child_record = $this->child_db->get( $this->campaign_benefactor_1_id );

		$this->assertEquals( $this->download_1->ID, $child_record->edd_download_id );
		$this->assertEquals( 0, $child_record->edd_download_category_id );
		$this->assertEquals( 0, $child_record->edd_is_global_contribution );
	}

	public function test_get_benefactors_for_downloads() {
		$tests = array(
			'cart_1' => array(
				'expected' 	=> array( 
					$this->campaign_benefactor_1_id, 
					$this->campaign_benefactor_4_id 
				), 
				'actual'	=> $this->child_db->get_benefactors_for_downloads( $this->cart_1 )
			), 
			'cart_2' => array(
				'expected' 	=> array(
					$this->campaign_benefactor_4_id
				), 
				'actual'	=> $this->child_db->get_benefactors_for_downloads( $this->cart_2 )
			), 
			'cart_3' => array(
				'expected' 	=> array( 
					$this->campaign_benefactor_1_id, 
					$this->campaign_benefactor_2_id, 
					$this->campaign_benefactor_3_id, 
					$this->campaign_benefactor_4_id 
				), 
				'actual'	=> $this->child_db->get_benefactors_for_downloads( $this->cart_3 )
			)
		);

		foreach ( $tests as $cart => $test ) {
			$this->assertCount( count( $test['expected'] ), $test['actual'], sprintf( 'Testing count on %s', $cart ) );

			foreach ( $test['expected'] as $i => $campaign_benefactor_id ) {
				$this->assertArrayHasKey( $campaign_benefactor_id, $test['actual'], sprintf( 'Test %d on %s', $i, $cart ) );
			}
		}
	}
}