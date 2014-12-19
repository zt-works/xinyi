<?php
get_header();
$current_category=get_the_category();

global $wp_query;
$get_grade=$_GET["grade"];
$get_full_year=$_GET["full_year"];

//print_r($_GET);

$args=$wp_query->query_vars;
$meta_query=array();
if($get_grade&&$get_grade!="all"){
    array_push($meta_query,array("key"=>"grade","value"=>$get_grade));
}
if($get_grade&&$get_full_year!="all"){
    array_push($meta_query,array("key"=>"year","value"=>$get_full_year));
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
                <option value="一年级">一年级</option>
                <option value="二年级">二年级</option>
                <option value="三年级">三年级</option>
                <option value="四年级">四年级</option>
                <option value="五年级">五年级</option>
                <option value="六年级">六年级</option>
                <option value="七年级">七年级</option>
                <option value="八年级">八年级</option>
                <option value="九年级">九年级</option>
            </select>
            <label class="labelTxt">年份:</label>
            <select class="selectTxt" name="full_year">
                <option value="all">全部</option>
                <option value="2014">2014</option>
                <option value="2015">2015</option>
                <option value="2016">2016</option>
                <option value="2017">2017</option>
                <option value="2018">2018</option>
                <option value="2019">2019</option>
            </select>
            <input class="searchBtn" value="搜索" type="submit">
        </form>
    </div>
    <ul class="postList">
        <?php while (have_posts()) : the_post();

            $post_id=get_the_ID();
            $thumb="";
            $date_range=get_post_meta($post_id,"date_range",true);
            $address=get_post_meta($post_id,"address",true);
            $full_year=get_post_meta($post_id,"full_year",true);
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
                    <p>年份：<?php echo $full_year; ?></p>
                    <p>地点：<?php echo $address; ?></p>
                    <p>开课周期：<?php echo $date_range; ?></p>
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
            trailingslashit( remove_query_arg( array('grade',"year") ,get_pagenum_link(1,false)) ).
            'page/%#%/', 'paged' );
    }
    //print_r(get_pagenum_link(1)."<br>".$base);
    echo paginate_links(array(
        'base' => $base,
        //'format' => $format,
        'current' => $current_page,
        'total' => $total,
        'mid_size' => 4,
        "add_args"=>array( 'grade' => $get_grade,"year"=>$get_full_year),
        'type' => 'list'
    ));
}
?>
<?php get_footer(); ?>