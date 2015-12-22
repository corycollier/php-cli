<?php

namespace PhpCli;

use PhpCli\Output;

class OutputTest extends \PHPUnit_Framework_TestCase
{
    /**
     * Tests the write method.
     *
     * @param string $message The message to write.
     * @param array $params An array of params to use in testing.
     *
     * @dataProvider dataWrite
     */
    public function testWrite($message, $params = [])
    {
        $sut = $this->getMockBuilder('\PhpCli\Output')
            ->setMethods([
                'getMergedWriteParams',
                'getMessagePrefix',
                'getDecoratedMessage',
                'getReset',
            ])
            ->getMock();

        $sut->expects($this->once())
            ->method('getMergedWriteParams')
            ->with($this->equalTo($params))
            ->will($this->returnValue($params));
        $sut->expects($this->once())
            ->method('getMessagePrefix')
            ->with($this->equalTo($params))
            ->will($this->returnValue(''));
        $sut->expects($this->once())
            ->method('getDecoratedMessage')
            ->with($this->equalTo($message), $this->equalTo($params))
            ->will($this->returnValue(''));

        $sut->expects($this->once())
            ->method('getReset')
            ->will($this->returnValue(''));

        $result = $sut->write($message, $params);
        $this->assertEquals($sut, $result);
    }

    /**
     * Tests the getMergedWriteParams method.
     *
     * @param arary $expected The expected result of the test.
     * @param array $params The input params to test with.
     *
     * @dataProvider dataGetMergedWriteParams
     */
    public function testGetMergedWriteParams($expected, $params)
    {
        $sut = new Output;

        $reflection = new \ReflectionClass('\PhpCli\Output');

        $reflection_method = $reflection->getMethod('getMergedWriteParams');
        $reflection_method->setAccessible(true);
        $result = $reflection_method->invoke($sut, $params);

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests the getDecoratedMessage method.
     *
     * @param string $expected The expected result of the test.
     * @param string $message The string to decorate
     * @param array $params An array of parameters to use.
     *
     * @dataProvider dataGetDecoratedMessage
     */
    public function testGetDecoratedMessage($expected, $message, $params = [])
    {
        $sut = new \PhpCli\Output;

        $reflection = new \ReflectionClass('\PhpCli\Output');

        $reflection_method = $reflection->getMethod('getDecoratedMessage');
        $reflection_method->setAccessible(true);
        $result = $reflection_method->invoke($sut, $message, $params);

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests the getMessagePrefix method.
     *
     * @param string $expected The expected result of the test.
     * @param array $params An array of parameters to use in testing.
     *
     * @dataProvider dataGetMessagePrefix
     */
    public function testGetMessagePrefix($expected, $params = [])
    {
        $sut = new Output;

        $reflection = new \ReflectionClass('\PhpCli\Output');

        $reflection_method = $reflection->getMethod('getMessagePrefix');
        $reflection_method->setAccessible(true);
        $result = $reflection_method->invoke($sut, $params);

        $this->assertEquals($expected, $result);
    }

    /**
     * Tests the getReset methods
     */
    public function testGetReset()
    {
        $expected = "\033[0m";
        $sut      = new Output;
        $result = $sut->getReset();

        $this->assertEquals($expected, $result);
    }

    /**
     * Data Provider for testing the write method.
     *
     * @return array
     */
    public function dataWrite()
    {
        return [

            // simple message, simple params
            'simple message, simple params' => [
                'message' => 'message',
                'params'  => [],
            ],

        ];
    }

    /**
     * Tests the getMergedWriteParams
     * @return [type] [description]
     */
    public function dataGetMergedWriteParams()
    {
        $defaults = [
            'background' => false,
            'color'      => false,
            'bold'       => false,
            'underline'  => false,
        ];

        return [

            // empty test
            'empty test' => [
                'expected' => array_merge($defaults, []),
                'params'   => [],
            ],

            // backgroundtest
            'has background' => [
                'expected' => array_merge($defaults, [
                    'background' => 'value',
                ]),
                'params'   => [
                    'background' => 'value',
                ],
            ],

            // color test
            'has color' => [
                'expected' => array_merge($defaults, [
                    'color' => 'value',
                ]),
                'params'   => [
                    'color' => 'value',
                ],
            ],

            // color test
            'is bold' => [
                'expected' => array_merge($defaults, [
                    'bold' => true,
                ]),
                'params'   => [
                    'bold' => true,
                ],
            ],

        ];
    }

    /**
     * Data provider for testing getDecoratedMessage
     * @return array
     */
    public function dataGetDecoratedMessage()
    {
        return [
            // simple test
            'simple test' => [
                'expected' => 'message',
                'message'  => 'message',
                'params'   => [],
            ],
            // red color
            'red color' => [
                'expected' => '31mmessage',
                'message'  => 'message',
                'params'   => [
                    'color' => 'red'
                ],
            ],
            // green color
            'green color' => [
                'expected' => '32mmessage',
                'message'  => 'message',
                'params'   => [
                    'color' => 'green'
                ],
            ],
            // yellow color
            'yellow color' => [
                'expected' => '33mmessage',
                'message'  => 'message',
                'params'   => [
                    'color' => 'yellow'
                ],
            ],
            // blue color
            'blue color' => [
                'expected' => '34mmessage',
                'message'  => 'message',
                'params'   => [
                    'color' => 'blue'
                ],
            ],
            // magenta color
            'magenta color' => [
                'expected' => '35mmessage',
                'message'  => 'message',
                'params'   => [
                    'color' => 'magenta'
                ],
            ],
            // cyan color
            'cyan color' => [
                'expected' => '36mmessage',
                'message'  => 'message',
                'params'   => [
                    'color' => 'cyan'
                ],
            ],
            // light gray color
            'light gray color' => [
                'expected' => '37mmessage',
                'message'  => 'message',
                'params'   => [
                    'color' => 'light gray'
                ],
            ],
            // dark gray color
            'dark gray color' => [
                'expected' => '90mmessage',
                'message'  => 'message',
                'params'   => [
                    'color' => 'dark gray'
                ],
            ],
            // light red color
            'light red color' => [
                'expected' => '91mmessage',
                'message'  => 'message',
                'params'   => [
                    'color' => 'light red'
                ],
            ],
            // light green color
            'light green color' => [
                'expected' => '92mmessage',
                'message'  => 'message',
                'params'   => [
                    'color' => 'light green'
                ],
            ],
            // light yellow color
            'light yellow color' => [
                'expected' => '93mmessage',
                'message'  => 'message',
                'params'   => [
                    'color' => 'light yellow'
                ],
            ],
            // light blue color
            'light blue color' => [
                'expected' => '94mmessage',
                'message'  => 'message',
                'params'   => [
                    'color' => 'light blue'
                ],
            ],
            // light magenta color
            'light magenta color' => [
                'expected' => '95mmessage',
                'message'  => 'message',
                'params'   => [
                    'color' => 'light magenta'
                ],
            ],
            // light cyan color
            'light cyan color' => [
                'expected' => '96mmessage',
                'message'  => 'message',
                'params'   => [
                    'color' => 'light cyan'
                ],
            ],
            // white color
            'white color' => [
                'expected' => '97mmessage',
                'message'  => 'message',
                'params'   => [
                    'color' => 'white'
                ],
            ],
        ];
    }

    /**
     * Data provider for testing getMessagePrefix.
     * @return array
     */
    public function dataGetMessagePrefix()
    {
        return [
            // expect null, empty params
            'expect null, empty params' => [
                'expected' => null,
                'params' => [],
            ],

            // expect null, empty params
            'expect prefix, NOT empty params' => [
                'expected' => "\033[",
                'params' => [
                    'key' => 'value',
                ],
            ],
        ];
    }

    /**
     * Tests the PhpCli\Output::newline method.
     */
    public function testNewline()
    {
        $sut = new \PhpCli\Output;
        $result = $sut->newline();
        $this->assertEquals($sut, $result);
    }
}
