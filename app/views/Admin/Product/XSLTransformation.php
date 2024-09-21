<?php

/*
 * Click nbfs://nbhost/SystemFileSystem/Templates/Licenses/license-default.txt to change this license
 * Click nbfs://nbhost/SystemFileSystem/Templates/Scripting/PHPClass.php to edit this template
 */

/**
 * Description of XMLTransform
 *
 * @author Acer
 */
class XSLTransformation {
    public function transformWithXsl($xmlFilePath, $xslFilePath, $currentDateTime) {
        $xml = new DOMDocument();
        $xsl = new DOMDocument();

        // Load XML and XSL files
        if (!$xml->load($xmlFilePath)) {
            return "Error loading XML file.";
        }
        if (!$xsl->load($xslFilePath)) {
            return "Error loading XSL file.";
        }

        // Initialize XSLT processor
        $proc = new XSLTProcessor();
        $proc->importStyleSheet($xsl);

        // Pass the current date and time as a parameter
        $proc->setParameter('', 'currentDateTime', $currentDateTime);

        // Perform the transformation
        return $proc->transformToXml($xml);
    }
}
