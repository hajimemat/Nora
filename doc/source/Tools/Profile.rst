パフォーマンス計測
=====================

`参考 <https://tech.fusic.co.jp/posts/tideways-xhprof-profiler/>`_

使用ツール

1. xprof (php extension)
1. tideways/toolkit (go)
1. dot (graphviz)


.. code-block:: sh

   git clone https://github.com/tideways/php-xhprof-extension
   cd php-xhprof-extension
   phpize
   ./configure
   make
   sudo make install


.. code-block:: sh

   go get github.com/tideways/toolkit

.. code-block:: sh

   toolkit generate-xhprof-graphviz 5bd7e95b34f23.seike460.xhprof
   dot -Tpng callgraph.dot > callgraph.png

.. code-block:: php

   <?php
   function hoge($i)
   {
       echo 'hoge' . $i;
   }
   function saveXhprofData()
   {
       $savePath = uniqid() . 'seike460.xhprof';
       $data = tideways_xhprof_disable();
       file_put_contents($savePath, json_encode($data));
   }
   // プロファイリング開始
   tideways_xhprof_enable();
   // スクリプト終了時にデータ保存する関数
   register_shutdown_function('saveXhprofData');

   $i=1;
   while ($i < 100) {
       hoge($i);
       $i++;
   }
