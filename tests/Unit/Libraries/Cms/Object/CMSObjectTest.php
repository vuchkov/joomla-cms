<?php
/**
 * @package     Joomla.UnitTest
 * @subpackage  Base
 *
 * @copyright   Copyright (C) 2005 - 2017 Open Source Matters, Inc. All rights reserved.
 * @license     GNU General Public License version 2 or later; see LICENSE.txt
 */

namespace Joomla\Tests\Unit\Libraries\Cms\Object;

use Exception;
use Joomla\CMS\Object\CMSObject;
use Joomla\Tests\Unit\UnitTestCase;

/**
 * Test class for JObject.
 * Generated by PHPUnit on 2009-09-24 at 17:15:16.
 *
 * @package     Joomla.UnitTest
 * @subpackage  Object
 * @since       11.1
 */
class CMSObjectTest extends UnitTestCase
{
	/**
	 * Tests the object constructor.
	 *
	 * @group     JObject
	 * @covers    JObject::__construct
	 * @return void
	 */
	public function testIsConstructable()
	{
		$object = new CMSObject(['property1' => 'value1', 'property2' => 5]);

		$this->assertEquals('value1', $object->get('property1'));
	}

	/**
	 * Tests setting the default for a property of the object.
	 *
	 * @group     JObject
	 * @covers    JObject::def
	 * @return void
	 */
	public function testDef()
	{
		$object = new CMSObject;

		$object->def("check");
		$this->assertEquals(null, $object->def("check"));
		$object->def("check", "paint");
		$object->def("check", "forced");
		$this->assertEquals("paint", $object->def("check"));
		$this->assertNotEquals("forced", $object->def("check"));
	}

	/**
	 * Tests getting a property of the object.
	 *
	 * @group     JObject
	 * @covers    JObject::get
	 * @return void
	 */
	public function testGet()
	{
		$object = new CMSObject;

		$object->goo = 'car';
		$this->assertEquals('car', $object->get('goo', 'fudge'));
		$this->assertEquals('fudge', $object->get('foo', 'fudge'));
		$this->assertNotEquals(null, $object->get('foo', 'fudge'));
		$this->assertNull($object->get('boo'));
	}

	/**
	 * Tests getting the properties of the object.
	 *
	 * @group     JObject
	 * @covers    JObject::getProperties
	 * @return void
	 */
	public function testGetProperties()
	{
		$object = new CMSObject([
			'_privateproperty1' => 'valuep1',
			'property1'         => 'value1',
			'property2'         => 5
		]);
		$this->assertEquals(
			[
				'_errors'           => [],
				'_privateproperty1' => 'valuep1',
				'property1'         => 'value1',
				'property2'         => 5
			],
			$object->getProperties(false),
			'Should get all properties, including private ones'
		);
		$this->assertEquals(
			[
				'property1' => 'value1',
				'property2' => 5
			],
			$object->getProperties(),
			'Should get all public properties'
		);
	}

	/**
	 * Tests getting a single error.
	 *
	 * @group     JObject
	 * @covers    JObject::getError
	 * @return void
	 */
	public function testGetError()
	{
		$object = new CMSObject;

		$object->setError(1234);
		$object->setError('Second Test Error');
		$object->setError('Third Test Error');
		$this->assertEquals(
			1234,
			$object->getError(0, false),
			'Should return the test error as number'
		);
		$this->assertEquals(
			'Second Test Error',
			$object->getError(1),
			'Should return the second test error'
		);
		$this->assertEquals(
			'Third Test Error',
			$object->getError(),
			'Should return the third test error'
		);
		$this->assertFalse(
			$object->getError(20),
			'Should return false, since the error does not exist'
		);

		$exception = new Exception('error');
		$object->setError($exception);
		$this->assertThat(
			$object->getError(3, true),
			$this->equalTo('error')
		);
	}

	/**
	 * Tests getting the array of errors.
	 *
	 * @group     JObject
	 * @covers    JObject::getErrors
	 * @return void
	 */
	public function testGetErrors()
	{
		$object = new CMSObject;

		$errors = [1234, 'Second Test Error', 'Third Test Error'];

		foreach ($errors as $error)
		{
			$object->setError($error);
		}
		$this->assertAttributeEquals(
			$object->getErrors(),
			'_errors',
			$object
		);
		$this->assertEquals(
			$errors,
			$object->getErrors(),
			'Should return every error set'
		);
	}

	/**
	 * Tests setting a property.
	 *
	 * @group     JObject
	 * @covers    JObject::set
	 * @return void
	 */
	public function testSet()
	{
		$object = new CMSObject;

		$this->assertEquals(null, $object->set("foo", "imintheair"));
		$this->assertEquals("imintheair", $object->set("foo", "nojibberjabber"));
		$this->assertEquals("nojibberjabber", $object->foo);
	}

	/**
	 * Tests setting multiple properties.
	 *
	 * @group     JObject
	 * @covers    JObject::setProperties
	 * @return void
	 */
	public function testSetProperties()
	{
		$object = new CMSObject;
		$a = ["foo" => "ghost", "knife" => "stewie"];
		$f = "foo";

		$this->assertEquals(true, $object->setProperties($a));
		$this->assertEquals(false, $object->setProperties($f));
		$this->assertEquals("ghost", $object->foo);
		$this->assertEquals("stewie", $object->knife);
	}

	/**
	 * Tests setting an error.
	 *
	 * @group     JObject
	 * @covers    JObject::setError
	 * @return void
	 */
	public function testSetError()
	{
		$object = new CMSObject;
		$object->setError('A Test Error');
		$this->assertAttributeEquals(
			array('A Test Error'),
			'_errors',
			$object
		);
	}
}
