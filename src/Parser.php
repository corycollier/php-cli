<?php

namespace PhpCli;

class Parser
{
    protected $argv = array();
    protected $argc = 0;

    public function __construct(array $argv = array(), $argc = 0)
    {
        $this->argv = $argv;
        $this->argc = $argc;
    }
}