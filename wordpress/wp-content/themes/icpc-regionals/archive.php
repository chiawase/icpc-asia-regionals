<?php get_header(); ?>

<main id="news">
  <?php if (have_posts()): ?>
    <?php
      the_archive_title('<h1 class="page-title">', '</h1>');
    ?>

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