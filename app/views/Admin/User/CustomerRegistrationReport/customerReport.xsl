<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">
<!--
    Document   : customerReport.xsl
    Created on : September 20, 2024, 7:13 PM
    Author     : erika
    Description:
        Purpose of transformation follows.
-->

    <xsl:output method="html" encoding="UTF-8" indent="yes"/>
    <xsl:template match="/">
        <html>
            <head>
                <title>Customer Registration Report</title>
                <style>
                    body { font-family: Arial, sans-serif; }
                    table { width: 100%; border-collapse: collapse; }
                    th, td { padding: 10px; border: 1px solid #000; text-align: left; }
                    th { background-color: #f2f2f2; }
                </style>
            </head>
            <body>
                <h1>Customer Registration Report</h1>
                <table>
                    <tr>
                        <th>UserID</th>
                        <th>CustomerID</th>
                        <th>Username</th>
                        <th>Email</th>
                        <th>Gender</th>
                        <th>Phone</th>
                        <th>Registration Date</th>
                    </tr>
                    <xsl:for-each select="customers/customer">
                        <tr>
                            <td><xsl:value-of select="UserID"/></td>
                            <td><xsl:value-of select="CustomerID"/></td>
                            <td><xsl:value-of select="Username"/></td>
                            <td><xsl:value-of select="Email"/></td>
                            <td><xsl:value-of select="Gender"/></td>
                            <td><xsl:value-of select="Phone"/></td>
                            <td><xsl:value-of select="RegistrationDate"/></td>
                        </tr>
                    </xsl:for-each>
                </table>
            </body>
        </html>
    </xsl:template>
</xsl:stylesheet>
