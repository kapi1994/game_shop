<?php $navLinks = navFunction();
?>
<nav class="navbar navbar-expand-lg bg-body-tertiary">
  <div class="container">
    <a class="navbar-brand" href="<?= $navLinks['url']?>?>">Navbar</a>
    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
      <span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse" id="navbarSupportedContent">
      <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
        <?php foreach($navLinks['pages'] as $navLink):?>
        <li class="nav-item">
          <a class="nav-link <?= isset($_GET['page']) && $_GET['page'] == $navLink->name ? "active fw-bold border-bottom" : "" ?>" aria-current="page" href="<?=$navLinks['url']?>?page=<?=$navLink->name?>"><?=ucfirst($navLink->name)?></a>
        </li>
       <?php endforeach;
        include "./includes/pages/fixedBlocks/authBlock.php";?>
      </ul>
    </div>
  </div>
</nav>