<?php get_header(); ?>
    <h2 class="singleTitle">
        <span class="text"><?php single_cat_title(); ?></span>
    </h2>
    <ul class="postList">
        <?php while (have_posts()) : the_post();

            $post_id=get_the_ID();
            $thumb="";
            $grade=get_post_meta($post_id,"grade",true);
            $course=get_post_meta($post_id,"course",true);
            if(has_post_thumbnail($post_id)){
                $thumbnail_id=get_post_thumbnail_id($post_id);
                if(wp_get_attachment_metadata($thumbnail_id)){

                    //如果存在保存媒体文件信息的metadata，那么系统是可以获取出缩略图的
                    $thumb= wp_get_attachment_image_src($thumbnail_id,"post-thumbnail");
                    $thumb=$thumb[0];
                }
            }
            ?>
            <li class="item">
                <img class="thumb" src="<?php echo $thumb; ?>">
                <div class="info">
                    <h2><?php the_title(); ?></h2>
                    <span class="grade">年纪：<?php echo $grade; ?></span>
                    <span class="course">科目：<?php echo $course; ?></span>
                    <p class="abstract"><?php echo get_the_excerpt(); ?></p>
                    <a href="<?php the_permalink(); ?>" class="detail">详细信息</a>
                </div>
            </li>
        <?php endwhile; ?>
    </ul>

    <!-- 分页-->
<?php
global $wp_query;
$total = $wp_query->max_num_pages;
if ($total > 1) {
    if (!$current_page = get_query_var('paged')) {
        $current_page = 1;
    }
    //获取路径
    $permalink_structure = get_option('permalink_structure');
    $format = empty($permalink_structure) ? '&page=%#%' : '/page/%#%/';
    echo paginate_links(array(
        'base' => get_pagenum_link(1) . '%_%',
        'format' => $format,
        'current' => $current_page,
        'total' => $total, 'mid_size' => 4,
        'type' => 'list'
    ));
}
?>
<?php get_footer(); ?>