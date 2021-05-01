<?php

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

class CheckoutType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Checkout',
            'description' => 'CheckoutData',
            'fields' => function() {
                return [
                    'id' => [
                        'type' => Types::nonNull(Types::string()),
                        'resolve' => function($value) {
                            return (string) $value->id;
                        }
                    ],
                    'data' => [
                    	'type' => Types::listOf(Types::checkout_data())
                    ],
                    'date' => [
                        'type' => Types::string()
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

   public function data($value, $args, $context)
    {
        $pdo = $context['pdo'];
        $cid = $value -> id;
        $stmt = $pdo -> prepare (
        	"select * from checkout_data where id = {$cid}"
        );
        $stmt -> execute ();
        return $result->fetchObject() ?: null;
    }
    

}
