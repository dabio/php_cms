<?xml version="1.0" encoding="UTF-8" ?>
<xsl:stylesheet xmlns:xsl="http://www.w3.org/1999/XSL/Transform" version="1.0">

<xsl:include href="layouts/_admin.xsl"/>

<xsl:key name="rowstruct" match="item" use="parent"/>



<xsl:template name="content">

    <table cellspacing="0">
        <tr>

            <td id="tree">
                <!-- must put that into a single container, because safari and
                    ff handle the width of td-elements different -->
                <div style="padding-left:50px">
                <!-- start with the root  -->
                <xsl:apply-templates select="//root/tree/item[parent='0']"/>
                </div>
            </td>

            <td id="item">

            </td>
        </tr>

    </table>

</xsl:template>



<xsl:template match="item">
	<ul>
		<li>
			<xsl:if test="//root/tree/item/parent = id">
				<xsl:attribute name="class">plus</xsl:attribute>
			</xsl:if>
			<div class="leaf">
			    <a onclick="editContent({id})" title="Edit Page">
				    <xsl:value-of select="title"/>
			    </a>
			    <span class="more">
				    <a onclick="addContent({id})" title="Add Page">+</a>
				    <a title="More Information">i</a>
				    <a onclick="editContent({id})" title="Edit Page">e</a>
			    </span>
		    </div>
		<xsl:apply-templates select="key('rowstruct', id)"/>
		</li>
	</ul>
</xsl:template>

</xsl:stylesheet>
