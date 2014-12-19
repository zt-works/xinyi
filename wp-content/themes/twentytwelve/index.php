<?php
/**
 * The main template file.
 *
 * This is the most generic template file in a WordPress theme
 * and one of the two required files for a theme (the other being style.css).
 * It is used to display a page when nothing more specific matches a query.
 * For example, it puts together the home page when no home.php file exists.
 *
 * Learn more: http://codex.wordpress.org/Template_Hierarchy
 *
 * @package WordPress
 * @subpackage Twenty_Twelve
 * @since Twenty Twelve 1.0
 */
$headline_id=7;//头条文章id

$courses_id=2;//课程分类id

$teachers_id=3;//名师分类id

$news_id=4;//心一动态分类id

$about_id=5;//关于分类id

?>

<!-- 头 部 -->
<?php get_header() ?>

<script src="<?php echo get_template_directory_uri(); ?>/js/frontend/lib/jquery-1.11.1.min.js"></script>
<script src="<?php echo get_template_directory_uri(); ?>/js/frontend/src/index.js"></script>
<!-- **************** 头条消息 ****************  -->
<div class="topPostContainer" id="section0">
<a href="#" class="navBtn prevBtn hidden" id="prevBtn">上一页</a>
    <ul class="topPostList" id="topPostList">

        <?php
        // The Query
        $query = new WP_Query(array(
            "tag_id"=>$headline_id,"posts_per_page"=>3,"orderby"=>'date',"order"=>'DESC'
        ));

        // The Loop
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $post_id=get_the_ID();

                if($background=get_post_meta($post_id,"zy_background",true)){
                    $background=json_decode($background,true);
                    $background_src=$background["filepath"];
                }else{
                    $background_src=get_template_directory_uri()."/images/frontend/app/00.jpg";
                }

                $sub_title=get_post_meta($post_id,"sub_title",true);

                ?>

                <li class="item">
                    <img class="background" src="<?php echo $background_src ?>">
                    <div class="info">
                        <!--新学期采用自定义字段-->
                        <h3 class="subTitle textEllipsis"><?php echo $sub_title; ?></h3>
                        <h2 class="title textEllipsis"><?php the_title(); ?>!</h2>
                        <a href="<?php the_permalink(); ?>" class="detail">详细信息</a>
                    </div>
                </li>

            <?php
            }
        }

        /* Restore original Post Data */
        wp_reset_postdata();

        ?>

    </ul>

    <a href="#" class="navBtn nextBtn" id="nextBtn">下一页</a>
</div>

<div id="section1" class="section coursesSection">
    <h2 class="sectionTitle">
        <?php
            $courses_category=get_category($courses_id);
            echo $courses_category->name;
        ?>
    </h2>
    <p class="sectionAbstract">
        <?php
            echo $courses_category->description;
        ?>
    </p>
    <ul class="coursesList">
        <?php
        // The Query
        $query = new WP_Query(array(
            "cat"=>$courses_id,"posts_per_page"=>3,"orderby"=>'date',"order"=>'DESC'
        ));

        // The Loop
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $post_id=get_the_ID();
                $date_range=get_post_meta($post_id,"date_range",true);
                $address=get_post_meta($post_id,"address",true)

                ?>
                <li class="item">
                    <div class="info">
                        <!--后台的摘要-->
                        <p class="date"><?php echo $date_range; ?></p>
                        <h3 class="title"><?php the_title(); ?></h3>
                        <!--教师采用自定义字段-->
                        <p class="teacher">地点：<?php echo $address; ?></p>
                    </div>
                    <a href="<?php the_permalink(); ?>" class="detail">详细信息</a>
                </li>

            <?php
            }
        }

        /* Restore original Post Data */
        wp_reset_postdata();

        ?>
    </ul>
    <a href="<?php echo get_category_link($courses_id) ?>" class="more">更多<?php echo $courses_category->name; ?></a>
</div>

<div id="section2" class="section teachersSection">
    <h2 class="sectionTitle">
        <?php
        $teachers_category=get_category($teachers_id);
        echo $teachers_category->name;
        ?>
    </h2>
    <ul class="teachersList">
        <?php
        // The Query
        $query = new WP_Query(array(
            "cat"=>$teachers_id,"posts_per_page"=>3,"orderby"=>'date',"order"=>'DESC'
        ));

        // The Loop
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $post_id=get_the_ID();
                $grade=get_post_meta($post_id,"grade",true);
                $course=get_post_meta($post_id,"course",true);
                $showDir="";
                if(has_post_thumbnail($post_id)){
                    $thumbnail_id=get_post_thumbnail_id($post_id);
                    if(wp_get_attachment_metadata($thumbnail_id)){

                        //如果存在保存媒体文件信息的metadata，那么系统是可以获取出缩略图的
                        $showDir= wp_get_attachment_image_src($thumbnail_id,"post-thumbnail");
                        $showDir=$showDir[0];
                    }
                }
                ?>
                <li class="item">
                    <img class="thumb" src="<?php echo $showDir; ?>">
                    <div class="info">
                        <h3 class="title textEllipsis"><?php the_title(); ?></h3>
                        <span class="grade">年级：<?php echo $grade; ?></span>
                        <span class="course">科目：<?php echo $course; ?></span>
                        <p class="abstract"><?php echo get_the_excerpt(); ?></p>
                    </div>
                    <a href="<?php the_permalink(); ?>" class="detail">详细信息</a>
                </li>

            <?php
            }
        }

        /* Restore original Post Data */
        wp_reset_postdata();

        ?>
    </ul>
    <a href="<?php echo get_category_link($teachers_id) ?>" class="more gray">更多<?php echo $teachers_category->name; ?></a>
</div>

<div id="section3" class="section newsSection">
    <h2 class="sectionTitle">
        <?php
        $news_category=get_category($news_id);
        echo $news_category->name;
        ?>
    </h2>
    <ul class="newsList">
        <?php
        // The Query
        $query = new WP_Query(array(
            "cat"=>$news_id,"posts_per_page"=>3,"orderby"=>'date',"order"=>'DESC'
        ));

        // The Loop
        if ( $query->have_posts() ) {
            while ( $query->have_posts() ) {
                $query->the_post();
                $post_id=get_the_ID();
                $grade=get_post_meta($post_id,"grade",true);
                $course=get_post_meta($post_id,"course",true);
                $showDir="";
                if(has_post_thumbnail($post_id)){
                    $thumbnail_id=get_post_thumbnail_id($post_id);
                    if(wp_get_attachment_metadata($thumbnail_id)){

                        //如果存在保存媒体文件信息的metadata，那么系统是可以获取出缩略图的
                        $showDir= wp_get_attachment_image_src($thumbnail_id,"post-thumbnail");
                        $showDir=$showDir[0];
                    }
                }
                ?>
                <li class="item">
                    <img class="thumb" src="<?php echo $showDir; ?>">
                    <div class="info">
                        <h3 class="title textEllipsis"><?php the_title(); ?></h3>
                        <p class="abstract"><?php echo get_the_excerpt(); ?></p>
                    </div>
                    <a href="<?php the_permalink(); ?>" class="detail">详细信息</a>
                </li>

            <?php
            }
        }

        /* Restore original Post Data */
        wp_reset_postdata();

        ?>
    </ul>
    <a href="<?php echo get_category_link($news_id) ?>" class="more gray">更多<?php echo $news_category->name; ?></a>
</div>

<div id="section4" class="section aboutUsSection">
    <h2 class="sectionTitle">
        <?php
        $about_category=get_category($about_id);
        echo $about_category->name;
        ?>
    </h2>
    <?php
        $query = new WP_Query(array(
            "cat"=>$about_id,"posts_per_page"=>1,"orderby"=>'date',"order"=>'DESC'
        ));

    // The Loop
    if ( $query->have_posts() ) {
        while ( $query->have_posts() ) {
            $query->the_post();
    ?>

    <p class="content"><?php echo get_the_excerpt(); ?></p>
    <a href="<?php the_permalink(); ?>" class="more gray">详细信息</a>
    <?php
        }
    }

    /* Restore original Post Data */
    wp_reset_postdata();

    ?>
</div>

<!--脚部-->
<?php get_footer(); ?>