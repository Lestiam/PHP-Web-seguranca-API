<?php

declare(strict_types=1);

namespace Alura\Mvc\Controller;

use Alura\Mvc\Entity\Video;
use Alura\Mvc\Repository\VideoRepository;

class NewVideoController implements Controller
{
    public function __construct(private VideoRepository $videoRepository)
    {
    }

    public function processaRequisicao(): void
    {
        $url = filter_input(INPUT_POST, 'url', FILTER_VALIDATE_URL);
        if ($url === false) {
            header('Location: /?sucesso=0');
            return;
        }
        $titulo = filter_input(INPUT_POST, 'titulo');
        if ($titulo === false) {
            header('Location: /?sucesso=0');
            return;
        }

        $video = new Video($url, $titulo);
        if ($_FILES['image']['error'] === UPLOAD_ERR_OK) { //se a chave de erro na variavel imagem for igual UPLOAD_ERR_OK (0), eu envio a imagem
            $finfo = new \finfo(FILEINFO_MIME_TYPE); //verifica o tipo de arquivo que está vindo, mesmo que a extensão seja .jpg, se o arquivo não for uma imagem, ele bloqueia

            $mimeType = $finfo->file($_FILES['image']['name']);

            if (is_string($mimeType) && str_starts_with($mimeType, 'image/')) {
                $safeFileName = uniqid('upload_') . '_' . pathinfo($_FILES['image']['name'], PATHINFO_BASENAME); //esse pathinfo pegando o base name pega só o nome básico do arquivo mais a extenão, sem pegar o caminho completo das pastas
                move_uploaded_file(
                    $_FILES['image']['tmp_name'],//tmp_name é onde o php salvou temporariamente o arquivo enviado
                    __DIR__ . '/../../public/img/uploads' . $safeFileName //move para a pasta com o nome que a foto estava no meu PC
                );
                $video->setFilePath($safeFileName);
            }
        }

        $success = $this->videoRepository->add($video);
        if ($success === false) {
            header('Location: /?sucesso=0');
        } else {
            header('Location: /?sucesso=1');
        }
    }
}
