<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2017112700193310",

		//商户私钥，您的原始格式RSA私钥
		'merchant_private_key' => "MIIEvAIBADANBgkqhkiG9w0BAQEFAASCBKYwggSiAgEAAoIBAQC0jVTJ4IkKZF+tytEpvGxxk4LCdAynK0zegqc+C4Fuqgf3/s86nsSdI6K08zQST5WUstbHu/+P7DkLVPC1sxtUlAq7KVz1JFg+h3WZcHWdb5oEUKQb0rqO5r6Zc6hrl/Zd0e7Ge2kggIOKeVfrGSQTJrmo14kZNBK+JxW5NGFHjanjZvf4D0+SCNz2srqC2EqAcFpZb2ZzrTlJctQQY7KrPY+Nk+ryxlxgoQGd5G+DpLiXrIX0kLYSh3YyoOSLmuEy7oVNftxOzFS5irVyjo9zhrSN9FtiT/7pcdB/KJlwOEAgWzqFU3PRhca5egrdmC9HLdlhNnLgn5m80LMKsYebAgMBAAECggEAFSh6xy2I4a7hh3w9BqcODkE7EY6H7Q8l8u1cq+itQkqqpGU346a3Y7LJXmshrwOkg8hxYvak/DiydqF9k81pZn/ywfwW6KNZs/orzc9f+RMa+WZ51MjdE+H2dAUZt4IXXOc9FAViTGzC/Xc61lIb6CKhnyei6VEw4RezdACvZbMBladHMknKM4cUIcYWykR9q1iW++wkCgdZViGgMCEDoMaA+Id8hzdGun6GYvd9IfDRAW7iuG8OJnWepI3rc8UjAWllgn4E14ruowz5EvrI2eQT82Us+dpPoQ823noyZYfx9hWMuj7QMAONyta7sE54gHif8k5Gu2j4FhDH/ipAAQKBgQDs2L2pKXTMGH4+5lS9W9OADJeuce15hjpK1FNz6dCR9zTmK4N4t48tp6NJkxPVgwl3V13ImXBgBW9hij9njw5auDXQTWlxE7pay9/PMRuBARvs5w/On9l+TLQbj1WRKh/DDs5d9ivfeKPDLXQ0nh9gNEq/ZO2AxXeYpLar5UhscQKBgQDDJyp6wTzDkG6g+AbFI/ncDtZ14yL+UmcWAhcqObwRtdRWNx2caGwNEEhbMUkhsCQ5LRx3x1VzM15PhMMWh7qzD6Q1q7MKgxPnVDbDDqd2SoT8M+wBDwUeohzov0FnN1nWu/dvO5Hl/H3SACN8aczILPue9FgccyqN7SOxUIkqywKBgHqPOa2kpyzJ6+IunGEUeGCFo6QAjktZWybHspuDvYsa6gPFNpYsHmoYmlWDRzswQKDB0+TvCYiWp2lI+cBj9aCaPDabKFn8RG2hnCICHmJmKYYwlyxr6UcC+Bxe0n941dDBA6b5sQBF1DA2gDCONlw5EwjxeDkvddDGQ0S2NADxAoGAUpgL8YJBTl+dgsEWVG9ie1FIUQJ9t/d2K6lc1oEy6Kf1IzwWazECshC3HulgE0LyQcSm7MbPkn8mJi+BRdLSIKC5FgICN6u8S8anmqxkEbt+RyNOejA6MXnZFGSFsMKLYkeRdGFY4WaYdb2bRzeb9QicweyWMgxH8WNxHMWCGDsCgYB6uDqS/DzmcA/jTkXfsVWFW/Loq44CFV3v+m7zwx3UNeNIOVf2SV7lqc3akPfDLHw8aaEw7K78JJsFnWlkrIi5GWF01lGTSIYJy18bWmmP/E/6yEKQRWULL6uFyTz4JqPv+HpiDecx94pVaDhnHB9iXAtU8zheKRLYjMukSx9m/Q==",
		
		//异步通知地址
		'notify_url' => "http://www.qiyue99.com/qiyue/cart/alipay_notify.html",
		
		//同步跳转
		'return_url' => "http://www.qiyue99.com/qiyue/cart/alipay_return.html",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipay.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAtI1UyeCJCmRfrcrRKbxscZOCwnQMpytM3oKnPguBbqoH9/7POp7EnSOitPM0Ek+VlLLWx7v/j+w5C1TwtbMbVJQKuylc9SRYPod1mXB1nW+aBFCkG9K6jua+mXOoa5f2XdHuxntpIICDinlX6xkkEya5qNeJGTQSvicVuTRhR42p42b3+A9Pkgjc9rK6gthKgHBaWW9mc605SXLUEGOyqz2PjZPq8sZcYKEBneRvg6S4l6yF9JC2Eod2MqDki5rhMu6FTX7cTsxUuYq1co6Pc4a0jfRbYk/+6XHQfyiZcDhAIFs6hVNz0YXGuXoK3ZgvRy3ZYTZy4J+ZvNCzCrGHmwIDAQAB",
		
	
);