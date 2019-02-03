Extension: Downloadable Files 1.3
Released: May 16, 2018
Author: Volodymyr Chornovol
Site: https://helpforsite.com
E-mail: support@helpforsite.com
___________________________________
ABOUT EXTENSION

Downloadable Files is an OCMOD extension. It creates a downloads list in the tab "Documentation" on the product page. Besides, you can use external links in the filename field in the download form.

Features:

support English, Russian and Ukrainian
support seopro
support Journal2 (mb it works with another templates)
support external links in the download form and in the downloads list
filename 128-character limit increased up to 255 (for another table prefix than 'oc_' you must edit install.sql)
files will be open in browser if its possible

Extension work with OpenCart 2.x and 3.x.
________________________________________

HOW TO INSTALL

At first, go to admin area of your site.

Step 1: Enable FTP in System -> Settings
Step 2: Upload extension (zip file) in Extensions -> Extension installer

OR

Step 1: Rename install.xml to install.ocmod.xml and upload this one in Extensions -> Extension installer
Step 2: Upload install.sql in Tools -> Backup/Restore and press restore button or import this file via phpmyadmin
_________________________________________

HOW TO ADD A LANGUAGE SUPPORT

Edit this block in the install.xml

  <file path="catalog/language/*/product/product.php">
	<operation>
	  <search index="0"><![CDATA[$_['tab_description']]]></search>
	  <add position="before"><![CDATA[$_['tab_documentation'] = 'Documentation';]]></add>
	</operation>
  </file> 

* - your language catalog
'Documentation' - tab name  
________________________________________
CHANGELOG 
Version 1.3
+ compatibility with Opemcart 3.x

Version 1.2.6
+ some little fixes of markers

Version 1.2.5
+ some little fixes of markers
 
Version 1.2.4
- Russian and Ukrainian was dropped. English only
+ links and files will be open in new window

Version 1.2.3
+ the deprecated function mime_content_type() replaced to finfo::file

Version 1.2.2
+ files will be open in browser if its possible

Version 1.2.1
+ filename 128-character limit was increased up to 255

Version 1.2
+ support external links in the download form and in the downloads list
+ icons

Version 1.1
+ support Journal2 (mb it works with another templates)

Version 1.0
+ downloads list in the tab "Documentation" on the product page
+ support English, Russian and Ukrainian
+ support seopro


DONATE

If you like my free extensions and you'd like to see new versions, you can to buy me a cup of coffee (or shot of whiskey ^^).
Skrill: vladyka1989@gmail.com
PerfectMoney: U5279439
OKPAY: OK679368672

Also I'll be very thankful for fixing some mistakes in my texts. I've just started to study English. Write me to support@helpforsite.com please. 
	
	
	
	
