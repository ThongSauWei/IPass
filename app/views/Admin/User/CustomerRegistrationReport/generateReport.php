p
Copy code
<?php
// Load the XML document
$xml = new DOMDocument();
$xml->load(__DIR__ . '/CustomerRegistrationReport/customers.xml'); // Load the XML data

// Load the XSLT stylesheet
$xsl = new DOMDocument();
$xsl->load(__DIR__ . '/CustomerRegistrationReport/customerReport.xsl'); // Load the XSLT file

// Configure the XSLT processor
$processor = new XSLTProcessor();
$processor->importStylesheet($xsl); // Import the XSLT stylesheet

// Transform XML into HTML and display the result
$html = $processor->transformToXML($xml);
echo $html;
?>