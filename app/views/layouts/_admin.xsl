<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:output method="xml" media-type="application/xhtml+xml" encoding="utf-8" indent="yes" version="1.0" omit-xml-declaration="yes" doctype-system="http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd" doctype-public="-//W3C//DTD XHTML 1.0 Strict//EN"/>

<xsl:param name="layout"/> 
<xsl:param name="action"/> 
<xsl:param name="path"/> 

<xsl:variable name="a_path"><xsl:value-of select="$path"/>4600da0df39030a225fbc4098d48f004/</xsl:variable>
<xsl:variable name="c_path"><xsl:value-of select="$path"/>pub/c/admin/</xsl:variable>
<xsl:variable name="i_path"><xsl:value-of select="$path"/>pub/i/admin/</xsl:variable>
<xsl:variable name="j_path"><xsl:value-of select="$path"/>pub/j/</xsl:variable>


<xsl:template match="root">

<html xml:lang="en" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
<head>
	<meta http-equiv="Content-type" content="application/xml+xhtml; charset=utf-8"/>
	<meta http-equiv="Content-Language" content="de-de"/>
	
	<title>Administration</title>

	<meta name="ROBOTS" content="ALL" />
	<meta http-equiv="imagetoolbar" content="no" />
	<meta name="MSSmartTagsPreventParsing" content="true" />
	<meta name="Copyright" content="(c) 2005 Copyright content:  Copyright design: Danilo Braband" />
	<!-- (c) Copyright 2005 by Danilo Braband All Rights Reserved. -->

	<meta name="Keywords" content="__KEYWORDS__" />
	<meta name="Description" content="__DESCRIPTION__" />

	<link href="{$c_path}master.css" rel="stylesheet" type="text/css" media="all"/>

	<script src="{$j_path}prototype.js" type="text/javascript"></script>
	<script src="{$j_path}admin.js" type="text/javascript"></script>

</head>

<body id="{$action}">

	<div id="spinner">&#160;</div>	

	<div id="header">Administration</div>

	<div id="navigation">
		<ul>
			<li><a href="{$a_path}" title="Content">Content</a></li>
			<li>Statistics
				<ul>
				<li><a title="Visits">Visits</a></li>
				<li><a title="Referrers">Referrers</a></li>
				<li><a title="Pages">Pages</a></li>
				<li><a title="Searches">Searches</a></li>
				</ul>
			</li>
			<li>Settings
				<ul>
				<li><a title="Authors">Authors</a></li>
				<li><a title="Preferences">Preferences</a></li>
				</ul>
			</li>
		</ul>
	</div>

	<div id="content"><xsl:call-template name="content"/></div>

	<div id="subnav">subnav</div>

	<div id="footer">footer</div>

</body>

</html>

</xsl:template>


</xsl:stylesheet>
