<?php
/**
 * @author：storm
 * Email：hi@yumufeng.com
 * Date: 2017/5/17 0017
 * Time: 9:12
 */
namespace  Application\Component\Libs;
class Rsa2 {
	private static $PRIVATE_KEY ='-----BEGIN PRIVATE KEY-----
MIICeAIBADANBgkqhkiG9w0BAQEFAASCAmIwggJeAgEAAoGBALmDisHVFJK3Jesj
J1Bo9/N9AKUdLbqvLq2Wu8qFKv+dvxOz86Psk9RwV2NYftRqWrtzr5mZzilYEERb
zTeSGPbo84FBes1UYdNIzWUQ19ZeNoiTfQeuAx31ABX7E19rLsrKyEdzcg1Mnh2Y
iPVmeD/NG6KJONqyKecBH/hvsDVnAgMBAAECgYEAj8LrbahVqBi8DssQNVUTCSLO
tCW3gVsufiE4aAnot2wkXf1vCpJUQSXJ4tf6eVvt8iQFaa/yPsHa4U6O6siSBLum
v+kTpa0p81nw3uv9CMXePLlU+eFCmVkejSYwg+IQfplPLyHxf/g/wP3bmjpL256/
BLwPnGIRnLttcC/sNJECQQDiC8cL6h330nO5aKmB2bz47Bm8AQZnm2GXXaQ2xv76
Ip7ppS7hg+ilX4L8UBvBc88rD+AlGqaD32BlRozbyCopAkEA0hjHvomJ4VKlVqou
/YodZLYF22cKMNV1NAEWI5QevRv0lZqZA/eKFnfVmmEVlBxMPxg1FLDIs1BspObG
Xkl1DwJBAKlF8o+siTaNBZYhl1Yi7M1nWLod9mLdy84jFJbknApKpMAIr7u3IR++
D4PMpYxPoiL4J30BoRJ901zj4RWwpkkCQQC5NDcAc+YjxuMRXrxFrHb0zLClFFQb
cE/9I+gMPQpL0lTtfHbe1FvaKpEVofePtNJR3FkOgDisOX1McFL10vNpAkBii2tG
oqXkfLQIsyNNXrjROlPAtR5KKwdz9uXEYAYx9dv9xadn7quJYFdR6wJJNGzgcjyq
LhUjBOICRCN9nFvV
-----END PRIVATE KEY-----';
	private static $PUBLIC_KEY  = '-----BEGIN PUBLIC KEY-----
MIGfMA0GCSqGSIb3DQEBAQUAA4GNADCBiQKBgQC5g4rB1RSStyXrIydQaPfzfQCl
HS26ry6tlrvKhSr/nb8Ts/Oj7JPUcFdjWH7Ualq7c6+Zmc4pWBBEW803khj26POB
QXrNVGHTSM1lENfWXjaIk30HrgMd9QAV+xNfay7KyshHc3INTJ4dmIj1Zng/zRui
iTjasinnAR/4b7A1ZwIDAQAB
-----END PUBLIC KEY-----';

	/**
	 * 获取私钥
	 * @return bool|resource
	 */
	private static function getPrivateKey()
	{
		$privKey = self::$PRIVATE_KEY;
		return openssl_pkey_get_private($privKey);
	}

	/**
	 * 设置私钥
	 * @param $private_key
	 */
	public static function setPrivateKey($private_key){
		self::$PRIVATE_KEY = $private_key;
	}
	/**
	 * 获取公钥
	 * @return bool|resource
	 */
	private static function getPublicKey()
	{
		$publicKey = self::$PUBLIC_KEY;
		return openssl_pkey_get_public($publicKey);
	}

	/**设置公钥
	 * @param $public_key
	 */
	public static function setPublicKey($public_key){
		 self::$PUBLIC_KEY = $public_key;
	}

	/**
	 * 创建签名
	 * @param string $data 数据
	 * @return null|string
	 */
	public static function createSign($data = '')
	{
		if (!is_numeric($data)) {
			return null;
		}
		return openssl_sign(
			$data,
			$sign,
			self::getPrivateKey(),
			OPENSSL_ALGO_SHA256
		) ? base64_encode($sign) : null;
	}

	/**
	 * 验证签名
	 * @param string $data 数据
	 * @param string $sign 签名
	 * @return bool
	 */
	public static function verifySign($data = '', $sign = '')
	{
		if (!is_string($sign)) {
			return false;
		}
		return (bool)openssl_verify(
			$data,
			base64_decode($sign),
			self::getPublicKey(),
			OPENSSL_ALGO_SHA256
		);
	}
}