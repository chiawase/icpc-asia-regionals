<?php get_header(); ?>

<div id="above-fold" style="background-image: linear-gradient(rgba(255,255,255,0.5), rgba(0,0,0,0.15), rgba(0,0,0,0.5), rgba(0,0,0,1)), url('<?php echo get_template_directory_uri(); ?>/img/above-fold-01.jpg');">
  <div id="main-info">
    <h1>2016 ACM ICPC<br>Asia-Manila Regional Contest</h1>
    <div id="details">
      <div id="date-box">
        <i class="fa fa-calendar"></i> <span id="date">December 14-16, 2016</span>
      </div>
      <div id="location-box">
        <i class="fa fa-map-marker"></i> <span id="location">Ateneo de Manila University</span>
      </div>
    </div>
  </div>

  <div id="buttons">
    <a href="<?php echo get_permalink(9); ?>">Register Now</a>
    <a href="#news">Learn More</a>
  </div>
</div>

<main id="news">
  <?php if (have_posts()): ?>
  <div id="posts">
  <?php while(have_posts()) : the_post(); ?>
    <div class="post">
      <div class="post-header">
        <a href="<?php the_permalink(); ?>"><h1 class="post-title"><?php the_title(); ?></h1></a>
        <div class="post-details">
          By <span class="post-author"><?php the_author(); ?></span> | <a href="<?php the_permalink(); ?>" class="post-datetime"><?php the_time('g:i A, j F Y'); ?></a>
        </div>
      </div>

      <div class="post-body">
        <div class="post-preview">
          <?php the_excerpt(); ?>
        </div>
        <hr>
        <?php if (has_tag()): ?>
          <?php the_tags('<ul class="post-tags"><li class="post-tag">', '</li><li class="post-tag">', '</li></ul>'); ?>
        <?php else: ?>
          <p style="font-style: italic;">No tags</p>
        <?php endif; ?>
      </div>
    </div>

    <?php getPrevNext(); ?>
    <!-- <div id="navigation"> -->
      <!-- <a href="#" class="older-posts">&laquo; Older posts</a> -->
      <!-- <a href="#" class="newer-posts">Newer posts &raquo;</a> -->
    <!-- </div> -->
  <?php endwhile; ?>
  </div>
  <?php endif; ?>

  <div id="social-media">
    <h3>Get Live Updates</h3>
    <a class="twitter-timeline" href="https://twitter.com/ICPCNews" data-widget-id="736185526772858882">Tweets by @ICPCNews</a>
    <script>!function(d,s,id){var js,fjs=d.getElementsByTagName(s)[0],p=/^http:/.test(d.location)?'http':'https';if(!d.getElementById(id)){js=d.createElement(s);js.id=id;js.src=p+"://platform.twitter.com/widgets.js";fjs.parentNode.insertBefore(js,fjs);}}(document,"script","twitter-wjs");</script>
  </div>
</main>


<?php get_footer(); ?>

<?php
function getPrevNext(){
  $pagelist = get_pages('sort_column=menu_order&sort_order=asc');
  $pages = array();
  foreach ($pagelist as $page) {
     $pages[] += $page->ID;
  }

  $current = array_search(get_the_ID(), $pages);
  $prevID = $pages[$current-1];
  $nextID = $pages[$current+1];
  
  echo '<div id="navigation">';
  
  if (!empty($prevID)) {
    echo '<a href="';
    echo get_permalink($prevID);
    echo '"';
    echo 'class="older-posts"';
    echo 'title="';
    echo get_the_title($prevID); 
    echo'">&laquo; Older posts</a>';
  }
  if (!empty($nextID)) {
    echo '<a href="';
    echo get_permalink($nextID);
    echo '"';
    echo 'class="newer-posts"';
    echo 'title="';
    echo get_the_title($nextID); 
    echo'">Newer posts &raquo;</a>';
  }
  
  echo '</div>';
} 
?>