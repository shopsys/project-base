<?php

namespace Shopsys\ShopBundle\Model\PickUpPlace;

use Shopsys\ShopBundle\Model\Transport\Transport;
use SimpleXMLElement;

class PickUpPlaceLoader
{
    /**
     * @var string[]
     */
    protected $daysTranslationMap;

    public function __construct()
    {
        $this->daysTranslationMap = [
            'monday' => 'Pondělí',
            'tuesday' => 'Úterý',
            'wednesday' => 'Středa',
            'thursday' => 'Čtvrtek',
            'friday' => 'Pátek',
            'saturday' => 'Sobota',
            'sunday' => 'Neděle',
        ];
    }

    /**
     * @param string $feedUrl
     * @param string $transportType
     * @return \Shopsys\ShopBundle\Model\PickUpPlace\PickUpPlaceData[]
     */
    public function load($feedUrl, $transportType)
    {
        $pickUpPlaceFeedData = [];
        $xml = simplexml_load_file($feedUrl);

        if ($xml === false) {
            throw new \Shopsys\ShopBundle\Model\PickUpPlace\Exception\PickUpPlaceXmlParsingException(
                'Could not load XML file "' . $feedUrl . '".'
            );
        }
        try {
            foreach ($xml->children() as $xmlNode) {
                if ($transportType === Transport::TYPE_ZASILKOVNA) {
                    if ($xmlNode->getName() === 'branches') {
                        foreach ($xmlNode->children() as $branch) {
                            $pickUpPlaceFeedData[] = $this->parseZasilkovnaData($branch);
                        }
                    }
                }
            }
        } catch (\Symfony\Component\Debug\Exception\ContextErrorException $exception) {
            throw new \Shopsys\ShopBundle\Model\PickUpPlace\Exception\PickUpPlaceXmlParsingException(
                'Could not parse XML file "' . $feedUrl . '": ' . $exception->getMessage(),
                $exception
            );
        }
        return $pickUpPlaceFeedData;
    }

    /**
     * @param \SimpleXMLElement $xmlNode
     * @return \Shopsys\ShopBundle\Model\PickUpPlace\PickUpPlaceData
     */
    protected function parseZasilkovnaData(SimpleXMLElement $xmlNode)
    {
        $pickUpPlaceData = new PickUpPlaceData();
        $pickUpPlaceData->placeId = (string)$xmlNode->id;
        $pickUpPlaceData->postCode = str_replace(' ', '', (string)$xmlNode->zip);
        $pickUpPlaceData->name = (string)$xmlNode->name;
        $pickUpPlaceData->street = (string)$xmlNode->street;
        $pickUpPlaceData->city = (string)$xmlNode->city;
        $pickUpPlaceData->description = $this->parseOpeningHours($xmlNode->openingHours->regular);
        $pickUpPlaceData->countryCode = (string)$xmlNode->country;
        $pickUpPlaceData->transportType = Transport::TYPE_ZASILKOVNA;
        $pickUpPlaceData->gpsLongitude = (string)$xmlNode->longitude;
        $pickUpPlaceData->gpsLatitude = (string)$xmlNode->latitude;
        return $pickUpPlaceData;
    }

    /**
     * @param \SimpleXMLElement $xmlOpeningHours
     * @return string
     */
    protected function parseOpeningHours(SimpleXMLElement $xmlOpeningHours)
    {
        $descriptionRows = [];
        foreach ($xmlOpeningHours->children() as $xmlOpeningHoursDay) {
            /* @var $xmlOpeningHoursDay \SimpleXMLElement */
            $day = $this->daysTranslationMap[$xmlOpeningHoursDay->getName()];
            $hours = (string)$xmlOpeningHoursDay;
            $descriptionRows[] = $day . ': ' . ($hours === '' ? '---' : $hours);
        }
        return implode("\n", $descriptionRows);
    }
}
