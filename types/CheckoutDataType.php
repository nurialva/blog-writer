<?php

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

class CheckoutDataType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'CheckoutData',
            'description' => 'CheckoutData_Type',
            'fields' => function() {
                return [
                    'id' => [
                        'type' => Types::nonNull(Types::string()),
                        'resolve' => function($value) {
                            return (string) $value->id;
                        }
                    ],
                    'product' => [
                        'type' => Types::product()
                    ],
                    'product_qty' => [
                    	'type' => Types::string()
                    ],
                    'product_amout' => [
                    	'type' => Types::string()
                    ]
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

   public function product($value, $args, $context) {
        $pdo = $context['pdo'];
        $pid = $value -> product_id;
        $stmt = $pdo -> prepare (
        	"select * from product where id = {$pid}"
        );
        $stmt -> execute ();
        return $result->fetchObject() ?: null;
    }
    

}
