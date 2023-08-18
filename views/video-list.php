<?php
$this->layout('layout'); //diz que este arquivo utiliza o meu layout.php
/** @var \Alura\Mvc\Entity\Video[] $videoList */
?>

<ul class="videos__container">
    <?php foreach ($videoList as $video): ?>
        <li class="videos__item">
            <?php if ($video->getFilePath() !== null): //se minha imagem for diferente de nulo...?>
                <a href="<?= $video->url; ?>">
                    <img src="/img/uploads/<?= $video->getFilePath(); ?>" alt="" style="width: 100%"/>
                </a>
            <?php else: //se for nulo, eu utilizo o iframe a partir da url do video?>
                <iframe width="100%" height="72%" src="<?= $video->url; ?>"
                        title="YouTube video player" frameborder="0"
                        allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture"
                        allowfullscreen></iframe>
            <?php endif; ?>
            <div class="descricao-video">
                <h3><?= htmlentities($video->title); //sanitiza para que o cliente não consiga usar um script?></h3>
                <div class="acoes-video">
                    <a href="/editar-video?id=<?= $video->id; ?>">Editar</a>
                    <a href="/remover-video?id=<?= $video->id; ?>">Excluir</a>
                </div>
            </div>
        </li>
    <?php endforeach; ?>
</ul>
