<?php

namespace PhpCli;

class Parser
{
    const ERR_NO_ARG_BY_NAME = 'No argument exists with the provided name';

    protected $argv = array();
    protected $argc = 0;
    protected $parsedArgs = array();

    /**
     * Constructor.
     *
     * @param array $argv The value for $argv.
     * @param integer $argc The value for $argc.
     */
    public function __construct(array $argv = array(), $argc = 0)
    {
        $this->argv = $argv;
        $this->argc = $argc;

        $this->init();
    }

    /**
     * Local implementation of the init hook.
     *
     * @return PhpCli\Parser Returns $this, for object-chaining.
     */
    public function init()
    {
        $this->parseArgv($this->argv);
        return $this;
    }

    /**
     * Parses an array of arguments.
     *
     * @param  array $argv The raw array of arguments.
     *
     * @return PhpCli\Parser Returns $this, for object-chaining.
     */
    protected function parseArgv($argv)
    {
        foreach ($this->argv as $arg) {
            $parts = explode('=', $arg);
            if (! isset($parts[1])) {
                continue;
            }
            $value = $parts[1];
            $key   = strtr($parts[0], array(
                '-' => ''
            ));

            $this->parsedArgs[$key] = $value;
        }

        return $this;
    }

    /**
     * Get the parsed arguments.
     *
     * @return array The parsed arguments.
     */
    public function getArgs()
    {
        return $this->parsedArgs;
    }

    /**
     * Gets an argument by name
     *
     * @param string $name The name of the argument to get.
     *
     * @return string The value of the argument.
     */
    public function getArg ($name = '')
    {
        if (!isset($this->parsedArgs[$name])) {
            throw new \PhpCli\Exception(self::ERR_NO_ARG_BY_NAME);
        }

        return $this->parsedArgs[$name];
    }
}
