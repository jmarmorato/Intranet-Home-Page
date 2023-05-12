<nav class="navbar navbar-expand-md navbar-<?php echo dol(); ?> bg-<?php echo dol(); ?> justify-content-between">
    <?php if ($data["config"]["header_text"] != ""): ?>
        <a class="navbar-brand" href="#"><?php echo $data["config"]["header_text"]; ?></a>
    <?php endif; ?>
    <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
	<span class="navbar-toggler-icon"></span>
    </button>
    <div class="collapse navbar-collapse <?php echo don(); ?>" id="navbarSupportedContent">
	<ul class="navbar-nav mr-auto">
	    <?php foreach ($data["config"]["nav"]["lists"] as $list): ?>
		<?php if (!isset($list["columns"])) : ?>
		    <li class="nav-item dropdown">
			<a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			    <?php echo $list["title"]; ?>
			</a>
			<div class="dropdown-menu <?php echo don(); ?>" aria-labelledby="navbarDropdown">
			    <?php foreach ($list["items"] as $menu_item): ?>
				<?php if (isset($menu_item["link_url"]) && isset($menu_item["link_text"])): ?>
				    <a class="dropdown-item" href="<?php echo $menu_item["link_url"]; ?>"><?php echo $menu_item["link_text"]; ?></a>
				<?php elseif (isset($menu_item["divider"]) && $menu_item["divider"]): ?>
				    <div class="dropdown-divider"></div>
				<?php endif; ?>
			    <?php endforeach; ?>
			</div>
		    </li>
		<?php else: ?>
		    <li class="nav-item dropdown">
			<a href="#" class="nav-link dropdown-toggle" data-toggle="dropdown"><?php echo $list["title"]; ?> <b class="caret"></b></a>
			<ul class="dropdown-menu multi-column <?php echo dol(); ?> columns-<?php echo count($list["columns"]); ?>">
			    <div class="row">
				<?php foreach ($list["columns"] as $menu_items): ?>
	    			<div class="col-sm-<?php echo 12 / count($list["columns"]); ?>">
	    			    <ul class="multi-column-dropdown">
					    <?php foreach ($menu_items as $menu_item): ?>
						<?php if (isset($menu_item["link_url"]) && isset($menu_item["link_text"])): ?>
		    				<a class="dropdown-item" href="<?php echo $menu_item["link_url"]; ?>"><?php echo $menu_item["link_text"]; ?></a>
						<?php elseif (isset($menu_item["divider"]) && $menu_item["divider"]): ?>
		    				<div class="dropdown-divider"></div>
						<?php elseif (isset($menu_item["header"])): ?>
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
	<form class="form-inline my-2 my-lg-0" id="searchForm" method="GET" action="this-will-change" onsubmit="googleSearch()">
	    <div class="input-group">
		<div class="input-group-prepend">
		    <button type="button" class="btn btn-outline-<?php echo sbdol(); ?>" onclick="googleSearch();">Google</button>
		    <button type="button" class="btn btn-outline-<?php echo sbdol(); ?> dropdown-toggle dropdown-toggle-split" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
			<span class="sr-only">Choose Search</span>
		    </button>
		    <div class="dropdown-menu <?php don(); ?>">
			<a class="dropdown-item" onclick="googleSearch();">Google</a>
			<a class="dropdown-item" onclick="youtubeSearch();">YouTube</a>
			<a class="dropdown-item" onclick="redditSearch();">Reddit</a>
		    </div>
		</div>
		<input type="text" class="form-control <?php echo sbdol(); ?>" id="appendedInputButton" name="this-will-change">
	    </div>
	</form>
    </div>
</nav>
