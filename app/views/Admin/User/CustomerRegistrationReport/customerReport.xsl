<?xml version="1.0" encoding="UTF-8"?>

<!--
    Document   : customerReport.xsl
    Created on : September 21, 2024, 1:55 AM
    Author     : erika
    Description:
        Purpose of transformation follows.
-->
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" encoding="UTF-8" />

    <xsl:template match="/">
        <html>
            <head>
                <title>Customer Registration Report</title>
                <style>
                    table { width: 100%; border-collapse: collapse; }
                    th, td { border: 1px solid black; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; }
                    
                    /* Styling for the print button */
                    #printButton {
                        position: absolute;
                        top: 20px;
                        right: 20px;
                        background-color: #36b9cc;
                        color: white;
                        padding: 10px 20px;
                        border: none;
                        cursor: pointer;
                        font-size: 16px;
                    }

                    #printButton:hover {
                        background-color: #4e73df;
                    }
                </style>
                <script>
                    function printReport() {
                        window.print(); // Trigger the print dialog
                    }
                </script>
            </head>
            <body>
                <!-- Print Button -->
                <button id="printButton" onclick="printReport()">Print Report</button>
                <h1>Customer Registration Report</h1>
                <p><strong>Report Generated On:</strong> <xsl:value-of select="$currentDateTime" /></p>

                <table>
                    <tr>
                        <th>User ID</th>
                        <th>Customer ID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Phone</th>
                        <th>Registration Date</th>
                    </tr>

                    <xsl:for-each select="customers/customer">
                        <tr>
                            <td><xsl:value-of select="UserID" /></td>
                            <td><xsl:value-of select="CustomerID" /></td>
                            <td><xsl:value-of select="Username" /></td>
                            <td><xsl:value-of select="Email" /></td>
                            <td><xsl:value-of select="Gender" /></td>
                            <td><xsl:value-of select="Phone" /></td>
                            <td><xsl:value-of select="RegistrationDate" /></td>
                        </tr>
                    </xsl:for-each>

                    <tr>
                        <td colspan="7">
                            <strong>Total Customers Registered:</strong>
                            <xsl:value-of select="count(customers/customer)" />
                        </td>
                    </tr>
                </table>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>