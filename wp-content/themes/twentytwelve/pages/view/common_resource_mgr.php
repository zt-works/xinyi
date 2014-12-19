<?php
/**
 * Created by JetBrains PhpStorm.
 * User: ty
 * Date: 14-6-26
 * Time: 上午8:51
 * To change this template use File | Settings | File Templates.
 */

//include("../controller/videoController.php");
global $current_user;
$types=array("1"=>"上课资料","2"=>"试题资料");
$status=array(0=>"可下载",1=>"上传失败",2=>"正在处理流媒体",3=>"处理流媒体失败，请重新上传");
$per_load=10;
$search=$_GET["search"];
$resource_controller=new Resource_Controller();
if(isset($_GET["option"])){
    $resource_id=$_GET["resource_id"];
    if($resource_controller->delete_resource($resource_id)===false){
        die("删除数据出错，请联系开发人员！");
    }else{
        echo "<p>删除成功，请继续其他操作！</p>";
    }
}
$offset=isset($_GET["paged"])?($_GET["paged"]-1)*$per_load:0;

$resources_count=$resource_controller->get_common_resources_count($search);
$resources=$resource_controller->get_common_resources($offset,$search);

?>
<script>
    var maxPage=<?php echo $resources_count/$per_load?$resources_count/$per_load:1; ?>;
    var currentPage=<?php echo isset($_GET["paged"])?$_GET["paged"]:1; ?>;
    var url="<?php echo admin_url("upload.php?page=common_resource_mgr&search=$search"); ?>";
</script>
<h2 class="title">公共资料管理</h2>
<form style="text-align: right;margin-right:30px;" method="get" action="<?php echo admin_url("upload.php"); ?>">
    <input type="hidden" name="page" value="common_resource_mgr">
    <input type="text" name="search" value="<?php echo $search ?>">
    <input type="submit" value="搜索">
</form>
<table>
    <thead>
    <tr>
        <th>文件名</th>
        <th>日期</th>
        <th>类型</th>
        <th>状态</th>
        <th>操作</th>
    </tr>
    </thead>
    <tbody>
    <?php
        foreach($resources as $value){
            ?>
            <tr>
                <td><?php echo $value->name; ?></td>
                <td><?php echo $value->resource_date; ?></td>
                <td><?php echo $types[$value->type]; ?></td>
                <td><?php echo $status[$value->status]; ?></td>
                <td>

                    <a href="<?php echo $value->url; ?>" target="_blank">下载</a>
                    <?php
                    if(current_user_can( 'manage_options' )){
                        ?>

                        <a href="<?php echo admin_url("upload.php?page=common_resource_mgr").
                            "&option=delete&resource_id=$value->id"; ?>" class="delete">删除</a></td>

                    <?php
                    }
                    ?>

            </tr>
            <?php
        }
    ?>
    </tbody>
</table>
<div id="ownPagination" class="ownPagination">
    <a href="#" class="first" data-action="first">首页</a>
    <a href="#" class="previous" data-action="previous">上一页</a>
    <input type="text" readonly="readonly" class="showPageInfo"/>
    <a href="#" class="next" data-action="next">下一页</a>
    <a href="#" class="last" data-action="last">末页</a>
</div>