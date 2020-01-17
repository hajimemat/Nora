<?php
namespace Nora\Framework;

use Nora\Framework\Adapter\Misc\Elasticsearch\ElasticsearchClientInterface;
use Nora\Framework\DI\InjectorInterface;
use Nora\Framework\Kernel\Context\AppContext;
use Nora\Framework\Kernel\KernelInjector;
use Nora\Framework\Bootstrap;
use Nora\Framework\Kernel\KernelInterface;
use PHPUnit\Framework\TestCase;

class ElasticsearchTest extends TestCase
{
    /**
     * @test
     */
    public function カーネルロード()
    {
        $kernel = (new Bootstrap)('NoraFake', 'app-test');
        $this->assertInstanceOf(\NoraFake\Kernel\Kernel::class, $kernel);

        return $kernel;
    }

    /**
     * @test
     * @depends カーネルロード
     */
    public function ElasticsearchClientの起動(KernelInterface $kernel)
    {
        $this->assertInstanceOf(
            ElasticsearchClientInterface::class,
            $kernel->elasticsearch
        );

        return $kernel->elasticsearch;
    }

    /**
     * var/scheme.jsonでインデックスを構築しておくこと
     *
     * @test
     * @depends ElasticsearchClientの起動
     */
    public function ドキュメントの作成(ElasticsearchClientInterface $client)
    {
        // 一個だけ
        $client->index([
            'index' => 'unit_test',
            'id' => '1',
            'body' => [
                'first_name' => 'はじめ',
                'age' => '36',
                'comment' => '文章をGETすると、文章解析した結果がコンソールに表示されます'
            ]
        ]);
        $res = $client->bulk([
            'body' => [
                [
                    'index' => [
                        '_index' => 'unit_test',
                        '_id' => 2
                    ]
                ],
                [
                    'first_name' => 'かおり',
                    'age' => '39',
                    'comment' => '松本家の長女'
                ],
                [
                    'index' => [
                        '_index' => 'unit_test',
                        '_id' => 3
                    ]
                ],
                [
                    'first_name' => 'つとむ',
                    'age' => '80',
                    'comment' => '松本家の父'
                ],
                [
                    'index' => [
                        '_index' => 'unit_test',
                        '_id' => 4
                    ]
                ],
                [
                    'first_name' => 'しずこ',
                    'age' => '70',
                    'comment' => '松本家の母'
                ]
            ]
        ]);


        $result = $client->get([
            'index' => 'unit_test',
            'id' => '1',
        ]);


        $this->assertEquals('はじめ', $result['_source']['first_name']);


        $result = $client->search([
            'index' => 'unit_test',
            'body' => [
                'query' => [
                    "match" =>  [
                        'first_name' => 'つとむ'
                    ]
                ]
            ]
        ]);


        $result = $client->search([
            'index' => 'unit_test',
            'body' => [
                'query' => [
                    "match" =>  [
                        'comment' => 'の長'
                    ]
                ]
            ]
        ]);

        $this->assertCount(3, $result['hits']);

        // https://www.wantedly.com/companies/wantedly/post_articles/30216?auto_login_flag=true#_=_
    }
}
