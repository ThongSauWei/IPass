<?php
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
