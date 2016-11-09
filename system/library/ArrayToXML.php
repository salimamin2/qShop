<?php
class ArrayToXML
{
    /**
     * The main function for converting to an XML document.
     * Pass in a multi dimensional array and this recrusively loops through and builds up an XML document.
     *
     * @param array $data
     * @param string $rootNodeName - what you want the root node to be - defaultsto data.
     * @param bool formated - formated the output or send raw output
     * @return string XML
     */
    public static function toXML( $data, $rootNodeName = 'Response', $formated=true) {
        return DOM::toXML($data,$rootNodeName,$formated);
    }


    /**
     * Converts a simpleXML element into an array. Preserves attributes and everything.
     * You can choose to get your elements either flattened, or stored in a custom index that
     * you define.
     * For example, for a given element
     * <field name="someName" type="someType"/>
     * if you choose to flatten attributes, you would get:
     * $array['field']['name'] = 'someName';
     * $array['field']['type'] = 'someType';
     * If you choose not to flatten, you get:
     * $array['field']['@attributes']['name'] = 'someName';
     * _____________________________________
     * Repeating fields are stored in indexed arrays. so for a markup such as:
     * <parent>
     * <child>a</child>
     * <child>b</child>
     * <child>c</child>
     * </parent>
     * you array would be:
     * $array['parent']['child'][0] = 'a';
     * $array['parent']['child'][1] = 'b';
     * ...And so on.
     * _____________________________________
     * @param simpleXMLElement $xml the XML to convert
     * @param boolean $flattenValues    Choose wether to flatten values
     *                                    or to set them under a particular index.
     *                                    defaults to true;
     * @param boolean $flattenAttributes Choose wether to flatten attributes
     *                                    or to set them under a particular index.
     *                                    Defaults to true;
     * @param boolean $flattenChildren    Choose wether to flatten children
     *                                    or to set them under a particular index.
     *                                    Defaults to true;
     * @param string $valueKey            index for values, in case $flattenValues was set to
            *                            false. Defaults to "@value"
     * @param string $attributesKey        index for attributes, in case $flattenAttributes was set to
            *                            false. Defaults to "@attributes"
     * @param string $childrenKey        index for children, in case $flattenChildren was set to
            *                            false. Defaults to "@children"
     * @return array the resulting array.
     */
    public static function toArray($xml,
                    $flattenValues=true,
                    $flattenAttributes = true,
                    $flattenChildren=true,
                    $valueKey='@value',
                    $attributesKey='@attributes',
                    $childrenKey='@children'){

        $return = array();
        if(!($xml instanceof SimpleXMLElement)){return $return;}
        $name = $xml->getName();
        $_value = trim((string)$xml);
        if(strlen($_value)==0){$_value = null;};

        if($_value!==null){
            if(!$flattenValues){$return[$valueKey] = $_value;}
            else{$return = $_value;}
        }

        $children = array();
        $first = true;
        foreach($xml->children() as $elementName => $child){
            $value = self::toArray($child, $flattenValues, $flattenAttributes, $flattenChildren, $valueKey, $attributesKey, $childrenKey);
            if(isset($children[$elementName])){
                if($first){
                    $temp = $children[$elementName];
                    unset($children[$elementName]);
                    $children[$elementName][] = $temp;
                    $first=false;
                }
                $children[$elementName][] = $value;
            }
            else{
                $children[$elementName] = $value;
            }
        }
        if(count($children)>0){
            if(!$flattenChildren){$return[$childrenKey] = $children;}
            else{$return = array_merge($return,$children);}
        }

        $attributes = array();
        foreach($xml->attributes() as $name=>$value){
            $attributes[$name] = trim($value);
        }
        if(count($attributes)>0){
            if(!$flattenAttributes){$return[$attributesKey] = $attributes;}
            else{$return = array_merge($return, $attributes);}
        }

        return $return;
    }



    // determine if a variable is an associative array
    public static function isAssoc( $array ) {
        return (is_array($array) && 0 !== count(array_diff_key($array, array_keys(array_keys($array)))));
    }
}
class DOM{
 /**
   * @param array $source
   * This source array:
   *
   * Array
   * (
   *   [book] => Array
   *     (
   *       [0] => Array
   *         (
   *           [author] => Author0
   *           [title] => Title0
   *           [publisher] => Publisher0
   *         )
   *       [1] => Array
   *         (
   *           [author] => Array
   *             (
   *               [0] => Author1
   *               [1] => Author2
   *             )
   *           [title] => Title1
   *           [publisher] => Publisher1
   *         )
   *     )
   * )
   *
   * will produce this XML:
   *
   * <root>
   *   <book>
   *     <author>Author0</author>
   *     <title>Title0</title>
   *     <publisher>Publisher0</publisher>
   *   </book>
   *   <book>
   *     <author>Author1</author>
   *     <author>Author2</author>
   *     <title>Title1</title>
   *     <publisher>Publisher1</publisher>
   *   </book>
   * </root>
   * @param string $rootTagName
   * @return DOMDocument
   */
  public static function arrayToDOMDocument(array $source, $rootTagName = 'root')
  {
    $document = new DOMDocument();
    $document->appendChild(self::createDOMElement($source, $rootTagName, $document));

    return $document;
  }

  /**
   * @param array $source
   * @param string $rootTagName
   * @param bool $formatOutput
   * @return string
   */
  public static function toXML(array $source, $rootTagName = 'root', $formatOutput = true)
  {
    $document = self::arrayToDOMDocument($source, $rootTagName);
    $document->formatOutput = $formatOutput;
    return $document->saveXML();
  }

  private static function createDOMElement($source, $tagName, DOMDocument $document)
  {
    if (!is_array($source))
      return $document->createElement($tagName, $source);

    $element = $document->createElement($tagName);

    foreach ($source as $key => $value)
      if (is_string($key))
        foreach ((is_array($value) ? $value : array($value)) as $elementKey => $elementValue)
          $element->appendChild(self::createDOMElement($elementValue, $key, $document));
      else
        $element->appendChild(self::createDOMElement($value, $tagName, $document));

    return $element;
  }
}
?>
