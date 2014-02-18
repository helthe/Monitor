<?php

/*
 * This file is part of the Helthe Monitor package.
 *
 * (c) Carl Alexander <carlalexander@helthe.co>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Helthe\Monitor\Tests;

use Helthe\Monitor\Configuration;
use Symfony\Component\Config\Definition\Processor;

class ConfigurationTest extends \PHPUnit_Framework_TestCase
{
    /**
     * @var Processor
     */
    private $processor;

    /**
     * {@inheritdoc}
     */
    protected function setUp()
    {
        $this->processor = new Processor();
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testEmptyConfig()
    {
        $this->processor->processConfiguration(new Configuration(), array(array()));
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testEmptyProjectKeyConfig()
    {
        $this->processor->processConfiguration(new Configuration(), array(array('api_key' => '')));
    }

    public function testWithProjectKeyOnly()
    {
        $config = $this->processor->processConfiguration(new Configuration(), array(array('api_key' => 'foo')));

        $this->assertEquals(array('api_key' => 'foo', 'endpoint' => 'https://api.helthe.co', 'error_level' => null), $config);
    }

    public function testOverrideEverything()
    {
        $config = $this->processor->processConfiguration(new Configuration(), array(array('api_key' => 'foo', 'endpoint' => 'http://api.helthe.local')));

        $this->assertEquals(array('api_key' => 'foo', 'endpoint' => 'http://api.helthe.local', 'error_level' => null), $config);
    }

    /**
     * @expectedException \Symfony\Component\Config\Definition\Exception\InvalidConfigurationException
     */
    public function testEmptyRootDirConfig()
    {
        $this->processor->processConfiguration(new Configuration(), array(array('api_key' => 'foo', 'application' => array('root_directory' => ''))));
    }

    public function testWithApplicationRootDirOnly()
    {
        $config = $this->processor->processConfiguration(new Configuration(), array(array('api_key' => 'foo', 'application' => array('root_directory' => 'foo/bar'))));

        $this->assertEquals(array('api_key' => 'foo', 'endpoint' => 'https://api.helthe.co', 'error_level' => null,  'application' => array('root_directory' => 'foo/bar', 'context' => array())), $config);
    }

    public function testWithApplicationVersion()
    {
        $config = $this->processor->processConfiguration(new Configuration(), array(array('api_key' => 'foo', 'application' => array('root_directory' => 'foo/bar', 'version' => 'test'))));

        $this->assertEquals(array('api_key' => 'foo', 'endpoint' => 'https://api.helthe.co', 'error_level' => null, 'application' => array('root_directory' => 'foo/bar', 'version' => 'test', 'context' => array())), $config);
    }

    public function testWithApplicationContext()
    {
        $config = $this->processor->processConfiguration(new Configuration(), array(array('api_key' => 'foo', 'application' => array('root_directory' => 'foo/bar', 'context' => array('route' => '_test_route')))));

        $this->assertEquals(array('api_key' => 'foo', 'endpoint' => 'https://api.helthe.co', 'error_level' => null, 'application' => array('root_directory' => 'foo/bar', 'context' => array('route' => '_test_route'))), $config);
    }
}
