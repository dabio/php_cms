<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:output method="xml" media-type="text/xml" encoding="UTF-8" indent="yes" version="1.0" omit-xml-declaration="yes"/>

<xsl:param name="layout"/> 
<xsl:param name="action"/> 
<xsl:param name="path"/> 

<xsl:variable name="a_path"><xsl:value-of select="$path"/>4600da0df39030a225fbc4098d48f004/</xsl:variable>
<xsl:variable name="c_path"><xsl:value-of select="$path"/>pub/c/admin/</xsl:variable>
<xsl:variable name="i_path"><xsl:value-of select="$path"/>pub/i/admin/</xsl:variable>
<xsl:variable name="j_path"><xsl:value-of select="$path"/>pub/j/</xsl:variable>



<xsl:template match="root/preview">

    <xsl:value-of disable-output-escaping="yes" select="content"/>

</xsl:template>



<xsl:template match="root/edit/item">

	<form id="page_content" method="post" action="{$a_path}content/save/">
		<fieldset>

			<legend id="legend"><xsl:value-of select="title"/>&#160;</legend>

			<input type="hidden" id="f_id" name="id" value="{id}"/>
			<input type="hidden" id="f_url" name="url" value="{url}"/>
			<input type="hidden" id="f_parent" name="parent" value="{parent}"/>

			<label for="f_title">Title</label>
			<input onkeyup="urlUpdate()" onblur="urlUpdate()" type="text" id="f_title" name="title" value="{title}"/>

            <div id="f_toolbar"><a title="Preview" onclick="hidePreview()">Edit</a> | <a title="Preview" onclick="showPreview()">Preview</a></div>
			<label for="f_content">Content</label>
            <textarea id="f_content" name="content" rows="10"><xsl:value-of select="content"/>&#160;</textarea>
			<div id="f_preview">&#160;</div>

			<xsl:call-template name="markup">
				<xsl:with-param name="f_markup" select="f_markup"/>
			</xsl:call-template>

        	<div id="buttons">
        		<input class="btn" type="image" alt="Save" name="submit" value="save" src="{$i_path}btn_save.png"/>
        		<input class="btn" type="image" alt="Cancel" name="submit" value="cancel" src="{$i_path}btn_cancel.png"/>
        		<input class="btn" type="image" alt="Delete" name="submit" value="delete" src="{$i_path}btn_delete.png"/>
        	</div>

		</fieldset>
	</form>

</xsl:template>



<xsl:template name="markup">

	<xsl:param name="f_markup"/>

	<select id="f_markup" name="f_markup" size="1" onchange="changeMarkup()">

		<option value="1">Text Formatting:</option>

		<xsl:for-each select="//root/markup/item">

			<option value="{id}">
				<xsl:if test="$f_markup = id">
					<xsl:attribute name="selected">selected</xsl:attribute>
				</xsl:if>
				<xsl:value-of select="name"/>
			</option>
		
		</xsl:for-each>

	</select>

</xsl:template>


<xsl:template match="root/save"/>
<xsl:template match="root/markup"/>

</xsl:stylesheet>
