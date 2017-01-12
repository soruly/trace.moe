<?php

namespace Alchemy\Tests\BinaryDriver;

use Alchemy\BinaryDriver\Configuration;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    public function testArrayAccessImplementation()
    {
        $configuration = new Configuration(array('key' => 'value'));

        $this->assertTrue(isset($configuration['key']));
        $this->assertEquals('value', $configuration['key']);

        $this->assertFalse(isset($configuration['key2']));
        unset($configuration['key']);
        $this->assertFalse(isset($configuration['key']));

        $configuration['key2'] = 'value2';
        $this->assertTrue(isset($configuration['key2']));
        $this->assertEquals('value2', $configuration['key2']);
    }

    public function testGetOnNonExistentKeyShouldReturnDefaultValue()
    {
        $conf = new Configuration();
        $this->assertEquals('booba', $conf->get('hooba', 'booba'));
        $this->assertEquals(null, $conf->get('hooba'));
    }

    public function testSetHasGetRemove()
    {
        $configuration = new Configuration(array('key' => 'value'));

        $this->assertTrue($configuration->has('key'));
        $this->assertEquals('value', $configuration->get('key'));

        $this->assertFalse($configuration->has('key2'));
        $configuration->remove('key');
        $this->assertFalse($configuration->has('key'));

        $configuration->set('key2', 'value2');
        $this->assertTrue($configuration->has('key2'));
        $this->assertEquals('value2', $configuration->get('key2'));
    }

    public function testIterator()
    {
        $data = array(
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3',
        );

        $captured = array();
        $conf = new Configuration($data);

        foreach ($conf as $key => $value) {
            $captured[$key] = $value;
        }

        $this->assertEquals($data, $captured);
    }

    public function testAll()
    {
        $data = array(
            'key1' => 'value1',
            'key2' => 'value2',
            'key3' => 'value3',
        );

        $conf = new Configuration($data);
        $this->assertEquals($data, $conf->all());
    }

}
