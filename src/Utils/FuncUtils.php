<?php
namespace Polymer\Utils;

use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Context\ExecutionContextInterface;

class FuncUtils
{
    /**
     * 生成用户的salt加密串
     *
     * @param int $len
     * @param int $type (1=>数字 , 2=>字母 , 3=>混合)
     * @return string
     */
    public static function generateSalt($len = 32, $type = 3)
    {
        $arr[1] = [1, 2, 3, 4, 5, 6, 7, 8, 9];
        $arr[2] = ['b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'm', 'n', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'z'];
        $arr[3] = ['b', 'c', 'd', 'f', 'g', 'h', 'j', 'k', 'm', 'n', 'p', 'q', 'r', 's', 't', 'u', 'v', 'w', 'x', 'z', '2', '3', '4', '5', '6', '7', '8', '9'];
        $word = '';
        $cnt = count($arr[$type]) - 1;
        mt_srand((float)microtime() * 1000000);
        shuffle($arr[$type]);
        for ($i = 0; $i < $len; $i++) {
            $word .= $arr[$type][random_int(0, $cnt)];
        }
        if (strlen($word) > $len) {
            $word = substr($word, 0, $len);
        }
        return $word;
    }

    /**
     * 用于Symfony的Callback验证器
     *
     * @param $object
     * @param ExecutionContextInterface $context
     * @param $payload
     */
    public static function validate($object, ExecutionContextInterface $context, $payload)
    {
        $context->buildViolation('This name sounds totally fake!')->atPath('firstName')->addViolation();
    }

    /**
     * 将实体对象转换为数组
     *
     * @param mixed $entity 实体对象
     * @param null $format 转换成的格式  json xml
     * @return array|object|\Symfony\Component\Serializer\Normalizer\scalar
     */
    public static function entityToArray($entity, $format = null)
    {
        $encoders = array(new XmlEncoder(), new JsonEncoder());
        $normalizers = array(new ObjectNormalizer());
        $serializer = new Serializer($normalizers, $encoders);
        return $serializer->normalize($entity, $format);
    }
}