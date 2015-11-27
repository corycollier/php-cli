<?php

namespace PhpCli;

class Output
{
    protected $decorations = array(
        'color' => array(
            'black'         => '30',
            'red'           => '31',
            'green'         => '32',
            'yellow'        => '33',
            'blue'          => '34',
            'magenta'       => '35',
            'cyan'          => '36',
            'light gray'    => '37',
            'dark gray'     => '90',
            'light red'     => '91',
            'light green'   => '92',
            'light yellow'  => '93',
            'light blue'    => '94',
            'light magenta' => '95',
            'light cyan'    => '96',
            'white'         => '97',
        ),
        'background' => array(
            'black'         => '40',
            'red'           => '41',
            'green'         => '42',
            'yellow'        => '43',
            'blue'          => '44',
            'magenta'       => '45',
            'cyan'          => '46',
            'light gray'    => '47',
            'dark gray'     => '100',
            'light red'     => '101',
            'light green'   => '102',
            'light yellow'  => '103',
            'light blue'    => '104',
            'light magenta' => '105',
            'light cyan'    => '106',
            'white'         => '107',
        ),
        'bold' => array(
            1 => '1'
        ),
        'underline' => array(
            1 => '4'
        ),
    );

    /**
     * writes a messge to the output
     *
     * @param string $message The message to write.
     * @param array  $params Array of parameters for writing.
     *
     * @return \PhpCli\Output Return $this for object-chaining.
     */
    public function write($message, array $params = array())
    {
        $options = $this->getMergedWriteParams($params);

        echo $this->getMessagePrefix($options)
            , $this->getDecoratedMessage($message, $options)
            , $this->getReset();

        return $this;
    }

    /**
     * Gets merged params with defaults
     *
     * @param array $params An array of parameters to merge with.
     *
     * @return array An array of merged parameters.
     */
    protected function getMergedWriteParams(array $params = array())
    {
        $defaults = array(
            'background' => false,
            'color'      => false,
            'bold'       => false,
            'underline'  => false,
        );

        return array_merge($defaults, $params);
    }

    /**
     * Gets a decorated version of the message.
     *
     * @param  string $message The message to output.
     * @param  array $params An array of options for outputting the message.
     *
     * @return string The decorated message.
     */
    protected function getDecoratedMessage($message, array $params = array())
    {
        $result = '';
        $prefix = '';
        foreach ($params as $option => $value) {
            if ($value) {
                $result .= $prefix . $this->decorations[$option][$value];
                $prefix = ';';
            }
        }

        if ($prefix) {
            $message = 'm' . $message;
        }

        return $result . $message;
    }

    /**
     * Starts the output of a message write. This is to preface a message with
     * the necessary string modifiers for modified cli output
     *
     * @param  array  $params An array of params, used to determine if output modified.
     *
     * @return string The resulting string to modify output (or not)
     */
    protected function getMessagePrefix(array $params = array())
    {
        foreach ($params as $option => $value) {
            if ($value) {
                return '\e[';
            }
        }
    }

    /**
     * Resets the writing output
     *
     * @return string The cli reset code.
     */
    public function getReset()
    {
        return '\e[0m';
    }
}
