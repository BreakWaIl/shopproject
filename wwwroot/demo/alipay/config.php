<?php
$config = array (	
		//应用ID,您的APPID。
		'app_id' => "2016082000291715",

		//商户私钥
		'merchant_private_key' => "MIIEowIBAAKCAQEA1/nQccRYZMyxBC8zCV1G1Iuje1w33vhVeLZFUONbtyd0eMYS9z7mp8erQufI9aiaHNtkfzeLjx/wqQRTLoFahkrZpqAx3CLtDnaasiSteV8uHDkHMlz7iMzL7D3oW/ogtQeroK30axoJCvcoJtziUgzErpdusDw5x4syHQroLx5YZpQ4teBKjGyX0gWF2m1STHV2kxR6aFVdwhKQacDvDsj0ZXjt5hu3RRtEUNwFzhUSxrf8u+sf1qPhXgDoNaTr03MSi+jOEPS6TqcfKPpfKTyTFuNcbLe5UmUqL3bR656MvlU47oP59uml8Z4ClbM25iX8zPk3ClxOZkbh2JWh0QIDAQABAoIBAAYQTXnkOVcbKB0l9hjdNY/iG8Tq4eawsjWhaHDM8VZDFVIOvZmTeoZbZMOMHmhHQ4xr3HZ16MUr8GXOaUd0+kWq9FFpTf0QPfeZ/N3jibtOoMzKDRms4qMEnPUVB+ENNL3gaUEpoSMgqABW0BnHOdNz6FG+jq0EA9tGXTHTjJFGC5811JZeGaz7PcBplxKLFQbnxSIMCtzYrRFLkKk8NuCVNM8HcBJX2Qu0/Aa91gtlocCpzy5tjN3efLKrXJEAyxYM9cAQWWOR8w2KfVFDc8rjHgpekEQ89kr6+kwtMEXy2uY7EBa7uZ47F8uTTkLBC37V4wKaiZ6AHeW3IHDoisECgYEA82ddVE8n2WHHcsCX7O0qOlDDp3gbwvUMKTEOp1KnnVyTDKfnfuczHZMw3aV56OEbR/kEttfMRO7Ba9Ok+2gkGj6M8tiTL4R1ZFNqxYrdCP41KaqfPEDcE/Itn5vzr1kwv54JXOmw4CGHOMVnwJegTjFd3Ur2UsV36YWsP8XbXl8CgYEA4ycU+PcKzR7UoBuJfsBwycsqkaKZbMC0534BaYkQZi0ws/OEM8Gz0NpCXlwZGWo8+KztLEISJzrlcO1pY0qICoH4hEncGHA09Bq+La/Q8jmmcq1LjNwavMH87cE38SleGcZsXxuq+4JdIsi4Onia8aCN/O9uRPWf4vPwmA0Gjc8CgYACeivV/tQ9/yPDhDhHngZO/6FuI+fMbSgJh0lJ5Kp2DIoml9LgVMQNagEWUGfUOe5IJj2pCrs2fGOVkFregCnV5osSsaeV1ThIpByzE/256LRrzyO9vj8/KLjUJgct4q9/U8Fuo6Y8MbsplcC/kgt8oD3/UZL+NqpIwyaEo6Pe6wKBgF38EaVhMcrk8ry8KAuVvBUCRr6zNB/XLAKMFDGCUJK2J+yM2SpA/xFu/P7lxEzLtCaABjHFtJUevUgYMv6uG9OdKyIxHSvPYVmOpxloIV/XkpLoONYzS6zF5szIoUjbAOBRRQ2k+P3PVpHJ0s/+jr1i3NzGhC1sI5dXrzh6SzfpAoGBAIZV0gpXIirEPiUzvdVBti1soAkOPbEVrcC4srBuScCVO44Ju5DDh6DxBhfmnvMiEeGe6niVEzyUEVeFQxRhONCkxD8jrL3rox2YLXgryhcR9TKf91RzrCsXZgBOhH88wTSLHH2ld21WRd+qicAywgkR7vAXCZP8gLJprk0S6PXZ",
		
		//异步通知地址
		'notify_url' => "http://shop.com/demo/alipay/notify_url.php",
		
		//同步跳转
		'return_url' => "http://shop.com/demo/alipay/return_url.php",

		//编码格式
		'charset' => "UTF-8",

		//签名方式
		'sign_type'=>"RSA2",

		//支付宝网关
		'gatewayUrl' => "https://openapi.alipaydev.com/gateway.do",

		//支付宝公钥,查看地址：https://openhome.alipay.com/platform/keyManage.htm 对应APPID下的支付宝公钥。
		'alipay_public_key' => "MIIBIjANBgkqhkiG9w0BAQEFAAOCAQ8AMIIBCgKCAQEAqvUONW/fc2jtaVxEIHet4ORRQmdXcpV4TZT92Un9sUDXtmPMBGZ8jYAp5XGDIngwRqYe7RwdscpHRHek9qO/F3rplbAAxN0OQ2ijTG5PqwZhhOukeuwe30tSoqn7W0TPe5Z/vh5hLYAMq9gtb7/zoaGfA57iw5JQK+rzshfMyoM+jE+pmk8YrnG0lJqgqv04L2HLU84lhpM25W/NidPEr/Ru5g1feeeS6GDQWwuqP78PVwjoZcSqCov9oiPcf6Py1i2noKI9Af5I5kmMC8ptu0kyWHY8DGSZKr0twcZI54G4CcPTO+8N70rww/IMb/+PRwaBT6fb3SO8kdhZLuvFuwIDAQAB",
);