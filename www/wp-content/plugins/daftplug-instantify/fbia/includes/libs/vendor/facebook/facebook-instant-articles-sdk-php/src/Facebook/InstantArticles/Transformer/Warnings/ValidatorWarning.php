<?php
/**
 * Copyright (c) 2016-present, Facebook, Inc.
 * All rights reserved.
 *
 * This source code is licensed under the license found in the
 * LICENSE file in the root directory of this source tree.
 */
namespace Facebook\InstantArticles\Transformer\Warnings;

use Facebook\InstantArticles\Elements\Element;
use Facebook\InstantArticles\Validators\Type;

class ValidatorWarning extends Warning
{
    /**
     * @var Element
     */
    private $element;

    /**
     * @var array the configuration content
     */
    private $configuration;

    /**
     * @param Element $element
     * @param DOMNode $node
     */
    public function __construct($element, $node = null)
    {
        parent::__construct($node);
        $this->element = $element;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->formatWarningMessage();
    }

    /**
     * @return Element
     */
    public function getElement()
    {
        return $this->element;
    }

    private function formatWarningMessage()
    {
        $object = Type::stringify($this->element);
        if (!$this->configuration) {
            $this->configuration = parse_ini_file("validator_warning_messages.ini", true);
        }
        $simple_class_name = substr(strrchr($this->element->getClassName(), '\\'), 1);

        if (!isset($this->configuration['warning_messages'][$simple_class_name])) {
            $message = 'Invalid content on the object.';
        } else {
            $message = $this->configuration['warning_messages'][$simple_class_name];
        }
        return $message;
    }
}
