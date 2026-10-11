<?php

declare(strict_types=1);

namespace Facturapi\Resources;

use Facturapi\Http\FacturapiHttpClient;
use Facturapi\Exceptions\FacturapiException;

class CartaPorteCatalogs {
	public function __construct(private readonly FacturapiHttpClient $client) {}

    /**
     * Air transport codes (Carta Porte 3.1)
     *
     * @param array|null $params Search parameters.
     * @return mixed JSON-decoded response.
     * @throws FacturapiException
     */
    public function searchAirTransportCodes(?array $params = null): mixed
    {
        return $this->client->get('catalogs/cartaporte/' . "air-transport-codes");
    }

    /**
     * Auto transport configurations (Carta Porte 3.1)
     *
     * @param array|null $params Search parameters.
     * @return mixed JSON-decoded response.
     * @throws FacturapiException
     */
    public function searchTransportConfigs(?array $params = null): mixed
    {
        return $this->client->get('catalogs/cartaporte/' . "transport-configs");
    }

    /**
     * Rights of passage (Carta Porte 3.1)
     *
     * @param array|null $params Search parameters.
     * @return mixed JSON-decoded response.
     * @throws FacturapiException
     */
    public function searchRightsOfPassage(?array $params = null): mixed
    {
        return $this->client->get('catalogs/cartaporte/' . "rights-of-passage");
    }

    /**
     * Customs documents (Carta Porte 3.1)
     *
     * @param array|null $params Search parameters.
     * @return mixed JSON-decoded response.
     * @throws FacturapiException
     */
    public function searchCustomsDocuments(?array $params = null): mixed
    {
        return $this->client->get('catalogs/cartaporte/' . "customs-documents");
    }

    /**
     * Packaging types (Carta Porte 3.1)
     *
     * @param array|null $params Search parameters.
     * @return mixed JSON-decoded response.
     * @throws FacturapiException
     */
    public function searchPackagingTypes(?array $params = null): mixed
    {
        return $this->client->get('catalogs/cartaporte/' . "packaging-types");
    }

    /**
     * Trailer types (Carta Porte 3.1)
     *
     * @param array|null $params Search parameters.
     * @return mixed JSON-decoded response.
     * @throws FacturapiException
     */
    public function searchTrailerTypes(?array $params = null): mixed
    {
        return $this->client->get('catalogs/cartaporte/' . "trailer-types");
    }

    /**
     * Hazardous materials (Carta Porte 3.1)
     *
     * @param array|null $params Search parameters.
     * @return mixed JSON-decoded response.
     * @throws FacturapiException
     */
    public function searchHazardousMaterials(?array $params = null): mixed
    {
        return $this->client->get('catalogs/cartaporte/' . "hazardous-materials");
    }

    /**
     * Naval authorizations (Carta Porte 3.1)
     *
     * @param array|null $params Search parameters.
     * @return mixed JSON-decoded response.
     * @throws FacturapiException
     */
    public function searchNavalAuthorizations(?array $params = null): mixed
    {
        return $this->client->get('catalogs/cartaporte/' . "naval-authorizations");
    }

    /**
     * Port stations (air/sea/land) (Carta Porte 3.1)
     *
     * @param array|null $params Search parameters.
     * @return mixed JSON-decoded response.
     * @throws FacturapiException
     */
    public function searchPortStations(?array $params = null): mixed
    {
        return $this->client->get('catalogs/cartaporte/' . "port-stations");
    }

    /**
     * Marine containers (Carta Porte 3.1)
     *
     * @param array|null $params Search parameters.
     * @return mixed JSON-decoded response.
     * @throws FacturapiException
     */
    public function searchMarineContainers(?array $params = null): mixed
    {
        return $this->client->get('catalogs/cartaporte/' . "marine-containers");
    }
}
