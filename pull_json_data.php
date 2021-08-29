<?php

require_once('ClerkBrowser.php');

$cb = new ClerkBrowser();

$content = $cb->get('https://login.churchofjesuschrist.org/?service=200&goto=https%3A%2F%2Fwww.churchofjesuschrist.org%2F%3Flang%3Deng');
$stateKey = substr($content, strpos($content, 'initResponse":{"authState":"')+strlen('initResponse":{"authState":"'));
$stateKey = substr($stateKey, 0, strpos($stateKey,'","output"'));
sleep(rand(4,16));

$ajax_headers = array();
$ajax_headers[] = "Accept: application/json";
$ajax_headers[] = "Accept-Encoding: gzip, deflate, br";
$ajax_headers[] = "Accept-Language: en-US,en;q=0.5";
$ajax_headers[] = "Api-Lang3: eng";
$ajax_headers[] = "Cache-Control: no-cache";
$ajax_headers[] = "Connection: keep-alive";
$ajax_headers[] = "Content-Length: 4614";
$ajax_headers[] = "Content-Type: application/json";
$ajax_headers[] = "Host: login.churchofjesuschrist.org";
$ajax_headers[] = "Origin: https://login.churchofjesuschrist.org";
$ajax_headers[] = "Pragma: no-cache";
$ajax_headers[] = "Referer: https://login.churchofjesuschrist.org/?service=200&goto=https%3A%2F%2Fwww.churchofjesuschrist.org%2F%3Flang%3Deng";
$ajax_headers[] = "User-Agent: Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:74.0) Gecko/20100101 Firefox/74.0";
$content = $cb->post('https://login.churchofjesuschrist.org/api/authenticate/credentials', array('authState'=>$stateKey,'username'=>'davestlyon', 'password'=>''), 'default', true, $ajax_headers);
var_dump($content);

{"headers":[{"name":"Accept","value":"application/json, text/plain, */*"},{"name":"Accept-Encoding","value":"gzip, deflate, br"},{"name":"Accept-Language","value":"en-US,en;q=0.5"},{"name":"Cache-Control","value":"no-cache"},{"name":"Connection","value":"keep-alive"},{"name":"Cookie","value":"mbox=PC#31005789e2404186a1003153a2494bfc.28_0#1647229917|session#e74927de0b434eebb94f7a0eb1d05184#1583986983; utag_main=v_id:016ec654141700213e869720d1440004c002d00901788$_sn:48$_ss:0$_st:1583986928176$vapi_domain:churchofjesuschrist.org$dc_visit:48$ses_id:1583982951148%3Bexp-session$_pn:11%3Bexp-session$dc_event:15%3Bexp-session$dc_region:us-east-1%3Bexp-session; audience_split=33; t_ppv=churchofjesuschrist.org%20%3A%20lcr%20%3A%20%2C100%2C83%2C1452%2C15352; AMCV_66C5485451E56AAE0A490D45%40AdobeOrg=1099438348%7CMCIDTS%7C18329%7CMCMID%7C33828997040904273924618454418695691597%7CMCAID%7C2E4C22B505036D22-6000119A00000FE4%7CMCOPTOUT-1583992321s%7CNONE%7CMCAAMLH-1584589921%7C9%7CMCAAMB-1584589921%7Cj8Odv6LonN4r3an7LhD3WZrU1bUpAkFkkiY1ncBR96t2PTI%7CMCSYNCS%7C1083-18336*1085-18336*1086-18336*1087-18336*1088-18336*19913-18336*83349-18336*411-18336%7CMCSYNCSOP%7C411-18336%7CvVersion%7C2.1.0; _fbp=fb.1.1575551699320.392125792; __CT_Data=gpv=53&ckp=tld&dm=churchofjesuschrist.org&apv_59_www11=54&cpv_59_www11=53&rpv_59_www11=52; ctm={'pgv':8444261560601082|'vst':4284346417863438|'vstr':8928337141457853|'intr':1582475161900|'v':1|'lvst':503}; WRUIDCD=2547405615759638; _cs_c=1; _cs_ex=1; _ga=GA1.2.605618691.1581177362; _CT_RS_=EventTriggeredRecording; _fbc=fb.1.1581866980858.IwAR2vBGenihWpbzH9jGDUdtgJjVKNKkAs6I_gmaRHrdZTAkK1Z3uCcUlJbik; cr-aths=shown; RT=\"z=1&dm=churchofjesuschrist.org&si=4ab542af-6638-48b9-a9fb-6520fee2ac82&ss=k7o6zygn&sl=8&tt=f84&bcn=%2F%2F173e2529.akstat.io%2F&ld=ucbh&nu=08b89d78d40e90b8b126f7a10960d4e4&cl=uds9&ul=udsl&hd=uefd\"; JSESSIONID=0; __VCAP_ID__=2f8bc50a-0d8c-49e4-6e86-0663; audience_s_split=25; AMCVS_66C5485451E56AAE0A490D45%40AdobeOrg=1; s_cc=true; s_sq=ldsall%3D%2526pid%253Dchurchofjesuschrist.org%252520%25253A%252520lcr%252520%25253A%252520%2526pidt%253D1%2526oid%253DMembers%2525C2%2525A0%2526oidt%253D3%2526ot%253DSUBMIT; s_ppvl=https%253A%2F%2Fwww.churchofjesuschrist.org%2Fletters%253Fclang%253Deng%2526lang%253Deng%2526p%253D2%2526source%253Dall%2C42%2C96%2C3410%2C2175%2C1472%2C3840%2C2160%2C1%2CL; s_ppv=https%253A%2F%2Fwww.churchofjesuschrist.org%2Fletters%253Fclang%253Deng%2526lang%253Deng%2526p%253D3%2526source%253Dall%2C42%2C42%2C1472%2C2175%2C1472%2C3840%2C2160%2C1%2CL; ADRUM=s=1583985119384&r=https%3A%2F%2Fwww.churchofjesuschrist.org%2F%3F479231918; TS01a096ec=01999b7023128837c15922c0ac6ff66e8134876060e5c4535b30b0966f6468a3eb0fd664e5876a75951c49c8fbbfb0566056dd207f; TS011e50d7=01999b702336b90e7a27ea05e219296efe750f92bd038bacaa5282031880438b2255baefdee84dbe8863de8edc302a077b97590a46; s_campaign=email-OCL_17320; TS01b07831=01999b70232a8edf47e3e3900240b4b3f0c1be99c7c2c3fc27f619a5937974330cecae5fd707ee6a9f9d604877780dde3171484dc9; check=true; mboxEdgeCluster=28; ChurchSSO=d7E8M6z5LWW4zqBCIae0uSOUtfY.*AAJTSQACMDIAAlNLABx6MnRLRy9DdFBTMVBPbHkyTlM2OW1ycDBxY2c9AAR0eXBlAANDVFMAAlMxAAIwMQ..*; Church-auth-jwt-prod=eyJ0eXAiOiJKV1QiLCJraWQiOiJDK2g4T1diR0IrMnV0L0xQQ0RlTEUwMXAzUjQ9IiwiYWxnIjoiUlMyNTYifQ.eyJzdWIiOiJkYXZlc3RseW9uIiwiYXVkaXRUcmFja2luZ0lkIjoiYmNhNTYzM2UtMzM2ZC00NzBiLTk3ODUtMDI5YmExNGU3YjFiLTIxMTMxMTYwNiIsImlzcyI6Im51bGw6Ly9pZGVudC1wcm9kLmNodXJjaG9mamVzdXNjaHJpc3Qub3JnOjQ0My9zc28vb2F1dGgyIiwidG9rZW5OYW1lIjoiaWRfdG9rZW4iLCJub25jZSI6IjMyMUM1OEMwMkY2ODJBNjQ1REY5RkREQzlDMkRBNjY5IiwiYXVkIjoibDE4MzgxIiwiYWNyIjoiMCIsImF6cCI6ImwxODM4MSIsImF1dGhfdGltZSI6MTU4Mzk4NTExNCwiZm9yZ2Vyb2NrIjp7InNzb3Rva2VuIjoiZDdFOE02ejVMV1c0enFCQ0lhZTB1U09VdGZZLipBQUpUU1FBQ01ESUFBbE5MQUJ4Nk1uUkxSeTlEZEZCVE1WQlBiSGt5VGxNMk9XMXljREJ4WTJjOUFBUjBlWEJsQUFORFZGTUFBbE14QUFJd01RLi4qIiwic3VpZCI6ImJjYTU2MzNlLTMzNmQtNDcwYi05Nzg1LTAyOWJhMTRlN2IxYi0yMTEzMDk2OTkifSwicmVhbG0iOiIvY2h1cmNoIiwiZXhwIjoxNTg0MDI4MzE1LCJ0b2tlblR5cGUiOiJKV1RUb2tlbiIsImlhdCI6MTU4Mzk4NTExNSwiYWdlbnRfcmVhbG0iOiIvY2h1cmNoIn0.hvmXKktQFpGM_6xhYEOS5KQTdMF4GWDE_9SMmbNf_KVLflw2z51ChAM8W0HySdd2rC09dHG6Ayh-UBzSqasmGaZFtLRyavOy5aRy3ZQlKiBQE4xae0kI_uR6CU1nO8qt3p_SUPivMIuKzloffBB6RqnQUWbV2dN6VFAXHcC9NVRuF5u1Nhk9I5hubhY_a35EjsRWEtCYMlzSjRE11snl1-9U6Xugyz3AkWD3RZT5_o35dlRmGwpdqDflt_KhNm0JFvEtxQLRoJccX1os1eZwXPltveAYv_Sc385OdyS8h-1vuXqOre9gxr4reJu1Knj_eMJMo2Zhbu21CRSawRFbOQ; ADRUM_BTa=R:54|g:75808d85-6006-41a2-b36b-8ecfa1cddf7b|n:customer1_acb14d98-cf8b-4f6d-8860-1c1af7831070; ADRUM_BT1=R:54|i:14049|e:204"},{"name":"Host","value":"lcr.churchofjesuschrist.org"},{"name":"Pragma","value":"no-cache"},{"name":"Referer","value":"https://lcr.churchofjesuschrist.org/messaging?lang=eng"},{"name":"TE","value":"Trailers"},{"name":"User-Agent","value":"Mozilla/5.0 (X11; Ubuntu; Linux x86_64; rv:74.0) Gecko/20100101 Firefox/74.0"}]}}

$content = $cb->get('https://lcr.churchofjesuschrist.org/services/leader-messaging/get-group-members/2118580/-28?lang=eng');
var_dump($content);
