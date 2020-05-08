<div class="container-fluid">
<div class="row justify-content-center">
<div class="col-lg-6">
<h2 class="text-center">Ruiniere das Spiel!</h2>


<ul class="nav nav-tabs" id="myTab" role="tablist">
  <li class="nav-item">
    <a class="nav-link<?php if ($active_tab == 0) { echo " active"; } ?>" id="mobs-tab" data-toggle="tab" href="#mobs" role="tab" aria-controls="mobs" aria-selected="true">Mobs</a>
  </li>
  <li class="nav-item">
    <a class="nav-link<?php if ($active_tab == 1) { echo " active"; } ?>" id="items-tab" data-toggle="tab" href="#items" role="tab" aria-controls="items" aria-selected="false">Items</a>
  </li>
  <li class="nav-item">
    <a class="nav-link<?php if ($active_tab == 2) { echo " active"; } ?>" id="effekts-tab" data-toggle="tab" href="#effects" role="tab" aria-controls="effects" aria-selected="false">Effekte</a>
  </li>
</ul>

<div class="tab-content" id="myTabContent">
  <div class="tab-pane fade<?php if ($active_tab == 0) { echo " show active"; } ?>" id="mobs" role="tabpanel" aria-labelledby="mobs-tab"><?php getTabTable($mysqli, 0, $spawn_timeout, $spawn_timeout_time); ?></div>
  <div class="tab-pane fade<?php if ($active_tab == 1) { echo " show active"; } ?>" id="items" role="tabpanel" aria-labelledby="items-tab"><?php getTabTable($mysqli, 1, $spawn_timeout, $spawn_timeout_time); ?></div>
  <div class="tab-pane fade<?php if ($active_tab == 2) { echo " show active"; } ?>" id="effects" role="tabpanel" aria-labelledby="effects-tab"><?php getTabTable($mysqli, 2, $spawn_timeout, $spawn_timeout_time); ?></div>
</div>



</div>
</div>
</div>