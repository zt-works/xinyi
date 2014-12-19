/**
 * Created by JetBrains WebStorm.
 * User: ty
 * Date: 14-9-20
 * Time: 上午12:00
 * To change this template use File | Settings | File Templates.
 */
var index=(function(){
    var currentPostIndex=0;
    function animation(){
        $("#topPostList").animate({
            "left":"-"+currentPostIndex*100+"%"
        },1000,function(){

        });
    }
    function toNext(){
        ++currentPostIndex;
        animation();
        if(currentPostIndex==2){
            $("#nextBtn").addClass("hidden");
        }

        $("#prevBtn").removeClass("hidden");
    }
    function toPrev(){
        --currentPostIndex;
        animation();
        if(currentPostIndex==0){
            $("#prevBtn").addClass("hidden");
        }

        $("#nextBtn").removeClass("hidden");
    }

    return {
        prev:toPrev,
        next:toNext,
        itemClick:function(index){
            var top=$("#section"+index).offset().top;
            if($("body").width()>960){
                top=top-120;
            }
            $("html,body").animate({
                "scrollTop":top
            },800);
        }
    }
})();
$(document).ready(function(){
    $("#nextBtn").click(function(){
        index.next();
        return false;
    });
    $("#prevBtn").click(function(){
        index.prev();
        return false;
    });
    $(".menu-item a").click(function(){
        index.itemClick($(this).parent().index());
        $(".menu-item").removeClass("current-menu-item");
        $(this).parent().addClass("current-menu-item");
        return false;
    })
});
