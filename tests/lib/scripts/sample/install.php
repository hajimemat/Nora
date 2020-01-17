<?php

use Elasticsearch\Common\Exceptions\Missing404Exception;

$kernel = include dirname(__DIR__).'/bootstrap.php';

// ElasticSearchをセットアップする
try {
    $kernel->elasticsearch->indices()->delete([
        'index' => 'google',
    ]);
} catch (Missing404Exception $e) {
    // なければ無いで良い
}
try {
    $kernel->elasticsearch->indices()->delete([
        'index' => 'unit_test',
    ]);
} catch (Missing404Exception $e) {
    // なければ無いで良い
}

// Googleユーザを格納
$kernel->elasticsearch->indices()->create([
    'index' => 'google',
    'pretty' => true,
    'body' => [
        'mappings' => [
            "properties" => [
                "type" => [ "type" => "keyword" ],
                "email" => [ "type" => "keyword" ],
                "token_uuid" => [ "type" => "keyword" ]
            ]
        ]
    ]
]);

// ElasticSearch用
$kernel->elasticsearch->indices()->create([
    'index' => 'unit_test',
    'pretty' => true,
    "body" => [
        "settings" => [
            "number_of_shards" => 1,
            "number_of_replicas" => 1,
            "analysis" => [
                "analyzer" => [
                    "avap_kuromoji_analyzer" => [
                        "type" => "custom",
                        "tokenizer" => "kuromoji_tokenizer"
                    ]
                ]
            ]
        ],
        "mappings" => [
            "_source" => [
                "enabled" => true
            ],
            "properties" => [
                "first_name" => [
                    "type" => "keyword"
                ],
                "age" => [
                    "type" => "integer"
                ],
                "comment" => [
                    "type" => "text",
                    "analyzer" => "avap_kuromoji_analyzer"
                ]
            ]
        ]
    ]
]);
