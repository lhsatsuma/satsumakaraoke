<?php
$pager->setSurroundCount(2);
?>
<nav aria-label="<?= lang('Pager.pageNavigation') ?>">
	<ul class="pagination">
		<?php if ($pager->hasPrevious()) : ?>
			<li class="page-item">
				<a href="javascript:void(0)" onclick="GoToPageSubpanel('<?= $group ?>', <?= $pager->GetFirstPageNumber() ?>)" og_loc="<?= $pager->getFirst() ?>" aria-label="<?= lang('Pager.first') ?>">
					<span aria-hidden="true"><?= lang('Pager.first') ?></span>
				</a>
			</li>
		<?php endif ?>

		<?php foreach ($pager->links() as $key => $link) : ?>
			<li class="page-item <?php echo $link['active'] ? 'active' : '' ?>">
				<a href="javascript:void(0)" onclick="GoToPageSubpanel('<?= $group ?>', <?= $link['title'] ?>)" og_loc="<?= $link['uri'] ?>">
					<?= $link['title'] ?>
				</a>
			</li>
		<?php endforeach ?>

		<?php if ($pager->hasNext()) : ?>
			<li class="page-item">
				<a href="javascript:void(0)" onclick="GoToPageSubpanel('<?= $group ?>', <?= $pager->GetLastPageNumber() ?>)" og_loc="<?= $pager->getLast() ?>"  aria-label="<?= lang('Pager.last') ?>">
					<span aria-hidden="true"><?= lang('Pager.last') ?></span>
				</a>
			</li>
		<?php endif ?>
	</ul>
</nav>
