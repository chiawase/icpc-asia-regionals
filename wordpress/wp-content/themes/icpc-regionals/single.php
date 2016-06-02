<?php get_header(); ?>

<?php if (have_posts()): ?>
  <?php while (have_posts()): the_post(); ?>
  <div id="single">
    <div id="post-<?php the_ID(); ?>" class="post">
      <div class="post-header">
        <?php the_title('<h1>', '</h1>'); ?>
        <div class="post-info">By <a href="<?php the_author_link(); ?>" class="post-author"><?php the_author(); ?></a> | <span class="post-datetime"><?php the_time('g:i A, j F Y'); ?></span></div>
      </div>
      <div class="post-body">
        <?php the_content(); ?>
      </div>
      <div class="post-footer">
        <hr>
        <?php if (has_tag()): ?>
          <?php the_tags('<ul class="post-tags"><li class="post-tag">', '</li><li class="post-tag">', '</li></ul>'); ?>
        <?php else: ?>
          <p style="font-style: italic;">No tags</p>
        <?php endif; ?>
      </div>
    </div>
  </div>
  <?php endwhile; ?>
<?php endif; ?>

<?php get_footer(); ?>