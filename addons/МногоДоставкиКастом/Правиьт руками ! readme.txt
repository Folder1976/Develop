Thanks for using it. 

Installation:
---------------
Installation file name is  "xshipping.ocmod.zip".  It is available inside respective directory for your opencart version.

1. Please Go to Extensions -> Extension Installer
2. Upload "xshipping.ocmod.zip" and click on continue.
3. Now install from admin -> Extensions -> Filter by Shipping Module -> X-Shipping



Support:
---------------
Please send comments in the module page of the opencart.com

Or you can send email to opencartmart@gmail.com for quick response.


Заменить в
/catalog/controller/checkout/onepagecheckout.php

После - foreach ($method_data as $i => $shipping_method)

на 
foreach ($shipping_method['quote'] as $shipping_method2) {
    
    $code = explode('.',$shipping_method2['code']);
    
    $data['shippig_methods'][$code[1]]['value'] = $shipping_method2['code'];
    $data['shippig_methods'][$code[1]]['title'] = $shipping_method2['title'];
    if (isset($shipping_method2['cost']))
        $data['shippig_methods'][$code[1]]['cost'] = $shipping_method2['cost'];
    else
        $data['shippig_methods'][$code[1]]['cost']='';

        
}