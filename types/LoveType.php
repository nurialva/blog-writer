<?php

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

class LoveType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Kesukaan',
            'description' => 'Barang_kesukaan',
            'fields' => function() {
                return [
                    'id' => [
                        'type' => Types::nonNull(Types::string()),
                        'resolve' => function($value) {
                            return (string) $value->id;
                        }
                    ],
                    'produk' => [
                        'type' => Types::product()
                    ],
                ];
            },
            'resolveField' => function($value, $args, $context, ResolveInfo $info) {
                if (method_exists($this, $info->fieldName)) {
                    return $this->{$info->fieldName}($value, $args, $context, $info);
                } else {
                    return $value->{$info->fieldName};
                }
            }
        ];
        parent::__construct($config);
    }

    public function produk($value, $args, $context)
    {
        $pdo = $context['pdo'];
        $uid = $value -> user_id;
        $pid = $value -> product_id;
        $result = $pdo->query("select * from product where id = {$pid}");
        return $result->fetchObject() ?: null;
    }

}
