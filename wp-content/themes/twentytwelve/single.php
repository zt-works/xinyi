<?php
/**
 * The Template for displaying all single posts.
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */

get_header();
$teachers_id=3;

$current_category=get_the_category();
//print_r($current_category);
while(have_posts()):the_post();
    $post_id=get_the_ID();
?>
    <h2 class="singleTitle">
        <span class="text"><?php the_title(); ?></span>
    </h2>
<?php
    if($current_category[0]->cat_ID!=$teachers_id){
        ?>
            <div class="singleContent">
                <?php the_content();?>
            </div>
        <?php
    }else{
        $thumb="";
        if(has_post_thumbnail($post_id)){
            $thumbnail_id=get_post_thumbnail_id($post_id);
            if(wp_get_attachment_metadata($thumbnail_id)){

                //如果存在保存媒体文件信息的metadata，那么系统是可以获取出缩略图的
                $thumb= wp_get_attachment_image_src($thumbnail_id,"post-thumbnail");
                $thumb=$thumb[0];
            }
        }
        $grade=get_post_meta($post_id,"grade",true);
        $course=get_post_meta($post_id,"course",true);
        ?>
            <div class="singleInfo">
                <img class="thumb" src="<?php echo $thumb; ?>">
                <div class="info">
                    <p>年级：<?php echo $grade;?></p>
                    <p>科目：<?php echo $course;?></p>
                    <p>简介：<?php echo get_the_excerpt();  ?></p>
                </div>
            </div>
            <div class="singleIntroduce">
                <?php the_content();?>
            </div>
        <?php
    }
?>
    <div class="singleNav">
        <span class="nav navPrevious">
            <?php previous_post_link('%link', '上一篇',true); ?>
        </span>
        <span class="nav navNext">
            <?php next_post_link('%link', '下一篇',true); ?>
        </span>
    </div>


<?php
    endwhile;
    get_footer();
?>