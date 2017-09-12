<?php
/**
 * XML conversion utils.
 *
 * @author @jaswrks
 * @copyright WebSharks™
 */
declare (strict_types = 1);
namespace WebSharks\Core\Classes\Core\Utils;

use WebSharks\Core\Classes;
use WebSharks\Core\Interfaces;
use WebSharks\Core\Traits;
#
use WebSharks\Core\Classes\Core\Error;
use WebSharks\Core\Classes\Core\Base\Exception;
#
use function assert as debug;
use function get_defined_vars as vars;

/**
 * XML conversion utils.
 *
 * @since 160829.74007 XML conversion utils.
 */
class Array2Xml extends Classes\Core\Base\Core
{
    /**
     * Array to XML markup.
     *
     * @since 160829.74007 XML conversion utils.
     *
     * @param string $parent_element_name Parent element name.
     * @param array  $array               Input array to convert.
     * @param array  $args                Any additional behavioral args.
     *
     * @return string XML or HTML (with or w/o a DOCTYPE tag).
     *
     * @note `<!DOCTYPE html>` is an HTML DOCTYPE tag.
     * @note `<?xml version="1.0" encoding="utf-8"?>` is an XML DOCTYPE tag.
     */
    public function __invoke(string $parent_element_name, array $array, array $args = []): string
    {
        $default_args = [
            'type'            => 'xml',
            'version'         => '1.0',
            'encoding'        => 'utf-8',
            'include_doctype' => true,
            'format'          => true,
        ];
        $args += $default_args; // Merge w/ defaults.

        $args['type']            = (string) $args['type'];
        $args['version']         = (string) $args['version'];
        $args['encoding']        = (string) $args['encoding'];
        $args['include_doctype'] = (bool) $args['include_doctype'];
        $args['format']          = (bool) $args['format'];

        if ($args['type'] === 'html') {
            $DOMImplementation = new \DOMImplementation();
            $DOMDocumentType   = $DOMImplementation->createDocumentType($args['type']);
            $DOMDocument       = $DOMImplementation->createDocument('', '', $DOMDocumentType);
        } else {
            $DOMDocument = new \DOMDocument($args['version']);
        }
        $DOMDocument->encoding     = $args['encoding'];
        $DOMDocument->formatOutput = $args['format']; // Indentation.
        $save                      = $args['type'] === 'html' ? 'saveHTML' : 'saveXML';

        $ParentDOMElement = $DOMDocument->createElement($parent_element_name);
        $DOMDocument->appendChild($ParentDOMElement);

        $this->convert($DOMDocument, $ParentDOMElement, $array);

        if (!$args['include_doctype']) {
            return (string) $DOMDocument->{$save}($DOMDocument->documentElement);
        } else {
            return (string) $DOMDocument->{$save}(); // With doctype.
        }
    }

    /**
     * Array to HTML markup.
     *
     * @since 160829.74007 XML conversion utils.
     *
     * @param string $parent_element_name Parent element name.
     * @param array  $array               Input array to convert.
     * @param array  $args                Any additional behavioral args.
     *
     * @return string HTML (with or w/o a DOCTYPE tag).
     */
    public function toHtml(string $parent_element_name, array $array, array $args = []): string
    {
        return $this->__invoke($parent_element_name, $array, array_merge($args, ['type' => 'html']));
    }

    /**
     * Array to XML handler.
     *
     * @since 160829.74007 XML conversion utils.
     *
     * @param \DOMDocument $DOMDocument      Class instance.
     * @param \DOMElement  $ParentDOMElement Class instance.
     * @param array        $array            Input array to convert.
     */
    protected function convert(\DOMDocument $DOMDocument, \DOMElement $ParentDOMElement, array $array)
    {
        foreach ($array as $_child_key => $_child_value) {
            if (is_string($_child_key)) { // Allows duplicate string keys.
                $_child_key = preg_replace('/^[0-9]+_/u', '', $_child_key);
            }
            if (is_array($_child_value)) { // Nested tag.
                $_ChildDOMElement = $DOMDocument->createElement($_child_key);
                $ParentDOMElement->appendChild($_ChildDOMElement);
                $this->convert($DOMDocument, $_ChildDOMElement, $_child_value);
                //
            } elseif (is_scalar($_child_value)) { // Must be scalar.
                // String keys become attributes; numeric keys text nodes.
                $_child_value = (string) $_child_value; // Force string.

                if (is_string($_child_key)) {
                    $_ChildDOMAttr = $DOMDocument->createAttribute($_child_key);
                    $ParentDOMElement->appendChild($_ChildDOMAttr);
                    $_ChildDOMAttr->value = $this->c::escAttr($_child_value);
                } else {
                    $_ChildDOMText = $DOMDocument->createTextNode($_child_value);
                    $ParentDOMElement->appendChild($_ChildDOMText);
                }
            } else { // Fail on unexpected values.
                throw $this->c::issue('Unexpected non-scalar child value.');
            }
        } // unset($_child_key, $_child_value, $_ChildDOMElement, $_ChildDOMAttr, $_ChildDOMText);
    }
}
