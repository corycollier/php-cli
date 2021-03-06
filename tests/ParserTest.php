<?php

namespace PhpCli;

use PhpCli\Parser;

class ParserTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the parser's ability to set things on the constructor
     */
    public function testConstructor()
    {
        $argv = ['path/to/file', 'asdf=asdf', '--qwer=qwer'];

        $sut = new Parser($argv);

        $reflection = new \ReflectionClass('\PhpCli\Parser');

        $reflection_property = $reflection->getProperty('argv');
        $reflection_property->setAccessible(true);
        $this->assertEquals($argv, $reflection_property->getValue($sut));
    }

    public function testInit()
    {
        $argv = ['path/to/file', 'asdf=asdf', '--qwer=qwer'];

        $sut = $this->getMockBuilder('\PhpCli\Parser')
            ->disableOriginalConstructor()
            ->setMethods(['parseArgv'])
            ->getMock();

        $reflection = new \ReflectionClass('\PhpCli\Parser');

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
     * Tests the PhpCli\Parser::parseArgv method.
     *
     * @param array $expected What the pasred Argv values should be.
     * @param array $argv The raw argv values.
     *
     * @dataProvider dataParseArgv
     */
    public function testParseArgv($expected, $argv, $supported)
    {
        $sut = new Parser($argv);
        $reflection = new \ReflectionClass('\PhpCli\Parser');

        $property = $reflection->getProperty('supportedArgs');
        $property->setAccessible(true);
        $property->setValue($sut, $supported);

        $reflection_method = $reflection->getMethod('parseArgv');
        $reflection_method->setAccessible(true);
        $result = $reflection_method->invoke($sut, $argv);

        $this->assertEquals($sut, $result);

        $reflection_property = $reflection->getProperty('parsedArgs');
        $reflection_property->setAccessible(true);
        $this->assertEquals($expected, $reflection_property->getValue($sut));
    }

    /**
     * Tests the PhpCli\Parser::getArgs method.
     */
    public function testGetArgs()
    {
        $supported = $args = [
            'key1' => 'value1',
            'key2' => 'value2',
        ];

        $sut = new Parser($args, $supported);

        $reflection = new \ReflectionClass('\PhpCli\Parser');
        $reflection_property = $reflection->getProperty('parsedArgs');
        $reflection_property->setAccessible(true);
        $reflection_property->setValue($sut, $args);

        $result = $sut->getArgs();

        $this->assertEquals($args, $result);
    }

    /**
     * Tests the PhpCli\Parser::getArg method.
     *
     * @param string $expected The value of the argument that is expected.
     * @param string $name The name of the argument to get.
     * @param array $args The value of the parsedArgs.
     * @param boolean $exception If an exception should be expected.
     *
     * @dataProvider dataGetArg
     */
    public function testGetArg($expected, $name, $args = [], $exception = false)
    {
        $sut = new Parser($args);

        $reflection = new \ReflectionClass('\PhpCli\Parser');
        $reflection_property = $reflection->getProperty('parsedArgs');
        $reflection_property->setAccessible(true);
        $reflection_property->setValue($sut, $args);

        if ($exception) {
            $this->setExpectedException('Exception');
        }

        $result = $sut->getArg($name);
        $this->assertEquals($expected, $result);
    }

    /**
     * Data provider for testParseArgv.
     *
     * @return array An array of data to use for testing.
     */
    public function dataParseArgv()
    {
        return [

            // empty argv, empty expectations
            'empty argv, empty expectations' => [
                'expected'  => [],
                'argv'      => [],
                'supported' => [],
            ],

            // simple argv, simple expectations
            'simple argv, simple expectations' => [
                'expected' => [
                    'key' => 'value'
                ],
                'argv' => [
                    'key=value',
                ],
                'supported' => [
                    'key' => 'some description',
                ]
            ],

            // argv with dashes, filtered expectations
            'argv with dashes, filtered expectations' => [
                'expected' => [
                    'key' => 'value',
                    'key2' => 'value 2',
                ],
                'argv' => [
                    'key=value',
                    '--key2=value 2'
                ],
                'supported' => [
                    'key' => 'some description',
                    'key2' => 'some other description',
                ],
            ],
        ];
    }

    /**
     * Data provider for testGetArg.
     *
     * @return array An array of data to use for testing.
     */
    public function dataGetArg()
    {
        return [
            // expect value, has named arg
            'expect value, has named arg' => [
                'expected' => 'expected',
                'name'     => 'name',
                'args'     => [
                    'name' => 'expected',
                ],
            ],

            // expect value, has named arg
            'expect exception, has no named arg' => [
                'expected'  => 'expected',
                'name'      => 'name',
                'args'      => [],
                'exception' => true,
            ],
        ];
    }

    /**
     * Tests the PhpCli\Parser::help method
     *
     * @runInSeparateProcess
     */
    public function testHelp()
    {
        $sut = new \PhpCli\Parser;
        $result = $sut->help();
    }
}
