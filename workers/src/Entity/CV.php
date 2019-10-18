<?php

namespace App\Entity;


use ApiPlatform\Core\Annotation\ApiResource;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;
use App\Controller\UploadCvAction;

/**
 *@ORM\Entity()
 * @Vich\Uploadable()
 * @ApiResource(
 *     attributes={"order"={"id": "ASC"}},
 *     collectionOperations={
 *          "get",
 *          "post"={
 *              "method"="POST",
 *              "path"="/cv",
 *              "controller"=UploadCvAction::class,
 *              "defaults"={"_api_receive"=false}
 *          }
 *     }
 * )
 */
class CV
{
    /**
     *@ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\File(
     *     maxSize="2048k",
     *     mimeTypes={"application/pdf", "application/x-pdf"},
     *     mimeTypesMessage="Nieprawidłowy plik PDF"
     * )
     * @Vich\UploadableField(mapping="cv", fileNameProperty="urlcv")
     * @Assert\NotNull()
     */
    private $filecv;

    /**
     *@ORM\Column(nullable=true)
     * @Groups({"get-work-post-with-author"})
     */
    private $urlcv;


    public function getId()
    {
        return $this->id;
    }


    public function getFilecv()
    {
        return $this->filecv;
    }


    public function setFilecv($filecv): void
    {
        $this->filecv = $filecv;
    }


    public function getUrlcv()
    {
        return '/cv/' . $this->urlcv;
    }


    public function setUrlcv($urlcv): void
    {
        $this->urlcv = $urlcv;
    }




}