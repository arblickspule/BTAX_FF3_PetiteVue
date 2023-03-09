<div class="hero is-small is-hidden" :class="{'is-info': (info.msgSeverity & 1), 'is-warning': (info.msgSeverity & 2), 'is-danger': (info.msgSeverity & 4)}">
  <div class="hero-head">
    <div class="container has-text-centered">
      <p class="title">$[ info.titleText ]</p>
    </div>
  </div>
  <!-- Hero content: will be in the middle -->
  <div class="hero-body">
    <div class="container has-text-centered">
      <p>$[ info.bodyText ]</p>
      <p><?= ($BASE) ?></p>
      <p><?= ($SESSION['howdy']) ?></p>
    </div>
  </div>
</div>