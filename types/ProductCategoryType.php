<?php

use GraphQL\Type\Definition\ObjectType;
use GraphQL\Type\Definition\ResolveInfo;

class ProductCategoryType extends ObjectType
{
    public function __construct()
    {
        $config = [
            'name' => 'Product_Category',
            'description' => 'Data kategori produk',
            'fields' => function() {
                return [
                    'id' => [
                        'type' => Types::nonNull(Types::int()),
                        'resolve' => function($value) {
                            return (int) $value->id;
                        }
                    ],
                    'name' => [
                        'type' => Types::string(),
                    ],
                    'products' => [
                        'type' => Types::listOf(Types::product()),
                        'args' => [
                            'limit' => Types::int(),
                            'offset' => Types::int()
                        ]
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

    public function products($value, $args, $context)
    {
        $pdo = $context['pdo'];
        $category_id = $value->id;
        $limit = $args['limit'] ?: 10;
        $offset = $args['offset'] ?: 0;
        if ($limit > 50) $limit = 50;

        $result = $pdo->query("
            select * from product where catalog_id = {$category_id} order by id desc limit {$limit} offset {$offset}
        ");
        
        return $result->fetchAll(PDO::FETCH_OBJ);
    }
}
