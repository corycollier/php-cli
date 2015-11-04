<?php

namespace PhpCli;

class Parser
{
    protected $argv = array();
    protected $argc = 0;
    protected $parsedArgs = array();

    public function __construct(array $argv = array(), $argc = 0)
    {
        $this->argv = $argv;
        $this->argc = $argc;

        $this->init();
    }

    /**
     * Local implementation of the init hook.
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
     * @param  array $argv The raw array of arguments
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


    public function getArg ($name = '')
    {

    }
}