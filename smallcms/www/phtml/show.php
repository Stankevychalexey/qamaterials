<div id="articles">
<h2>Статьи</h2>
<?php foreach($this->articles as $article): ?>
<table class="formholder">
    <tbody>
        <tr><td><a href="?action=article&id=<?php echo $article["id"] ?>"><?php echo $article["title"] ?></a></td></tr>
        <tr><td><?php echo $article["description"] ?></td></tr>
        <tr><td><hr /></td></tr>
    </tbody>
</table>
<?php endforeach; ?>
<table class="formholder">
    <tbody>
        <tr><td>Страницы:
        <?php for ($i = 1; $i <= $this->pages; $i++) : ?>
            <?php if($this->page == $i): ?>
                <?php echo $i; ?>
            <?php else: ?>
                <a href="?page=<?php echo $i?>"><?php echo $i ?></a>
            <?php endif; ?>
        <?php endfor; ?>
        </td></tr>
    </tbody>
</table>
</div>
