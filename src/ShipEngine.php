<?php
/**
 * PHP Implementation for ShipEngine API
 *
 * This file is subject to the terms and conditions defined in
 * file 'LICENSE.txt', which is found in the root folder of
 * this source code package.
 *
 * @author    John Hall
 */
namespace jsamhall\ShipEngine;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\RequestException;
use jsamhall\ShipEngine\Carriers\CarrierId;
use jsamhall\ShipEngine\Carriers\CarrierCode;
use jsamhall\ShipEngine\Carriers\FedEx\FedEx;
use jsamhall\ShipEngine\Carriers\UPS\Settings as UpsSettings;
use jsamhall\ShipEngine\Carriers\UPS\UPS;
use jsamhall\ShipEngine\Carriers\USPS\StampsDotCom;
use jsamhall\ShipEngine\Labels\LabelId;
use jsamhall\ShipEngine\Rating\Rate;
use jsamhall\ShipEngine\Rating\RateId;
use Psr\Http\Message\ResponseInterface;
use jsamhall\ShipEngine\Address\Factory;
use jsamhall\ShipEngine\Address\Address;
use jsamhall\ShipEngine\Address\FormatterInterface;

/**
 * Class ShipEngine
 *
 * @package ShipEngine
 */
class ShipEngine
{
    /**
     * @var Factory
     */
    protected $addressFactory;

    /**
     * @var Client
     */
    protected $client;

    /**
     * ShipEngine constructor.
     *
     * @param string                     $apiKey
     * @param FormatterInterface $addressFormatter
     * @param array                      $options
     */
    public function __construct(string $apiKey, FormatterInterface $addressFormatter, array $options = [])
    {
        $defaultOptions = [
            'base_uri' => 'https://api.shipengine.com/v1/',
            'headers' => [
                'Content-Type' => 'application/json',
                'API-Key'      => $apiKey,
            ],
        ];

        $options = $options + $defaultOptions;
        $this->addressFactory = new Factory($addressFormatter);
        $this->client = new HttpClient($options);
    }

    /**
     * Format an address using the Factory's Formatter
     *
     * @param mixed $address Domain address as expected by the Address\Formatter implementation
     * @return Address
     */
    public function formatAddress($address)
    {
        return $this->addressFactory->factory($address);
    }

    /**
     * Validates the given Addresses against ShipEngine's Address Validator
     *
     * @link https://docs.shipengine.com/docs/address-validation
     *
     * @param array $addresses Array of Address\Address or domain Addresses which are passed through the Formatter
     *
     * @return AddressVerification\VerificationResult[] Array of Verification Results for every address requested
     * @throws RequestException
     * @throws ClientException
     */
    public function validateAddresses(array $addresses)
    {
        $addressData = $this->addressFactory->getAddressData($addresses);

        $response = $this->client->post('addresses/validate', [
            'json' => $addressData
        ]);

        return array_map(function ($data) {
            return new AddressVerification\VerificationResult($data);
        }, $this->getResponseData($response));
    }

    /**
     * @param Address $address
     * @return AddressVerification\VerificationResult
     * @throws RequestException
     * @throws ClientException
     */
    public function validateAddress(Address $address)
    {
        $addressData = $address->toArray();

        $response = $this->client->post('addresses/validate', [
            'json' => $addressData
        ]);

        return new AddressVerification\VerificationResult($this->getResponseData($response, 0));
    }

    /**
     * Lists all Carriers setup in the ShipEngine account
     *
     * @link https://docs.shipengine.com/docs/list-your-carriers
     *
     * @return Carriers\Carrier[]
     * @throws RequestException
     * @throws ClientException
     */
    public function listCarriers()
    {
        $response = $this->client->get('carriers');

        return array_map(function ($carrier) {
            return new Carriers\Carrier($carrier);
        }, $this->getResponseData($response, 'carriers'));
    }

    /**
     * Get a single Carrier
     *
     * @param string $carrierId
     * @return Carriers\Carrier
     * @throws RequestException
     * @throws ClientException
     */
    public function getCarrier(string $carrierId)
    {
        $endpoint = sprintf("carriers/%s", $carrierId);
        $response = $this->client->get($endpoint);

        return new Carriers\Carrier($this->getResponseData($response));
    }

    /**
     * @param CarrierCode $carrierCode
     * @param string $carrierId
     * @return bool
     * @throws RequestException
     * @throws ClientException
     */
    public function removeCarrier(CarrierCode $carrierCode, string $carrierId): bool
    {
        $endpoint = sprintf('connections/carriers/%1$s/%2$s', $carrierCode, $carrierId);
        $response = $this->client->delete($endpoint);

        return $this->isSuccessful($response);
    }

    /**
     * List all Services offered by a Carrier
     *
     * @param string $carrierId
     * @return Carriers\Service[]
     * @throws RequestException
     * @throws ClientException
     */
    public function listCarrierServices(string $carrierId)
    {
        $endpoint = sprintf("carriers/%s/services", $carrierId);
        $response = $this->client->get($endpoint);

        return array_map(function ($carrier) {
            return new Carriers\Service($carrier);
        }, $this->getResponseData($response, 'services'));
    }

    /**
     * List all Package Types offered by a Carrier
     *
     * @param string $carrierId
     * @return Carriers\PackageType[]
     * @throws RequestException
     * @throws ClientException
     */
    public function listCarrierPackageTypes(string $carrierId)
    {
        $endpoint = sprintf("carriers/%s/packages", $carrierId);
        $response = $this->client->get($endpoint);

        return array_map(function ($carrier) {
            return new Carriers\PackageType($carrier);
        }, $this->getResponseData($response, 'packages'));
    }

    /**
     * Get all Options offered by a Carrier
     *
     * @param string $carrierId
     * @return Carriers\AvailableOption[]
     * @throws RequestException
     * @throws ClientException
     */
    public function getCarrierOptions(string $carrierId)
    {
        $endpoint = sprintf("carriers/%s/options", $carrierId);
        $response = $this->client->get($endpoint);

        return array_map(function ($carrier) {
            return new Carriers\AvailableOption($carrier);
        }, $this->getResponseData($response, 'options'));
    }

    /**
     * Get all Rates for the given Shipment and RateOptions
     *
     * @param Rating\Shipment $shipment
     * @param Rating\Options $rateOptions
     * @return Api\Response|Rating\RateResponse
     * @throws RequestException
     * @throws ClientException
     */
    public function getRates(Rating\Shipment $shipment, Rating\Options $rateOptions)
    {
        if (! count($rateOptions)) {
            throw new \InvalidArgumentException("\$rateOptions cannot be empty");
        }

        $response = $this->client->post('rates', [
            'json' => json_encode([
                'shipment'     => $shipment->toArray(),
                'rate_options' => $rateOptions->toArray()
            ])
        ]);

        return new Rating\RateResponse($this->getResponseData($response, 'rate_response'));
    }

    /**
     * Gets a specific Rate from known rateId.
     *
     * @param RateId $rateId
     * @return Rate
     * @throws RequestException
     * @throws ClientException
     */
    public function getRate(RateId $rateId): Rate
    {
        $endpoint = 'rates/' . $rateId;
        $response = $this->client->get($endpoint);

        return new Rating\Rate($this->getResponseData($response));
    }

    /**
     * Create a Shipping Label
     *
     * @param Labels\Shipment $shipment
     * @param bool $testMode
     * @return Labels\Response
     * @throws RequestException
     * @throws ClientException
     */
    public function createLabel(Labels\Shipment $shipment, $testMode = false)
    {
        $response = $this->client->post('labels', [
            'json' => json_encode([
                'shipment'   => $shipment->toArray(),
                'test_label' => $testMode
            ])
        ]);

        return new Labels\Response($this->getResponseData($response));
    }

    /**
     * Create a Shipping Label via Rate
     *
     * @param Labels\RateLabel $rateLabel
     * @param bool $testMode
     * @return Labels\Response
     * @throws RequestException
     * @throws ClientException
     */
    public function createLabelFromRate(Labels\RateLabel $rateLabel, $testMode = false)
    {
        $response = $this->client->post('labels/rates/' . (string) $rateLabel->getRateId(), [
            'json' => json_encode([
                'validate_address'    => $rateLabel->getAddressValidation(),
                'label_layout'        => $rateLabel->getLabelLayout(),
                'label_format'        => $rateLabel->getLabelFormat(),
                'label_download_type' => $rateLabel->getLabelDownloadFormat(),
                'test_label'          => $testMode,
            ]),
        ]);

        return new Labels\Response($this->getResponseData($response));
    }

    /**
     * @param LabelId $label
     * @return Labels\VoidResponse
     * @throws RequestException
     * @throws ClientException
     */
    public function voidLabel(LabelId $label)
    {
        $response = $this->client->put('labels/' . (string) $label . '/void');

        return new Labels\VoidResponse($this->getResponseData($response));
    }

    /**
     * Connect a Stamps.com account.
     *
     * @param StampsDotCom $stampsDotCom
     * @return CarrierId
     * @throws RequestException
     * @throws ClientException
     */
    public function connectStampsDotCom(StampsDotCom $stampsDotCom)
    {
        $response = $this->client->post('connections/carriers/stamps_com', [
            'json' => json_encode($stampsDotCom->toArray())
        ]);

        return new CarrierId($this->getResponseData($response, 'carrier_id'));
    }

    /**
     * Connect a ups.com account.
     *
     * @param UPS $UPS
     * @return CarrierId
     * @throws RequestException
     * @throws ClientException
     */
    public function connectUps(UPS $UPS): CarrierId
    {
        $response = $this->client->post('connections/carriers/ups', [
            'json' => json_encode($UPS->toArray())
        ]);

        return new CarrierId($this->getResponseData($response, 'carrier_id'));
    }

    /**
     * @param CarrierId $carrierId
     * @param UpsSettings $settings
     * @return array
     * @throws RequestException
     * @throws ClientException
     */
    public function adjustUps(CarrierId $carrierId, UpsSettings $settings)
    {
        $response = $this->client->put('connections/carriers/ups/' . $carrierId . '/settings', [
            'json'  => json_encode($settings->toArray())
        ]);

        // 4/29/2020 TODO: fix response for UPS adjustments
        return $this->getResponseData($response);
    }

    /**
     * Connect a fedex.com account.
     *
     * @param FedEx $fedEx
     * @return CarrierId
     * @throws RequestException
     * @throws ClientException
     */
    public function connectFedEx(FedEx $fedEx): CarrierId
    {
        $response = $this->client->post('connections/carriers/fedex', [
            'json' => json_encode($fedEx->toArray())
        ]);

        return new CarrierId($this->getResponseData($response, 'carrier_id'));
    }

    public function isSuccessful(ResponseInterface $response): bool
    {
        $successful = [200, 201, 202, 203, 204];

        return in_array($response->getStatusCode(), $successful);
    }

    private function getResponseData(ResponseInterface $data, $key = null)
    {
        $response = json_decode($data->getBody()->getContents(), 1);

        return $key === null
            ? $response
            : $response[$key];
    }
}
