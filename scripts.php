<?php
  function ANtexter_css() {
      wp_enqueue_style( 'ANtexter_css', css/ANTextCss.css);
  }

  add_action('admin_head', 'ANtexter_css')

?>