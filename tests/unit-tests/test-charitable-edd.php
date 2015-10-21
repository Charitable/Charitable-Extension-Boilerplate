<?php

class Test_Charitable_EDD extends WP_UnitTestCase {

	function setUp() {
		parent::setUp();
		$this->charitable_edd 	= charitable_edd();
		$this->directory_path 	= $this->charitable_edd->get_path( 'directory' );
		$this->directory_url 	= $this->charitable_edd->get_path( 'directory', false );
	}

	function test_static_instance() {
		$this->assertClassHasStaticAttribute( 'instance', get_class( $this->charitable_edd ) );
	}

	function test_load_dependencies() {
		$this->assertFileExists( $this->charitable_edd->get_path( 'includes' ) . 'charitable-edd-core-functions.php' );	
	}

	function test_attach_hooks_and_filters() {		
	}

	function test_is_start() {
		$this->assertFalse( $this->charitable_edd->is_start() );
	}

	function test_started() {
		$this->assertTrue( $this->charitable_edd->started() );
	}

	function test_get_path() {
		$this->assertEquals( $this->directory_path . 'charitable-edd.php', 	$this->charitable_edd->get_path() ); // __FILE__
		$this->assertEquals( $this->directory_path, 						$this->charitable_edd->get_path( 'directory' ) );
		$this->assertEquals( $this->directory_url, 							$this->charitable_edd->get_path( 'directory', false ) );
		$this->assertEquals( $this->directory_path . 'includes/', 			$this->charitable_edd->get_path( 'includes' ) );
		$this->assertEquals( $this->directory_path . 'includes/admin/', 	$this->charitable_edd->get_path( 'admin' ) );
		$this->assertEquals( $this->directory_path . 'public/', 			$this->charitable_edd->get_path( 'public' ) );		
	}

	function test_register_table() {
		$this->assertInstanceOf( 'Charitable_EDD_Benefactors_DB', charitable()->get_db_table( 'edd_benefactors' ) );
	}
}