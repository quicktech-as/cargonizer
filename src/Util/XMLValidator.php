<?php

namespace Quicktech\Cargonizer\Util;

class XMLValidator
{
    public function validate($xmlContent, $schemaFile)
    {
        if ('' === trim($xmlContent)) {
            throw new \InvalidArgumentException(
                'File does not contain valid XML, it is empty.'
            );
        }

        $internalErrors = libxml_use_internal_errors(true);
        $disableEntities = libxml_disable_entity_loader(true);
        libxml_clear_errors();
        
        $dom = new \DOMDocument();
        $dom->validateOnParse = false;

        /**
         * LIBXML_COMPACT 
         * Activate small nodes allocation optimization. 
         * This may speed up your application without needing to change the code.
         * 
         * LIBXML_NONET
         * Disable network access when loading documents.
         */
        if (!$dom->loadXML($xmlContent, LIBXML_NONET | (defined('LIBXML_COMPACT') ? LIBXML_COMPACT : 0))) {
            libxml_disable_entity_loader($disableEntities);
            throw new \InvalidArgumentException(
                implode("\n", static::getXmlErrors($internalErrors))
            );
        }

        $dom->normalizeDocument();

        libxml_use_internal_errors($internalErrors);
        libxml_disable_entity_loader($disableEntities);
        
        foreach ($dom->childNodes as $child) {
            if ($child->nodeType === XML_DOCUMENT_TYPE_NODE) {
                throw new \InvalidArgumentException(
                    'Document types are not allowed.'
                );
            }
        }
        
        if (is_null($schemaFile) || !is_file($schemaFile)) {
            $internalErrors = libxml_use_internal_errors(true);
            libxml_clear_errors();

            throw new \InvalidArgumentException(
                'The schema argument has to be a valid path to XSD file.'
            );
        }

        $schemaSource = file_get_contents($schemaFile);
        $valid = @$dom->schemaValidateSource($schemaSource);

        if (!$valid) {
            $messages = static::getXmlErrors($internalErrors);

            if (empty($messages)) {
                $messages = ['The XML file is not valid.'];
            }

            throw new \InvalidArgumentException(
                implode("\n", $messages), 0, null
            );
        }
        
        return true;
    }

    /**
     * @param boolean $internalErrors
     * @return array
     */
    protected static function getXmlErrors($internalErrors)
    {
        $errors = [];

        foreach (libxml_get_errors() as $error) {
            $errors[] = sprintf('[%s %s] %s',
                LIBXML_ERR_WARNING == $error->level ? 'WARNING' : 'ERROR',
                $error->code,
                trim($error->message)
            );
        }

        libxml_clear_errors();
        libxml_use_internal_errors(true);
  
        return $errors;
    }
}