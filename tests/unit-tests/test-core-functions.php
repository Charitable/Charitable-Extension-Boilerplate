<?php

class Test_Charitable_EDD_Core_Functions extends WP_UnitTestCase {

	function setUp() {
		parent::setUp();	
	}
	
	function test_charitable_edd() {
		$this->assertInstanceOf( 'Charitable_EDD', charitable_edd() );
	}
}