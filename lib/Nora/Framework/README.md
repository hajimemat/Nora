ファイル構成
====================

1. Annotation アノテーション
1. AOP アスペクト志向ライブラリ
1. Compiler PHPの生成
1. DI Dependency Injection
1. Renderer 描画エンジン
1. Resource リソース志向
1. ValueObject 値


テーマ: それぞれの主人公をわかりやすくする
==========================================


DIの主人公はContainerとBindである。


用語
:
操作 Manipulation
インスタンス化 Instantiate,Instantiation


Elasticsearch
============================


sysctl -w vm.max_map_count=262144
curl -X GET "localhost:9200/_cat/health?v&pretty"


外部ライブラリ選定
============================


ロガー Monolog
---------------------

PSR準拠、AWSクラウドウォッチ、Slackへの対応など多機能


キャッシュ cache/cache
------------------------

PSR準拠、Array, Apcu, Redis, Memcacheなどアダプターが豊富

Elasticsearch elasticsearch/elasticsearch-php
---------------------------------------------------

公式

GuzzleHttp
---------------------------------------------------

使われているのを見かけることが多いため
