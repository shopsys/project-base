<?php

declare(strict_types=1);

namespace App\Model\ProductFeed\Mergado\FeedItem;

use App\Model\Product\Brand\Brand;
use Shopsys\FrameworkBundle\Model\Feed\FeedItemInterface;
use Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPrice;

class MergadoFeedItem implements FeedItemInterface
{
    private const CATEGORY_PATH_SEPARATOR = ' > ';
    private const SHORT_DESCRIPTION_SEPARATOR = '. ';
    public const FLAGS_MAP = [
        1 => 'Akce',
        2 => 'Cenový HIT',
        3 => 'Novinka',
        4 => 'Výprodej',
        5 => 'Vyrobeno v CZ',
        6 => 'Vyrobeno v DE',
        7 => 'Vyrobeno v SK',
    ];

    /**
     * @var int
     */
    private $id;

    /**
     * @var string
     */
    private $name;

    /**
     * @var string
     */
    private $url;

    /**
     * @var array
     */
    private $categoryPath;

    /**
     * @var array
     */
    private $shortDescriptionUsp;

    /**
     * @var string
     */
    private $description;

    /**
     * @var int
     */
    private $deliveryDays;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPrice
     */
    private $price;

    /**
     * @var \App\Model\Product\Brand\Brand|null
     */
    private $brand;

    /**
     * @var string|null
     */
    private $imageUrl;

    /**
     * @var array
     */
    private $galleryImageUrls;

    /**
     * @var array
     */
    private $parameters;

    /**
     * @var string
     */
    private $productNo;

    /**
     * @var int|null
     */
    private $mainVariantId;

    /**
     * @var string
     */
    private $currencyCode;

    /**
     * @var \Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPrice
     */
    private ProductPrice $highProductPrice;

    /**
     * @var string[]
     */
    private array $flags;

    /**
     * @var int|null
     */
    private ?int $availability;

    /**
     * @param int $id
     * @param string $productNo
     * @param string $name
     * @param string $url
     * @param array $categoryPath
     * @param array $shortDescriptionUsp
     * @param int $deliveryDays
     * @param \Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPrice $price
     * @param array $galleryImageUrls
     * @param array $parameters
     * @param string $currencyCode
     * @param string|null $description
     * @param \App\Model\Product\Brand\Brand|null $brand
     * @param string|null $imageUrl
     * @param int|null $mainVariantId
     * @param \Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPrice $highProductPrice
     * @param string[] $flags
     * @param int|null $availability
     */
    public function __construct(
        int $id,
        string $productNo,
        string $name,
        string $url,
        array $categoryPath,
        array $shortDescriptionUsp,
        int $deliveryDays,
        ProductPrice $price,
        array $galleryImageUrls,
        array $parameters,
        string $currencyCode,
        ?string $description,
        ?Brand $brand,
        ?string $imageUrl,
        ?int $mainVariantId,
        ProductPrice $highProductPrice,
        array $flags,
        ?int $availability
    ) {
        $this->id = $id;
        $this->productNo = $productNo;
        $this->name = $name;
        $this->url = $url;
        $this->categoryPath = $categoryPath;
        $this->shortDescriptionUsp = $shortDescriptionUsp;
        $this->description = $description;
        $this->deliveryDays = $deliveryDays;
        $this->price = $price;
        $this->brand = $brand;
        $this->imageUrl = $imageUrl;
        $this->galleryImageUrls = $galleryImageUrls;
        $this->parameters = $parameters;
        $this->currencyCode = $currencyCode;
        $this->mainVariantId = $mainVariantId;
        $this->highProductPrice = $highProductPrice;
        $this->flags = $flags;
        $this->availability = $availability;
    }

    /**
     * @return int
     */
    public function getSeekId(): int
    {
        return $this->id;
    }

    /**
     * @return int
     */
    public function getId(): int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return string
     */
    public function getUrl(): string
    {
        return $this->url;
    }

    /**
     * @return string
     */
    public function getCategoryPath(): string
    {
        return implode(self::CATEGORY_PATH_SEPARATOR, $this->categoryPath);
    }

    /**
     * @return string
     */
    public function getShortDescription(): string
    {
        return implode(self::SHORT_DESCRIPTION_SEPARATOR, $this->shortDescriptionUsp);
    }

    /**
     * @return string|null
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * @return int
     */
    public function getDeliveryDays(): int
    {
        return $this->deliveryDays;
    }

    /**
     * @return \Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPrice
     */
    public function getPrice(): ProductPrice
    {
        return $this->price;
    }

    /**
     * @return iterable
     */
    public function getParameters(): iterable
    {
        return $this->parameters;
    }

    /**
     * @return \App\Model\Product\Brand\Brand|null
     */
    public function getBrand(): ?Brand
    {
        return $this->brand;
    }

    /**
     * @return string
     */
    public function getImageUrl(): ?string
    {
        return $this->imageUrl;
    }

    /**
     * @return array
     */
    public function getGalleryImageUrls(): array
    {
        return $this->galleryImageUrls;
    }

    /**
     * @return string
     */
    public function getProductNo(): string
    {
        return $this->productNo;
    }

    /**
     * @return string
     */
    public function getCurrencyCode(): string
    {
        return $this->currencyCode;
    }

    /**
     * @return int|null
     */
    public function getMainVariantId(): ?int
    {
        return $this->mainVariantId;
    }

    /**
     * @return \Shopsys\FrameworkBundle\Model\Product\Pricing\ProductPrice
     */
    public function getHighProductPrice(): ProductPrice
    {
        return $this->highProductPrice;
    }

    /**
     * @return string[]
     */
    public function getFlags(): array
    {
        return $this->flags;
    }

    /**
     * @return int|null
     */
    public function getAvailability(): ?int
    {
        return $this->availability;
    }
}
