<?php

class Test_Charitable_EDD_Cart extends WP_UnitTestCase {

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

		$this->campaign_1_id = Charitable_Campaign_Helper::create_campaign();
		$this->campaign_2_id = Charitable_Campaign_Helper::create_campaign();
		$this->campaign_3_id = Charitable_Campaign_Helper::create_campaign();

		/* Each download costs $20 */
		$this->download_1 = EDD_Helper_Download::create_simple_download();
		$this->download_2 = EDD_Helper_Download::create_simple_download();
		$this->download_3 = EDD_Helper_Download::create_simple_download();
		$this->download_4 = EDD_Helper_Download::create_simple_download();
		$this->download_5 = EDD_Helper_Download::create_variable_download();

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
			'date_deactivated' 					=> '2013-10-10 12:00:00'
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

		/* (2 + 0.4) + (2) + (0.8 + 0.4 + 0.4 + 0.8) */
		$this->cart_3 = Charitable_EDD_Cart_Helper::create_cart(
			array( $this->download_1, $this->download_2, $this->download_3, $this->download_4 ), 
			array( 2, 1, 1, 2 )
		);

		/* Buying two of the first variation ($20 each), one of the second ($100). */
		$this->cart_4 = Charitable_EDD_Cart_Helper::create_cart(
			array( array( $this->download_5, 0 ), array( $this->download_5, 1 ) ), 
			array( 2, 1 )
		);

		$this->edd_cart_1 = new Charitable_EDD_Cart( $this->cart_1 );
		$this->edd_cart_2 = new Charitable_EDD_Cart( $this->cart_2 );
		$this->edd_cart_3 = new Charitable_EDD_Cart( $this->cart_3 );
		$this->edd_cart_4 = new Charitable_EDD_Cart( $this->cart_4 );
	}

	public function tearDown() {

		parent::tearDown();

		Charitable_Campaign_Helper::delete_campaign( $this->campaign_1_id );
		Charitable_Campaign_Helper::delete_campaign( $this->campaign_2_id );
		Charitable_Campaign_Helper::delete_campaign( $this->campaign_3_id );
		
		EDD_Helper_Download::delete_download( $this->download_1->ID );
		EDD_Helper_Download::delete_download( $this->download_2->ID );
		EDD_Helper_Download::delete_download( $this->download_3->ID );
		EDD_Helper_Download::delete_download( $this->download_4->ID );
		EDD_Helper_Download::delete_download( $this->download_5->ID );

		Charitable_Campaign_EDD_Benefactor_Helper::delete_benefactor( $this->campaign_benefactor_1_id );
		Charitable_Campaign_EDD_Benefactor_Helper::delete_benefactor( $this->campaign_benefactor_2_id );
		Charitable_Campaign_EDD_Benefactor_Helper::delete_benefactor( $this->campaign_benefactor_3_id );
		Charitable_Campaign_EDD_Benefactor_Helper::delete_benefactor( $this->campaign_benefactor_4_id );
		Charitable_Campaign_EDD_Benefactor_Helper::delete_benefactor( $this->campaign_benefactor_5_id );	
	}

	public function test_get_downloads() {
		$this->assertCount( 1, $this->edd_cart_1->get_downloads() );
		$this->assertCount( 1, $this->edd_cart_2->get_downloads() );
		$this->assertCount( 4, $this->edd_cart_3->get_downloads() );
		$this->assertCount( 2, $this->edd_cart_4->get_downloads() );		
	}

	public function test_get_download_ids() {
		$this->assertCount( 1, $this->edd_cart_1->get_download_ids() );
		$this->assertCount( 1, $this->edd_cart_2->get_download_ids() );
		$this->assertCount( 4, $this->edd_cart_3->get_download_ids() );
		$this->assertCount( 1, $this->edd_cart_4->get_download_ids() );
	}

	public function test_get_benefactors() {
		$this->assertCount( 2, $this->edd_cart_1->get_benefactors() );
		$this->assertCount( 1, $this->edd_cart_2->get_benefactors() );
		$this->assertCount( 3, $this->edd_cart_3->get_benefactors() );
		$this->assertCount( 1, $this->edd_cart_4->get_benefactors() );
	}

	public function test_has_benefactors() {
		$this->assertTrue( $this->edd_cart_1->has_benefactors() );
		$this->assertTrue( $this->edd_cart_2->has_benefactors() );
		$this->assertTrue( $this->edd_cart_3->has_benefactors() );
		$this->assertTrue( $this->edd_cart_4->has_benefactors() );
	}

	public function test_get_line_item_percent_contribution() {
		$this->assertEquals( 5, $this->edd_cart_1->get_line_item_percent_contribution( 50, 2, 5 ) );
		$this->assertEquals( 0.7, $this->edd_cart_1->get_line_item_percent_contribution( 35, 1, 2 ) );
	}

	public function test_get_line_item_fixed_contribution() {
		$this->assertEquals( 4, $this->edd_cart_1->get_line_item_fixed_contribution( 2, 2 ) );
		$this->assertEquals( 1.80, $this->edd_cart_1->get_line_item_fixed_contribution( 3, 0.6 ) );
	}

	public function test_get_benefactor_benefits() {		
		$this->assertCount( 2, $this->edd_cart_1->get_benefactor_benefits() ); 
		$this->assertCount( 1, $this->edd_cart_2->get_benefactor_benefits() ); 
		$this->assertCount( 3, $this->edd_cart_3->get_benefactor_benefits() ); 
		$this->assertCount( 1, $this->edd_cart_4->get_benefactor_benefits() ); 
	}

	public function test_get_total_benefit_amount() {
		/* CART 1: 2 + 0.4 = 2.4 */
		$this->assertEquals( 2.4, $this->edd_cart_1->get_total_benefit_amount() );

		/* CART 2: 0.4 */
		$this->assertEquals( 0.4, $this->edd_cart_2->get_total_benefit_amount() );

		/* CART 3: 4 + 2 + 2.4 = 8.4 */
		$this->assertEquals( 8.4, $this->edd_cart_3->get_total_benefit_amount() );

		/* CART 4: 0.4 + 0.4 + 2 = 2.8 */
		$this->assertEquals( 2.8, $this->edd_cart_4->get_total_benefit_amount() );
	}

	public function test_get_benefits_by_campaign() {
		$cart_1_campaigns = $this->edd_cart_1->get_benefits_by_campaign();
		$this->assertCount( 2, $cart_1_campaigns );
		$this->assertArrayHasKey( $this->campaign_1_id, $cart_1_campaigns );
		$this->assertArrayHasKey( $this->campaign_3_id, $cart_1_campaigns );

		$cart_2_campaigns = $this->edd_cart_2->get_benefits_by_campaign();
		$this->assertCount( 1, $cart_2_campaigns );
		$this->assertArrayHasKey( $this->campaign_3_id, $cart_1_campaigns );

		$cart_3_campaigns = $this->edd_cart_3->get_benefits_by_campaign();
		$this->assertCount( 2, $cart_3_campaigns );
		$this->assertArrayHasKey( $this->campaign_1_id, $cart_1_campaigns );
		$this->assertArrayHasKey( $this->campaign_3_id, $cart_1_campaigns );

		$cart_4_campaigns = $this->edd_cart_4->get_benefits_by_campaign();
		$this->assertCount( 1, $cart_4_campaigns );
		$this->assertArrayHasKey( $this->campaign_3_id, $cart_4_campaigns );
	}

	public function test_get_total_campaign_benefit_amount() {
		$this->assertEquals( 2, $this->edd_cart_1->get_total_campaign_benefit_amount( $this->campaign_1_id ), sprintf( 'Asserting that Campaign %d gets $%s benefit', $this->campaign_1_id, 2 ) );
		$this->assertEquals( 0, $this->edd_cart_1->get_total_campaign_benefit_amount( $this->campaign_2_id ) );
		$this->assertEquals( 0.4, $this->edd_cart_1->get_total_campaign_benefit_amount( $this->campaign_3_id ) );

		$this->assertEquals( 0, $this->edd_cart_2->get_total_campaign_benefit_amount( $this->campaign_1_id ), sprintf( 'Asserting that Campaign %d gets $%s benefit', $this->campaign_1_id, 0 ) );
		$this->assertEquals( 0, $this->edd_cart_2->get_total_campaign_benefit_amount( $this->campaign_2_id ) );
		$this->assertEquals( 0.4, $this->edd_cart_2->get_total_campaign_benefit_amount( $this->campaign_3_id ) );

		$this->assertEquals( 6, $this->edd_cart_3->get_total_campaign_benefit_amount( $this->campaign_1_id ), sprintf( 'Asserting that Campaign %d gets $%s benefit', $this->campaign_1_id, 2.8 ) );
		$this->assertEquals( 0, $this->edd_cart_3->get_total_campaign_benefit_amount( $this->campaign_2_id ) );
		$this->assertEquals( 2.4, $this->edd_cart_3->get_total_campaign_benefit_amount( $this->campaign_3_id ) );

		$this->assertEquals( 0, $this->edd_cart_4->get_total_campaign_benefit_amount( $this->campaign_1_id ), sprintf( 'Asserting that Campaign %d gets $%s benefit', $this->campaign_1_id, 2.8 ) );
		$this->assertEquals( 0, $this->edd_cart_4->get_total_campaign_benefit_amount( $this->campaign_2_id ) );
		$this->assertEquals( 2.8, $this->edd_cart_4->get_total_campaign_benefit_amount( $this->campaign_3_id ) );
	}	
}