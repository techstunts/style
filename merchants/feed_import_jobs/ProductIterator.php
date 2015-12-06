<?php
require('xmlreader-iterators.php'); // https://gist.github.com/hakre/5147685

class ProductIterator extends XMLElementIterator {
    const ELEMENT_NAME = 'Product';

    public function __construct(XMLReader $reader) {
        parent::__construct($reader, self::ELEMENT_NAME);
    }

    /**
     * @return SimpleXMLElement
     */
    public function current() {
        return simplexml_load_string($this->reader->readOuterXml());
    }
}
