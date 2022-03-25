<?php
//配置文件
return [
    'hello/:name' =>['index/hello',[],['name'=>'\w+']],
];