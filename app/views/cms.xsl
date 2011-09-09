<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:output method="xml" media-type="text/xml" encoding="UTF-8" indent="yes" version="1.0" omit-xml-declaration="yes" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd"/>

<xsl:param name="path"/>

<xsl:variable name="id" select="root/item/id"/>
<xsl:variable name="parent" select="root/item/parent"/>

<xsl:template match="root">

<html xml:lang="de" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<head>
	<meta http-equiv="Content-type" content="text/html; charset=utf-8"/>
	<meta http-equiv="Content-Language" content="de-de"/>
	
	<title>Monoseat: <xsl:value-of select="item/title"/></title>

	<meta name="ROBOTS" content="ALL" />
	<meta http-equiv="imagetoolbar" content="no" />
	<meta name="MSSmartTagsPreventParsing" content="true" />
	<meta name="Copyright" content="(c) 2005 Copyright content:  Copyright design: Danilo Braband" />
	<!-- (c) Copyright 2005 by Danilo Braband All Rights Reserved. -->

	<meta name="Keywords" content="__KEYWORDS__" />
	<meta name="Description" content="__DESCRIPTION__" />

    <script type="text/javascript" src="{$path}pub/j/record.js.php"></script>
    <script type="text/javascript" src="{$path}pub/j/basics.js"></script>

	<link href="{$path}pub/c/master.css" rel="stylesheet" type="text/css" media="all" />

</head>
<body>

    <div id="container">

	<div id="header"><a href="{$path}" title="monoseat.de"><img src="{$path}pub/i/monoseat.gif" alt="monoseat.de"/></a></div>

	<div id="main_nav"><ul><xsl:apply-templates select="//root/tree/item[parent='1']"/></ul><div class="clearer"><span></span></div></div>

	<xsl:if test="$parent!='0'">

        <xsl:variable name="param">
            <xsl:choose>

                <xsl:when test="(//root/tree/item/parent=$id and $parent=1)">
                    <xsl:value-of select="$id"/>
                </xsl:when>

                <xsl:when test="(//root/tree/item/id=$parent and $parent!=1)">
                    <xsl:value-of select="$parent"/>
                </xsl:when>

                <xsl:otherwise>0</xsl:otherwise>

            </xsl:choose>
        </xsl:variable>

        <xsl:if test="$param!='0'">

            <div id="local_nav"><ul><xsl:apply-templates select="//root/tree/item[parent=$param]"/></ul><div class="clearer"><span></span></div></div>

        </xsl:if>
    </xsl:if>

    <div id="content"><xsl:value-of disable-output-escaping="yes" select="item/content"/></div>
    
    <div id="map"><xsl:call-template name="subnav"/></div>
    
    <div id="footer"><a href="/" class="copy">&#169; 2006 Danilo Braband, http://monoseat.de</a></div>

    </div>

</body>

</html>

</xsl:template>


<!--
	Das Template für die Subnavigationsleiste
	-->
<xsl:template name="subnav">

	<ul>
	<xsl:for-each select="path2node/item"><li><a href="{$path}"><xsl:if test="parent != '0'"><xsl:attribute name="href"><xsl:value-of select="$path"/><xsl:value-of select="url"/>/</xsl:attribute></xsl:if><xsl:value-of select="title"/></a>&#160;&#8250;&#160;</li></xsl:for-each><li><xsl:value-of select="//root/item/title"/></li>
	</ul>

</xsl:template>



<xsl:template match="tree/item">
		<li>
		    <a href="{$path}{url}/" title="{title}">
		        <xsl:if test="id=$id or id=$parent">
		            <xsl:attribute name="class">active</xsl:attribute>
		        </xsl:if>
		        <xsl:value-of select="title"/>
		    </a>
		</li>
</xsl:template>

</xsl:stylesheet>