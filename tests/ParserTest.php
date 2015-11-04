<?php

use PhpCli\Parser;

class ParserTest extends PHPUnit_Framework_TestCase
{
  /**
   * Tests the parser's ability to set things on the constructor
   */
  public function testConstructor()
  {
    $argv = array(
      'path/to/file',
      'asdf=asdf',
      '--qwer=qwer'
    );

    $argc = count($argv);

    $sut = new PhpCli\Parser($argv, $argc);

    $reflection = new ReflectionClass('PhpCli\\Parser');

    $reflection_property = $reflection->getProperty('argv');
    $reflection_property->setAccessible(true);
    $this->assertEquals($argv, $reflection_property->getValue($sut));

    $reflection_property = $reflection->getProperty('argc');
    $reflection_property->setAccessible(true);
    $this->assertEquals($argc, $reflection_property->getValue($sut));
  }

  public function testInit()
  {
    $argv = array(
      'path/to/file',
      'asdf=asdf',
      '--qwer=qwer'
    );

    $sut = $this->getMockBuilder('PhpCli\Parser')
      ->disableOriginalConstructor()
      ->setMethods(array('parseArgv'))
      ->getMock();

    $reflection = new ReflectionClass('PhpCli\\Parser');

    $reflection_property = $reflection->getProperty('argv');
    $reflection_property->setAccessible(true);
    $reflection_property->setValue($sut, $argv);

    $sut->expects($this->once())
      ->method('parseArgv')
      ->with($this->equalTo($argv));

    $result = $sut->init();
    $this->assertEquals($sut, $result);
  }

  /**
   * Tests the parseArgv method.
   *
   * @param array $expected What the pasred Argv values should be.
   * @param array $argv The raw argv values.
   *
   * @dataProvider dataParseArgv
   */
  public function testParseArgv($expected, $argv)
  {
    $sut = new PhpCli\Parser($argv);
    $reflection = new ReflectionClass('PhpCli\\Parser');

    $reflection_method = $reflection->getMethod('parseArgv');
    $reflection_method->setAccessible(true);
    $result = $reflection_method->invoke($sut, $argv);

    $this->assertEquals($sut, $result);

    $reflection_property = $reflection->getProperty('argv');
    $reflection_property->setAccessible(true);
    $this->assertEquals($expected, $reflection_property->getValue($sut));
  }

  public function dataParseArgv()
  {
    return array(

      'empty argv, empty expectations' => array(
        'expected' => array(),
        'argv' => array(),
      ),

      'simple argv, simple expectations' => array(
        'expected' => array(
          'key' => 'value'
        ),
        'argv' => array(
          'key=value',
        ),
      ),

    );
  }
}
