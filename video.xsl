<?xml version="1.0" encoding="utf-8"?>
<xsl:stylesheet version="1.0" xmlns:xsl="http://www.w3.org/1999/XSL/Transform">
	<xsl:output method="html" version="1.0" encoding="UTF-8" indent="yes"/>
	<xsl:template match="/">
		<html>
			<body>
				<xsl:for-each select="list/Site">
					<table width="100%" border="0" cellspacing="1" cellpadding="1"
						bgcolor="#B0C4DE" bordercolordark="#FFFFFF" id="siteList" class="siteList">
						<tr height="20" bgcolor="#eeeeee" class="listTrTitle">
							<th align="center" class="text" >URL</th>
							<th align="center" class="text" >Common Name</th>
							<th align="center" class="text" >Reliability</th>
						</tr>
						<tr height="20" bgcolor="#FFFFFF">
							<td class="text" align="center">
								<xsl:value-of select="url" />
							</td>
							<td class="text" align="center">
								<xsl:value-of select="name" />
							</td>
							<td class="text" align="center">
								<xsl:value-of select="reliability" />
							</td>
						</tr>
					</table>
					<table width="100%" border="0" cellspacing="1" cellpadding="1"
						bgcolor="#B0C4DE" bordercolordark="#FFFFFF" id="videoList" class="videoList">
						<tr height="20" bgcolor="#eeeeee" class="listTrTitle">
							<th align="center" class="text" >Title</th>
							<th align="center" class="text" >Runtime</th>
							<th align="center" class="text" >Format</th>
						</tr>
						<xsl:for-each select="videos/video">
							<xsl:element name="tr">
								<xsl:attribute name="id">
									<xsl:text>video</xsl:text>
								</xsl:attribute>
								<xsl:attribute name="height">
									<xsl:text>20</xsl:text>
								</xsl:attribute>
								<xsl:attribute name="bgcolor">
									<xsl:text>#FFFFFF</xsl:text>
								</xsl:attribute>

								<td class="text" align="center">
									<xsl:value-of select="title" />
								</td>
								<td class="text" align="center">
									<xsl:value-of select="runtime" />
								</td>
								<td class="text" align="center">
									<xsl:value-of select="format" />
								</td>
							</xsl:element>
						</xsl:for-each>
					</table>
				</xsl:for-each>
			</body>
		</html>
	</xsl:template>
</xsl:stylesheet>