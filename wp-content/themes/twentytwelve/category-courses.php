<?php
get_header();
$current_category=get_the_category();

global $wp_query;
$get_grade=$_GET["grade"];
$get_course=$_GET["course"];

$args=$wp_query->query_vars;
$meta_query=array();
if($get_grade!="all"){
    array_push($meta_query,array("key"=>"grade","value"=>$get_grade));
}
if($get_course!="all"){
    array_push($meta_query,array("key"=>"course","value"=>$get_course));
}

$args = array_merge( $args,array("meta_query"=>$meta_query));

query_posts($args);
?>
    <h2 class="singleTitle">
        <span class="text"><?php single_cat_title(); ?></span>
    </h2>
    <div class="filterContainer">
        <form class="search" action="<?php echo get_category_link($current_category[0]->cat_ID);  ?>" method="get">
            <label class="labelTxt">年级:</label>
            <select class="selectTxt" name="grade">
                <option value="all">全部</option>
                <option value="初中">初中</option>
                <option value="高中">高中</option>
            </select>
            <label class="labelTxt">科目:</label>
            <select class="selectTxt" name="course">
                <option value="all">全部</option>
                <option value="语文">语文</option>
                <option value="数学">数学</option>
            </select>
            <input class="searchBtn" value="搜索" type="submit">
        </form>
    </div>
    <ul class="postList">
        <?php while (have_posts()) : the_post();

            $post_id=get_the_ID();
            $thumb="";
            $date=get_post_meta($post_id,"date",true);
            $teacher=get_post_meta($post_id,"teacher",true);
            $course=get_post_meta($post_id,"course",true);
            $grade=get_post_meta($post_id,"grade",true);
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
                    <p>年级：<?php echo $grade; ?></p>
                    <p>科目：<?php echo $course; ?></p>
                    <p>教师：<?php echo $teacher; ?></p>
                    <p>开课周期：<?php echo $date; ?></p>
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
    //$format = empty($permalink_structure) ? '?page=%#%' :'/page/%#%/';
    $base=get_pagenum_link(1) . '%_%';
    if(!empty($permalink_structure)){
        $base=user_trailingslashit(
            trailingslashit( remove_query_arg( array('grade',"course") ,get_pagenum_link(1,false)) ).
            'page/%#%/', 'paged' );
    }
    //print_r(get_pagenum_link(1)."<br>".$base);
    echo paginate_links(array(
        'base' => $base,
        //'format' => $format,
        'current' => $current_page,
        'total' => $total,
        'mid_size' => 4,
        "add_args"=>array( 'grade' => $get_grade,"course"=>$get_course),
        'type' => 'list'
    ));
}
?>
<?php get_footer(); ?>