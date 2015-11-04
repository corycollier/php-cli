<?php

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
  }
}