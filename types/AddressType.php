<?php

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

class AddressType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Address',
            'description' => 'Address_type',
            'fields' => function() {
                return [
                    'id' => [
                        'type' => Types::nonNull(Types::string()),
                        'resolve' => function($value) {
                            return (string) $value->id;
                        }
                    ],
                    'phone' => [
                    	'type' => Types::string()
                    ],
                    'address' => [
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
}
