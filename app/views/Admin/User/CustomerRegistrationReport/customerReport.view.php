<?php
// Load the XML document
$xml = new DOMDocument();
$xml->load(__DIR__ . '/customers.xml'); // Load the generated XML

// Load the XSLT stylesheet
$xsl = new DOMDocument();
$xsl->load(__DIR__ . '/customerReport.xsl'); // Load the XSLT file

// Configure the XSLT processor
$processor = new XSLTProcessor();
$processor->importStylesheet($xsl); // Import the XSLT stylesheet

// Transform XML into HTML and display the result
$html = $processor->transformToXML($xml);
echo $html;
