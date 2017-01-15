<?php
declare(strict_types = 1);

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\Mapping as ORM;
use JMS\Serializer\Annotation as JmsAnnotation;

/**
 * Категории(разделы)
 *
 * @ORM\Table(
 *     name="category",
 *     options={
 *          "collate"="utf8mb4_unicode_ci",
 *          "charset"="utf8mb4",
 *          "comment"="Разделы"
 *     }
 * )
 *
 * @ORM\Entity(repositoryClass="AppBundle\Repository\Products\ProductCatalogRepository")
 * @ORM\HasLifecycleCallbacks*
 */
class Category
{

    /**
     * Порядковый номер
     *
     * @ORM\Column(
     *     name="id",
     *     type="integer",
     *     options={
     *          "comment" = "Id раздела"
     *     }
     * )
     * @ORM\Id
     */
    protected $id;

    /**
     * Название раздела
     *
     * @var string
     *
     * @JmsAnnotation\Type("string")
     * @JmsAnnotation\Since("1.0")
     *
     * @ORM\Column(
     *     name="name",
     *     type="string",
     *     length=255,
     *     nullable=false,
     *     options={
     *          "fixed":false,
     *          "comment" = "Название раздела"
     *     }
     * )
     */
    protected $categoryName;

//    /**
//     * Многие каталоги к многим продуктам
//     *
//     * @ORM\ManyToMany(
//     *     targetEntity = "AppBundle\Entity\User",
//     *     mappedBy     = "catalogs"
//     * )
//     */
//    protected $products;


    /**
     * Идентификатор родительского каталога
     *
     * @return integer
     */
    public function getPid(): int
    {
        return (int)$this->pid;
    }

    /**
     * Set pid
     *
     * @param integer $pid
     *
     * @return ProductCatalog
     */
    public function setPid($pid)
    {
        $this->pid = $pid;

        return $this;
    }

    /**
     * Get pid0
     *
     * @deprecated Это делалось для выравнивания до 3х уровней вложенности
     * @return integer
     */
    public function getPid0(): int
    {
        return (int)$this->pid0;
    }

    /**
     * Set pid0
     *
     * @param integer $pid0
     *
     * @deprecated Это делалось для выравнивания до 3х уровней вложенности
     * @return ProductCatalog
     */
    public function setPid0($pid0)
    {
        $this->pid0 = $pid0;

        return $this;
    }

    /**
     * Get pid1
     *
     * @deprecated Это делалось для выравнивания до 3х уровней вложенности
     * @return integer
     */
    public function getPid1(): int
    {
        return (int)$this->pid1;
    }

    /**
     * Set pid1
     *
     * @param integer $pid1
     *
     * @deprecated Это делалось для выравнивания до 3х уровней вложенности
     * @return ProductCatalog
     */
    public function setPid1($pid1)
    {
        $this->pid1 = $pid1;

        return $this;
    }

    /**
     * Get urlOrig
     *
     * @return string
     */
    public function getUrlOrig()
    {
        return $this->urlOrig;
    }

    /**
     * Set urlOrig
     *
     * @param string $urlOrig
     *
     * @return ProductCatalog
     */
    public function setUrlOrig($urlOrig)
    {
        $this->urlOrig = $urlOrig;

        return $this;
    }

    /**
     * Геттер названия каталога
     *
     * @return string
     */
    public function getCatalogName()
    {
        return $this->catalogName;
    }

    /**
     * Сеттер названия каталога
     *
     * @param string $catalogName
     *
     * @return ProductCatalog
     */
    public function setCatalogName($catalogName)
    {
        $this->catalogName = $catalogName;

        return $this;
    }

    /**
     * Добавить продукт
     *
     * @param Product $product
     *
     * @return ProductCatalog
     */
    public function addProduct(Product $product)
    {
        if (false === $this->products->contains($product)) {
            $this->products->add($product);
        }

        return $this;
    }

    /**
     * Получить все продукты этого каталога
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProducts()
    {
        return $this->products;
    }

    /**
     * Добавить свойство
     *
     * @param ProductProperty $property
     *
     * @return $this
     */
    public function addProperty(ProductProperty $property)
    {
        if (false === $this->properties->contains($property)) {
            $this->properties->add($property);
        }

        return $this;
    }

    /**
     * Удалить свойство
     *
     * @param ProductProperty $property
     */
    public function removeProperty(ProductProperty $property)
    {
        $this->properties->removeElement($property);
    }

    /**
     * Геттер всех возможных свойств товаров в каталоге
     *
     * @return \Doctrine\Common\Collections\Collection
     */
    public function getProperties()
    {
        return $this->properties;
    }
}
