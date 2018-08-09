<?php

namespace lav45\translate\test;

use Yii;
use yii\web\Application;
use lav45\translate\models\Lang;
use PHPUnit\Framework\TestCase;

class UrlManagerTest extends TestCase
{
    protected function mockWebApplication()
    {
        new Application([
            'id' => 'test_app',
            'basePath' => __DIR__,
            'components' => [
                'urlManager' => [
                    '__class' => \lav45\translate\web\UrlManager::class,
                    'baseUrl' => '',
                    'hostInfo' => 'http://site.com',
                    'scriptUrl' => '/index.php',
                    'showScriptName'  => false,
                    'enablePrettyUrl' => true,
                    'rules' => [
                        [
                            'pattern' => '<_lang:' . Lang::PATTERN . '>',
                            'route' => 'page/index',
                        ],
                        [
                            'pattern' => '<_lang:' . Lang::PATTERN . '>/<name:[\w\-]+>',
                            'route' => 'page/view',
                            'suffix' => '.html',
                        ],
                    ],
                ],
            ]
        ]);
    }

    public function testCreateUrl()
    {
        $tests = [
            '/en' => ['page/index'],
            '/ru' => ['page/index', '_lang' => 'ru'],
            '/ru?param=value' => ['page/index', 'param' => 'value', '_lang' => 'ru'],

            '/en/pageName.html' => ['page/view', 'name' => 'pageName'],
            '/ru/test-page.html' => ['page/view', 'name' => 'test-page', '_lang' => 'ru'],
            '/ru/test-page.html?param=value' => ['page/view', 'name' => 'test-page', '_lang' => 'ru', 'param' => 'value'],

            '/' => '/',
            '/site/index?_lang=en' => ['site/index'],
            '/site/index?param=val&_lang=en' => ['site/index', 'param' => 'val'],
            '/site/index?param=val&_lang=ru' => ['site/index', 'param' => 'val', '_lang' => 'ru'],
        ];

        $this->beginTest($tests);
    }

    /**
     * @param array $tests
     */
    protected function beginTest($tests)
    {
        $this->mockWebApplication();
        $urlManager = Yii::$app->getUrlManager();

        foreach($tests as $result => $params) {
            $this->assertEquals($urlManager->createUrl($params), $result);
        }
    }
}