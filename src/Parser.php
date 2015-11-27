<?php
/**
 * The Parser class for the PhpCli package
 *
 * @category  PHP
 * @package   PhpCli
 * @author    Cory Collier <corycollier@corycollier.com>
 * @license   https://github.com/corycollier/php-cli/blob/master/LICENSE
 * @link      https://github.com/corycollier/php-cli
 */

namespace PhpCli;

/**
 * Class to handle parsing cli arguments
 *
 * @category  PHP
 * @package   PhpCli
 * @author    Cory Collier <corycollier@corycollier.com>
 * @license   https://github.com/corycollier/php-cli/blob/master/LICENSE
 * @link      https://github.com/corycollier/php-cli
 */
class Parser
{
    const ERR_NO_ARG_BY_NAME = 'No argument exists with the provided name';

    protected $argv = array();
    protected $parsedArgs = array();
    protected $supportedArgs = array();

    /**
     * Constructor.
     *
     * @param array $argv The value for $argv.
     * @param integer $argc The value for $argc.
     */
    public function __construct(array $argv = array(), array $supported = array())
    {
        $this->argv = $argv;
        $this->supportedArgs = $supported;

        $this->init();
    }

    /**
     * Local implementation of the init hook.
     *
     * @return PhpCli\Parser Returns $this, for object-chaining.
     */
    public function init()
    {
        $this->setupSupportedArgs($this->supportedArgs);
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

            if (array_key_exists($key, $this->supportedArgs)) {
                $this->parsedArgs[$key] = $value;
            }

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
    public function getArg($name = '')
    {
        if (!isset($this->parsedArgs[$name])) {
            throw new \PhpCli\Exception(self::ERR_NO_ARG_BY_NAME);
        }

        return $this->parsedArgs[$name];
    }

    /**
     * Takes the supported arguments, and does some setup on them
     *
     * @param array $arguments The list of args/descriptions that are supported.
     *
     * @return PhpCli\Parser Returns $this, for object-chaining.
     */
    public function setupSupportedArgs($arguments = array())
    {
        $defaults = array(
            'help' => 'The help menu',
        );

        $arguments = array_merge($defaults, $arguments);

        $this->supportedArgs = $arguments;

        return $this;
    }

    /**
     * Outputs the help menu.
     *
     * @return PhpCli\Parser Returns $this, for object-chaining.
     */
    public function help()
    {
        $output = new Output;
        $maxlen = 0;
        foreach ($this->supportedArgs as $key => $description) {
            $len = strlen($key);
            if ($len > $maxlen) {
                $maxlen = $len;
            }
        }

        foreach ($this->supportedArgs as $key => $description) {
            $len = strlen($key);
            $output->write($key, array('bold' => true,))
                ->write(str_repeat(' ', $maxlen - $len))
                ->write(' - ')
                ->write($description);

            echo PHP_EOL;
        }

    }
}
