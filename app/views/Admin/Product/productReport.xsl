<?xml version="1.0" encoding="UTF-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
    <xsl:output method="html" encoding="UTF-8" />
    <xsl:key name="categories" match="Product" use="Category" />

    <xsl:template match="/">
        <html>
            <head>
                <title>NSK Grocery Products Available Report</title>
                <style>
                    table { width: 100%; border-collapse: collapse; }
                    th, td { border: 1px solid black; padding: 8px; text-align: left; }s
                    th { background-color: #f2f2f2; }
                </style>
            </head>
            <body>
                <h1>Available Products Report</h1>
                <p><strong>Report Generated On:</strong> <xsl:value-of select="$currentDateTime" /></p>

                <table>
                    <tr>
                        <th>Product ID</th>
                        <th>Product Name</th>
                        <th>Category</th>
                        <th>Price</th>
                        <th>Weight</th>
                    </tr>

                    <!-- Filter available products -->
                    <xsl:variable name="availableProducts" select="Report/Product[Availability='1']" />

                    <!-- Iterate through available products -->
                    <xsl:for-each select="$availableProducts">
                        <tr>
                            <td><xsl:value-of select="ProductID" /></td>
                            <td><xsl:value-of select="ProductName" /></td>
                            <td><xsl:value-of select="Category" /></td>
                            <td><xsl:value-of select="Price" /></td>
                            <td><xsl:value-of select="Weight" /></td>
                        </tr>
                    </xsl:for-each>

                    <!-- Total products count -->
                    <tr>
                        <td colspan="5">
                            <strong>Total Products Found:</strong> <xsl:value-of select="count($availableProducts)" />
                        </td>
                    </tr>

                    <!-- Unique categories and total count -->
                    <tr>
                        <td colspan="5">
                            <strong>Total Categories Found:</strong>
                            <xsl:value-of select="count(Report/Product[Availability='1'][generate-id() = generate-id(key('categories', Category)[1])])" />
                            <br />
                            <strong>Categories:</strong>
                            <xsl:for-each select="Report/Product[Availability='1'][generate-id() = generate-id(key('categories', Category)[1])]">
                                <xsl:if test="position() > 1">
                                    <xsl:text>, </xsl:text>
                                </xsl:if>
                                <xsl:value-of select="Category" />
                            </xsl:for-each>
                        </td>
                    </tr>

                    <!-- Call the template to calculate highest/lowest prices -->
                    <xsl:call-template name="calculate-prices">
                        <xsl:with-param name="products" select="$availableProducts" />
                        <xsl:with-param name="current-highest" select="0" />
                        <xsl:with-param name="current-lowest" select="999999" />
                        <xsl:with-param name="highest-product" select="''" />
                        <xsl:with-param name="lowest-product" select="''" />
                    </xsl:call-template>
                </table>
            </body>
        </html>
    </xsl:template>

    <!-- Calculate highest and lowest prices template -->
    <xsl:template name="calculate-prices">
        <xsl:param name="products" />
        <xsl:param name="current-highest" />
        <xsl:param name="current-lowest" />
        <xsl:param name="highest-product" />
        <xsl:param name="lowest-product" />

        <xsl:choose>
            <xsl:when test="count($products) = 0">
                <tr>
                    <td colspan="5">
                        <strong>Highest Price:</strong> <xsl:value-of select="$current-highest" />
                        <strong> (Product: </strong><xsl:value-of select="$highest-product" /><strong>)</strong>
                    </td>
                </tr>
                <tr>
                    <td colspan="5">
                        <strong>Lowest Price:</strong> <xsl:value-of select="$current-lowest" />
                        <strong> (Product: </strong><xsl:value-of select="$lowest-product" /><strong>)</strong>
                    </td>
                </tr>
            </xsl:when>
            <xsl:otherwise>
                <xsl:variable name="first" select="$products[1]" />
                <xsl:variable name="price" select="number($first/Price)" />
                <xsl:variable name="name" select="$first/ProductName" />

                <xsl:choose>
                    <xsl:when test="$price &gt; $current-highest">
                        <xsl:call-template name="calculate-prices">
                            <xsl:with-param name="products" select="$products[position() > 1]" />
                            <xsl:with-param name="current-highest" select="$price" />
                            <xsl:with-param name="current-lowest" select="$current-lowest" />
                            <xsl:with-param name="highest-product" select="$name" />
                            <xsl:with-param name="lowest-product" select="$lowest-product" />
                        </xsl:call-template>
                    </xsl:when>
                    <xsl:when test="$price &lt; $current-lowest">
                        <xsl:call-template name="calculate-prices">
                            <xsl:with-param name="products" select="$products[position() > 1]" />
                            <xsl:with-param name="current-highest" select="$current-highest" />
                            <xsl:with-param name="current-lowest" select="$price" />
                            <xsl:with-param name="highest-product" select="$highest-product" />
                            <xsl:with-param name="lowest-product" select="$name" />
                        </xsl:call-template>
                    </xsl:when>
                    <xsl:otherwise>
                        <xsl:call-template name="calculate-prices">
                            <xsl:with-param name="products" select="$products[position() > 1]" />
                            <xsl:with-param name="current-highest" select="$current-highest" />
                            <xsl:with-param name="current-lowest" select="$current-lowest" />
                            <xsl:with-param name="highest-product" select="$highest-product" />
                            <xsl:with-param name="lowest-product" select="$lowest-product" />
                        </xsl:call-template>
                    </xsl:otherwise>
                </xsl:choose>
            </xsl:otherwise>
        </xsl:choose>
    </xsl:template>
</xsl:stylesheet>
