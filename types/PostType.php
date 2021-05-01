<?php

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

class PostType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Post',
            'description' => 'Data postingan',
            'fields' => function() {
                return [
                    'id' => [
                        'type' => Types::nonNull(Types::string()),
                        'resolve' => function($value) {
                            return (string) $value->id;
                        }
                    ],
                    'content' => [
                        'type' => Types::string()
                    ],
                    'date' => [
                        'type' => Types::string()
                    ]
                ];
            },
            'resolveField' => function($value, $args, $context, ResolveInfo $info) {
                if (method_exists($this, $info->fieldName)) {
                    return $this->{$info->fieldName}($value, $args, $context, $info);
                } else {
                    return is_numeric($value->{$info->fieldName})? (int) $value->{$info->fieldName} : $value->{$info->fieldName};
                }
            }
        ];
        parent::__construct($config);
    }


}
