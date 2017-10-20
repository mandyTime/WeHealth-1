<?php global $salong; ?>
<!--幻灯片-->
<?php if(get_post_meta($post->ID, "slides", true)) { ?>
<section class="swiper-container swiper-gallery<?php wow(); ?>">
    <div class="swiper-wrapper">
        <?php $slide_arr=get_post_custom_values( "slides");$slide_str=isset($slide_arr[0])? $slide_arr[0]: "";$slides=explode(PHP_EOL,$slide_str);?>
        <?php foreach ($slides as $slide){$value=explode( "|",$slide);?>
        <article class="swiper-slide slide-gallery<?php if(get_post_meta($post->ID, " gallery_show ", true)) { echo ' gallery_show'; } ?>">
            <a href="<?php echo reset($value); ?>" data-fancybox="gallery" title="<?php if($result==2 ){ echo end($value); }else { the_title();} ?>"><img src="<?php echo reset($value); ?>" alt="<?php if($result==2 ){ echo end($value); }else { the_title();} ?>"><?php $result = count($value); if($result == 2){ ?><span><?php echo end($value); ?></span><?php } ?></a>
        </article>
        <?php } ?>
    </div>
    <!-- 导航 -->
    <div class="swiper-pagination swiper-gallery-pagination"></div>
    <!-- 按钮 -->
    <div class="swiper-button swiper-gallery-button-next swiper-button-next icon-angle-right"></div>
    <div class="swiper-button swiper-gallery-button-prev swiper-button-prev icon-angle-left"></div>
    <script type="text/javascript">
        //画廊文章幻灯片
        var swiper = new Swiper('.swiper-gallery', {
            pagination: '.swiper-gallery-pagination',
            nextButton: '.swiper-gallery-button-next',
            prevButton: '.swiper-gallery-button-prev',
            paginationClickable: true,
            centeredSlides: true,
            autoplay: 5000,
            autoplayDisableOnInteraction: false,
            lazyLoading: true,
            keyboardControl: true,
            <?php if(get_post_meta($post->ID, "gallery_effect", true) == 'gradient') { ?>
            effect: 'fade',
            <?php }else{ ?>
            effect: 'slide',
            <?php } if(get_post_meta($post->ID, "gallery_loop", true)) { ?>
            loop: false,
            <?php }else{ ?>
            loop: true
            <?php } ?>
        });
    </script>
</section>
<?php } ?>
<!--幻灯片end-->