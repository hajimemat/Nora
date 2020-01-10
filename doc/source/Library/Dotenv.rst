Nora.Dotenv
===================
:namespace: Nora\\Dotenv

目的
-------------

.envファイルを読み込むライブラリ

使用方法
--------------

.. code-block:: php

   # .envファイルを上書き読み込みをする
   use Nora\Dotenv\NewDotenv;
   (new NewDotenv)(dirname(__DIR__))->override();


例外
--------------

.envファイルが存在しない場合はExceptionをなげる



テストケース
----------------------------------

.. literalinclude:: ../../../tests/Dotenv/DotenvTest.php
   :caption:
   :language: php
   :linenos:
