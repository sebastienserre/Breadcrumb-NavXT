<?php
/**
 * This file contains tests for the mtekk_adminKit setting_absint class
 *
 * @group adminKit
 * @group bcn_core
 */
class adminKitSettingAbsintTest extends WP_UnitTestCase {
	public $settings = array();
	function setUp() {
		parent::setUp();
		$this->settings['normal_setting'] = new \mtekk\adminKit\setting\setting_absint(
				'normal_setting',
				42,
				'Normal Setting');
		$this->settings['empty_ok_setting'] = new \mtekk\adminKit\setting\setting_absint(
				'empty_ok_setting',
				56,
				'Empty Ok Setting',
				true);
		$this->settings['deprecated_setting'] = new \mtekk\adminKit\setting\setting_absint(
				'deprecated_setting',
				30,
				'Deprecated Setting',
				false,
				true);
	}
	public function tearDown() {
		parent::tearDown();
	}
	function test_is_deprecated() {
		$this->assertFalse($this->settings['normal_setting']->is_deprecated());
		$this->assertTrue($this->settings['deprecated_setting']->is_deprecated());
	}
	function test_set_deprecated() {
		$new_setting = new \mtekk\adminKit\setting\setting_absint(
				'normal_setting',
				42,
				'Normal Setting');
		//Check initial value from the constructed version
		$this->assertFalse($new_setting->is_deprecated());
		//Change to being deprecated
		$new_setting->set_deprecated(true);
		$this->assertTrue($new_setting->is_deprecated());
		//Change back
		$new_setting->set_deprecated(false);
		$this->assertFalse($new_setting->is_deprecated());
	}
	function test_get_value() {
		$this->assertSame($this->settings['normal_setting']->get_value(), 42);
		$this->assertSame($this->settings['deprecated_setting']->get_value(), 30);
	}
	function test_set_value() {
		//Check default value
		$this->assertSame($this->settings['normal_setting']->get_value(), 42);
		//Change the value
		$this->settings['normal_setting']->set_value(67);
		//Check
		$this->assertSame($this->settings['normal_setting']->get_value(), 67);
	}
	function test_get_title() {
		$this->assertSame($this->settings['normal_setting']->get_title(), 'Normal Setting');
	}
	function test_get_name() {
		$this->assertSame($this->settings['normal_setting']->get_name(), 'normal_setting');
	}
	function test_get_allow_empty() {
		$this->assertFalse($this->settings['normal_setting']->get_allow_empty());
		$this->assertTrue($this->settings['empty_ok_setting']->get_allow_empty());
	}
	function test_set_allow_empty() {
		$this->assertTrue($this->settings['empty_ok_setting']->get_allow_empty());
		$this->settings['empty_ok_setting']->set_allow_empty(false);
		$this->assertFalse($this->settings['empty_ok_setting']->get_allow_empty());
	}
	function test_get_opt_name() {
		$this->assertSame('a' . $this->settings['normal_setting']->get_name(), $this->settings['normal_setting']->get_opt_name());
	}
	function test_maybe_update_from_form_input() {
		$input = array('anormal_setting' => 45, 'anormal_settinga' => 423);
		$input_notthere = array('anormal_settinga' => 53, 'aabnormal_setting' => 33);
		//Test allowing empty
		$this->settings['normal_setting']->set_allow_empty(true);
		$this->settings['normal_setting']->maybe_update_from_form_input($input);
		$this->assertSame($this->settings['normal_setting']->get_value(), 45);
		//Change the value
		$this->settings['normal_setting']->set_value(67);
		$this->settings['normal_setting']->maybe_update_from_form_input($input_notthere);
		$this->assertSame($this->settings['normal_setting']->get_value(), 67);
		//Test diallowing empty
		$this->settings['empty_ok_setting']->set_allow_empty(false);
		//Change the value
		$this->settings['normal_setting']->set_value(67);
		$this->settings['normal_setting']->maybe_update_from_form_input($input);
		$this->assertSame($this->settings['normal_setting']->get_value(), 45);
		//Change the value
		$this->settings['normal_setting']->set_value(67);
		$this->settings['normal_setting']->maybe_update_from_form_input($input_notthere);
		$this->assertSame($this->settings['normal_setting']->get_value(), 67);
	}
	function test_validate() {
		//Test a normal/expected condition
		$this->assertSame($this->settings['normal_setting']->validate(42), 42);
		//Test a string
		$this->assertSame($this->settings['normal_setting']->validate('42'), 42);
		//Test a negative numbeer
		$this->assertSame($this->settings['normal_setting']->validate(-42), 42);
	}
}
