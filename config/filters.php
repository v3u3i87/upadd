<?php

Routes::filters('test',function(){

    if(false){
        echo '嘻嘻';
    }else{
        echo '抱歉,没有登陆哈哈';
    }
    echo '<br />';

});


Routes::filters('info',function(){

    echo '需要思考的实现';
    echo '<br />';

});