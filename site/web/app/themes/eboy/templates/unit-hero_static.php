  <section id="top" class="bg-white top mt-2">
    <div class="header-content container-fluid">
      <div class="row">
        <div class="col-12 p-0">
          <h1 ><?= get_post_field('post_title', $post->ID) ?></h1>
   <?= get_post_field('post_content', $post->ID) ?>
      </div>
      </div>
    </div>
</section>
