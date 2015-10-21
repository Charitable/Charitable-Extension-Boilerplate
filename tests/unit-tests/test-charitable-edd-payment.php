<?php

class Test_Charitable_EDD_Payment extends WP_UnitTestCase {
    
    public function setUp() {
        parent::setUp();

        $this->purchase_helper  = charitable_edd()->get_object( 'Charitable_EDD_Payment' );

        $this->campaign_1_id    = Charitable_Campaign_Helper::create_campaign();
        $this->campaign_2_id    = Charitable_Campaign_Helper::create_campaign();
        $this->download_1       = EDD_Helper_Download::create_simple_download(); // $2
        $this->download_2       = EDD_Helper_Download::create_simple_download(); // $2

        $this->campaign_benefactor_1_id = Charitable_Campaign_EDD_Benefactor_Helper::create_benefactor( $this->campaign_1_id, array(            
            'benefactor'                        => array( 
                'edd_download_id'               => $this->download_1->ID
            ),
            'contribution_amount'               => 10, 
            'contribution_amount_is_percentage' => 1, 
            'contribution_amount_is_per_item'   => 0            
        ) );

        $this->campaign_benefactor_2_id = Charitable_Campaign_EDD_Benefactor_Helper::create_benefactor( $this->campaign_2_id, array(            
            'benefactor'                        => array( 
                'edd_is_global_contribution'    => 1
            ),
            'contribution_amount'               => 1, 
            'contribution_amount_is_percentage' => 0, 
            'contribution_amount_is_per_item'   => 1            
        ) );

        /* Should create 1 donation with 2 campaign donations (benefactor 1 + benefactor 2) */
        $this->payment_1_id     = EDD_Helper_Payment::create_simple_payment( array( $this->download_1 ) ); // 2 + 1 = 3

        /* Should create 1 donation with 1 campaign donation (benefactor 2) */
        $this->payment_2_id     = EDD_Helper_Payment::create_simple_payment( array( $this->download_2 ) ); // 1

        /* Should create 1 donation with 3 campaign donations (benefactor 1 + benefactor 2 * 2) */
        $this->payment_3_id     = EDD_Helper_Payment::create_simple_payment( array( $this->download_1, $this->download_2 ) ); // 2 + 1 + 1 = 4
    }

    public function tearDown() {
        parent::tearDown();

        Charitable_Campaign_Helper::delete_campaign( $this->campaign_1_id );
        Charitable_Campaign_Helper::delete_campaign( $this->campaign_2_id );

        EDD_Helper_Download::delete_download( $this->download_1->ID );
        EDD_Helper_Download::delete_download( $this->download_2->ID );

        Charitable_Campaign_EDD_Benefactor_Helper::delete_benefactor( $this->campaign_benefactor_1_id );
        Charitable_Campaign_EDD_Benefactor_Helper::delete_benefactor( $this->campaign_benefactor_2_id );

        EDD_Helper_Payment::delete_payment( $this->payment_1_id );
        EDD_Helper_Payment::delete_payment( $this->payment_2_id );
        EDD_Helper_Payment::delete_payment( $this->payment_3_id );
    }

    public function test_should_add_donation() {
        $this->assertTrue( $this->purchase_helper->should_add_donation( 'publish', 'pending' ) );
        $this->assertTrue( $this->purchase_helper->should_add_donation( 'preapproval', 'pending' ) );
        $this->assertFalse( $this->purchase_helper->should_add_donation( 'preapproval', 'publish' ) );
        $this->assertFalse( $this->purchase_helper->should_add_donation( 'publish', 'preapproval' ) );
        $this->assertFalse( $this->purchase_helper->should_add_donation( 'refunded', 'pending' ) );
        $this->assertFalse( $this->purchase_helper->should_add_donation( 'revoked', 'pending' ) );
        $this->assertFalse( $this->purchase_helper->should_add_donation( 'failed', 'pending' ) );
        $this->assertFalse( $this->purchase_helper->should_add_donation( 'abandoned', 'pending' ) );
        $this->assertFalse( $this->purchase_helper->should_add_donation( 'cancelled', 'pending' ) );
        $this->assertFalse( $this->purchase_helper->should_add_donation( 'pending', 'publish' ) );
        $this->assertFalse( $this->purchase_helper->should_add_donation( 'pending', 'preapproval' ) );
    }

    public function test_should_remove_donation() {
        $this->assertTrue( $this->purchase_helper->should_remove_donation( 'pending', 'publish' ) );
        $this->assertTrue( $this->purchase_helper->should_remove_donation( 'pending', 'preapproval' ) );
        $this->assertFalse( $this->purchase_helper->should_remove_donation( 'preapproval', 'publish' ) );
        $this->assertFalse( $this->purchase_helper->should_remove_donation( 'publish', 'preapproval' ) );
        $this->assertFalse( $this->purchase_helper->should_remove_donation( 'refunded', 'pending' ) );
        $this->assertFalse( $this->purchase_helper->should_remove_donation( 'revoked', 'pending' ) );
        $this->assertFalse( $this->purchase_helper->should_remove_donation( 'failed', 'pending' ) );
        $this->assertFalse( $this->purchase_helper->should_remove_donation( 'abandoned', 'pending' ) );
        $this->assertFalse( $this->purchase_helper->should_remove_donation( 'cancelled', 'pending' ) );
        $this->assertFalse( $this->purchase_helper->should_remove_donation( 'publish', 'pending' ) );
        $this->assertFalse( $this->purchase_helper->should_remove_donation( 'preapproval', 'pending' ) );
    }

    public function test_add_donation_for_payment() {
        $this->assertGreaterThan( 0, $this->purchase_helper->add_donation_for_payment( $this->payment_1_id, 'publish' ) );
        $this->assertGreaterThan( 0, $this->purchase_helper->add_donation_for_payment( $this->payment_2_id, 'publish' ) );
        $this->assertGreaterThan( 0, $this->purchase_helper->add_donation_for_payment( $this->payment_3_id, 'publish' ) );
    }

    /** 
     * @depends test_add_donation_for_payment
     */
    public function test_get_donation_for_payment_1() {
        $donation_id = $this->purchase_helper->add_donation_for_payment( $this->payment_1_id, 'publish' );
    }

    /** 
     * @depends test_add_donation_for_payment
     */
    public function test_get_campaign_donations_for_payment_1() {
        $this->purchase_helper->add_donation_for_payment( $this->payment_1_id, 'publish' );     
        $this->assertCount( 2, $this->purchase_helper->get_campaign_donations_through_payment( $this->payment_1_id ) );
    }

    /** 
     * @depends test_get_campaign_donations_for_payment_1
     */
    public function test_get_campaign_donations_for_payment_2() {
        $this->purchase_helper->add_donation_for_payment( $this->payment_2_id, 'publish' );     
        $this->assertCount( 1, $this->purchase_helper->get_campaign_donations_through_payment( $this->payment_2_id ) );
    }   

    /** 
     * @depends test_get_campaign_donations_for_payment_1
     */
    public function test_get_campaign_donations_for_payment_3() {
        $this->purchase_helper->add_donation_for_payment( $this->payment_3_id, 'publish' );     
        $this->assertCount( 3, $this->purchase_helper->get_campaign_donations_through_payment( $this->payment_3_id ) );
    }

    /** 
     * @depends test_get_campaign_donations_for_payment_1
     */
    public function test_get_campaign_donation_total_for_payment_1() {
        $this->purchase_helper->add_donation_for_payment( $this->payment_1_id, 'publish' );     
        $this->assertEquals( 3, $this->purchase_helper->get_campaign_donation_total_through_payment( $this->payment_1_id ) );
    }

    /** 
     * @depends test_get_campaign_donation_total_for_payment_1
     * @depends test_get_campaign_donations_for_payment_2
     */
    public function test_get_campaign_donation_total_for_payment_2() {
        $this->purchase_helper->add_donation_for_payment( $this->payment_2_id, 'publish' );     
        $this->assertEquals( 1, $this->purchase_helper->get_campaign_donation_total_through_payment( $this->payment_2_id ) );
    }

    /** 
     * @depends test_get_campaign_donation_total_for_payment_1
     * @depends test_get_campaign_donations_for_payment_3
     */
    public function test_get_campaign_donation_total_for_payment_3() {
        $this->purchase_helper->add_donation_for_payment( $this->payment_3_id, 'publish' );     
        $this->assertEquals( 4, $this->purchase_helper->get_campaign_donation_total_through_payment( $this->payment_3_id ) );
    }

    /** 
     * @depends test_add_donation_for_payment
     */
    public function test_remove_donation_for_payment() {
        $donation_id = $this->purchase_helper->add_donation_for_payment( $this->payment_1_id, 'publish' );
        $this->assertEquals( $donation_id, $this->purchase_helper->remove_donation_for_payment( $this->payment_1_id, 'pending' ) );     
    }

    /**
     * @depends test_should_add_donation
     * @depends test_should_remove_donation
     * @depends test_add_donation_for_payment
     * @depends test_remove_donation_for_payment
     */
    public function test_process_payment() {
        /* Upgrading from pending to publish should create 2 donations */
        $this->assertGreaterThan( 0, $this->purchase_helper->process_payment( $this->payment_1_id, 'publish', 'pending' ) );

        /* Downgrading from publish to pending should remove 2 donations */
        $this->assertGreaterThan( 0, $this->purchase_helper->process_payment( $this->payment_1_id, 'pending', 'publish' ) );

        /* Changing from pending to any status other than publish and preapproval should not do anything and return 0 */
        $this->assertEquals( 0, $this->purchase_helper->process_payment( $this->payment_1_id, 'refunded', 'pending' ) );
        $this->assertEquals( 0, $this->purchase_helper->process_payment( $this->payment_1_id, 'revoked', 'pending' ) );
        $this->assertEquals( 0, $this->purchase_helper->process_payment( $this->payment_1_id, 'failed', 'pending' ) );
        $this->assertEquals( 0, $this->purchase_helper->process_payment( $this->payment_1_id, 'abandoned', 'pending' ) );
        $this->assertEquals( 0, $this->purchase_helper->process_payment( $this->payment_1_id, 'cancelled', 'pending' ) );

        /* Changing from pending to preapproval should create 1 donation */
        $this->assertGreaterThan( 0, $this->purchase_helper->process_payment( $this->payment_1_id, 'preapproval', 'pending' ) );
    }

    public function test_get_approved_statuses() {
        $this->assertContains( 'publish', $this->purchase_helper->get_approved_statuses() );
        $this->assertContains( 'preapproval', $this->purchase_helper->get_approved_statuses() );
    }

    public function test_get_charitable_donation_status() {        
        $this->assertEquals( 'charitable-completed',    $this->purchase_helper->get_charitable_donation_status( 'publish' ) );
        $this->assertEquals( 'charitable-pending',      $this->purchase_helper->get_charitable_donation_status( 'pending' ) );
        $this->assertEquals( 'charitable-refunded',     $this->purchase_helper->get_charitable_donation_status( 'refunded' ) );
        $this->assertEquals( 'charitable-cancelled',    $this->purchase_helper->get_charitable_donation_status( 'revoked' ) );
        $this->assertEquals( 'charitable-cancelled',    $this->purchase_helper->get_charitable_donation_status( 'abandoned' ) );
        $this->assertEquals( 'charitable-cancelled',    $this->purchase_helper->get_charitable_donation_status( 'cancelled' ) );
        $this->assertEquals( 'charitable-failed',       $this->purchase_helper->get_charitable_donation_status( 'failed' ) );
        $this->assertEquals( 'charitable-preapproved',  $this->purchase_helper->get_charitable_donation_status( 'preapproval' ) );
    }
}