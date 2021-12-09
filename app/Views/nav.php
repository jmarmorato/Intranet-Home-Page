<nav class="navbar navbar-expand-lg navbar-light bg-light">
  <?php if($config["header_text"] != ""): ?>
    <a class="navbar-brand" href="#"><?php echo $config["header_text"]; ?></a>
  <?php endif; ?>
  <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
    <span class="navbar-toggler-icon"></span>
  </button>
  <div class="collapse navbar-collapse" id="navbarSupportedContent">
    <ul class="navbar-nav mr-auto">
      <?php foreach($config["nav"]["lists"] as $list): ?>
        <?php if (!isset($list["columns"])) : ?>
          <li class="nav-item dropdown">
            <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
              <?php echo $list["title"]; ?>
            </a>
            <div class="dropdown-menu" aria-labelledby="navbarDropdown">
              <?php foreach($list["items"] as $menu_item): ?>
                <?php if(isset($menu_item["link_url"]) && isset($menu_item["link_text"])): ?>
                  <a class="dropdown-item" href="<?php echo $menu_item["link_url"]; ?>"><?php echo $menu_item["link_text"]; ?></a>
                <?php elseif(isset($menu_item["divider"]) && $menu_item["divider"]): ?>
                  <div class="dropdown-divider"></div>
                <?php endif; ?>
              <?php endforeach; ?>
            </div>
          </li>
        <?php else: ?>
          <li class="nav-item dropdown">
            <a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown"><?php echo $list["title"]; ?> <b class="caret"></b></a>
            <ul class="dropdown-menu multi-column columns-<?php echo count($list["columns"]); ?>">
              <div class="row">
                <?php foreach($list["columns"] as $menu_items): ?>
                  <div class="col-sm-<?php echo 12 / count($list["columns"]); ?>">
                    <ul class="multi-column-dropdown">
                      <?php foreach($menu_items as $menu_item): ?>
                        <?php if(isset($menu_item["link_url"]) && isset($menu_item["link_text"])): ?>
                          <a class="dropdown-item" href="<?php echo $menu_item["link_url"]; ?>"><?php echo $menu_item["link_text"]; ?></a>
                        <?php elseif(isset($menu_item["divider"]) && $menu_item["divider"]): ?>
                          <div class="dropdown-divider"></div>
                        <?php elseif(isset($menu_item["header"])): ?>
                          <li><h4 class="dropdown-header"><?php echo $menu_item["header"]; ?></h4></li>
                        <?php endif; ?>
                      <?php endforeach; ?>
                    </ul>
                  </div>
                <?php endforeach; ?>
              </div>
            </ul>
          </li>
        <?php endif; ?>
      <?php endforeach; ?>
    </ul>

    <form id="searchForm" method="GET" action="this-will-change">
      <div class="input-group search">
        <div class="input-group-prepend">
          <div class="btn-group">
            <button class="btn btn-outline-success my-2 my-sm-0 search-select" data-toggle="dropdown">
              Search
              <span class="caret"></span>
            </button>
            <ul class="dropdown-menu">
              <li class="dropdown-item" onclick="googleSearch();">Google</li>
              <li class="dropdown-item" onclick="youtubeSearch();">YouTube</li>
              <li class="dropdown-item" onclick="redditSearch();">Reddit</li>
            </ul>
          </div>
        </div>
        <input class="form-control mr-sm-2 search-box typeahead" id="appendedInputButton" name="this-will-change" type="text">
      </div>
    </form>
    
  </div>
</nav>
